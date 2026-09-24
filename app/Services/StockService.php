<?php

namespace App\Services;

use App\DTOs\StockMovementDTO;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\StockMovementRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\StockMovementRepository;

/**
 * Service Layer untuk Pengelolaan Mutasi Stok Barang
 * Menjamin transaksi database atomik (ACID) dan pencegahan stok minus.
 */
class StockService extends BaseService
{
    protected StockMovementRepositoryInterface $stockRepo;
    protected ProductRepositoryInterface $productRepo;

    public function __construct(
        ?StockMovementRepositoryInterface $stockRepo = null,
        ?ProductRepositoryInterface $productRepo = null
    ) {
        parent::__construct();
        $this->stockRepo   = $stockRepo ?? new StockMovementRepository();
        $this->productRepo = $productRepo ?? new ProductRepository();
    }

    /**
     * Mengambil riwayat mutasi stok untuk halaman laporan / histori
     */
    public function getMovementHistory(?string $tipe = null, ?string $search = '', int $limit = 50): array
    {
        return $this->stockRepo->getRecentMovements($tipe, $search, $limit);
    }

    /**
     * Mengambil riwayat mutasi khusus satu produk
     */
    public function getProductHistory(int $productId): array
    {
        return $this->stockRepo->getByProductId($productId);
    }

    /**
     * Memproses dan mencatat transaksi mutasi stok baru
     *
     * @param StockMovementDTO $dto
     * @return array Data produk yang telah diperbarui dan mutasi
     */
    public function recordMovement(StockMovementDTO $dto): array
    {
        return $this->executeInTransaction(function () use ($dto) {
            // 1. Ambil data produk saat ini dengan penguncian tingkat baris (Pessimistic Locking FOR UPDATE)
            $product = $this->productRepo->findById($dto->productId, true);
            if (!$product) {
                throw new \App\Exceptions\ResourceNotFoundException("Produk dengan ID #{$dto->productId} tidak ditemukan.");
            }

            $stokSebelum = (int)$product['stok'];
            $stokSesudah = $stokSebelum;
            $jumlahTercatat = $dto->jumlah;

            // 2. Kalkulasi stok sesudah berdasarkan tipe mutasi
            if ($dto->tipe === 'IN') {
                // Barang Masuk: Tambah stok
                $stokSesudah = $stokSebelum + $dto->jumlah;
            } elseif ($dto->tipe === 'OUT') {
                // Barang Keluar: Kurangi stok dengan proteksi anti-stok minus
                if ($stokSebelum < $dto->jumlah) {
                    throw new \App\Exceptions\InsufficientStockException("Stok tidak mencukupi untuk dikeluarkan! Stok fisik saat ini: {$stokSebelum} unit, kuantitas diminta: {$dto->jumlah} unit.");
                }
                $stokSesudah = $stokSebelum - $dto->jumlah;
            } elseif ($dto->tipe === 'ADJUSTMENT') {
                // Penyesuaian / Koreksi Opname: Setel langsung stok fisik ke angka target
                $stokSesudah = $dto->jumlah;
                $jumlahTercatat = abs($stokSesudah - $stokSebelum);
            }

            // 3. Perbarui stok fisik di tabel products
            $updated = $this->db->table('products')
                                ->where('id', $dto->productId)
                                ->update(['stok' => $stokSesudah]);

            if (!$updated) {
                throw new \RuntimeException("Gagal memperbarui kuantitas stok produk #{$dto->productId}.");
            }

            // 4. Catat baris transaksi di tabel stock_movements
            $movementData = [
                'product_id'   => $dto->productId,
                'user_id'      => $dto->userId,
                'supplier_id'  => $dto->supplierId,
                'customer_id'  => $dto->customerId,
                'tipe'         => $dto->tipe,
                'jumlah'       => $jumlahTercatat,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan'   => $dto->keterangan,
                'created_at'   => date('Y-m-d H:i:s'),
            ];

            $this->stockRepo->create($movementData);

            $actionName = match ($dto->tipe) {
                'IN'         => 'STOCK_IN',
                'OUT'        => 'STOCK_OUT',
                'ADJUSTMENT' => 'STOCK_ADJUSTMENT',
                default      => 'STOCK_MUTATION'
            };

            $descTipe = match ($dto->tipe) {
                'IN'         => "Mutasi Barang Masuk (+{$jumlahTercatat} unit)",
                'OUT'        => "Mutasi Barang Keluar (-{$jumlahTercatat} unit)",
                'ADJUSTMENT' => "Penyesuaian Opname Stok",
                default      => "Mutasi Stok"
            };

            $refInfo = !empty($dto->keterangan) ? " Ref: '{$dto->keterangan}'." : '';

            $this->logActivity(
                'STOCK',
                $actionName,
                "{$descTipe} pada '{$product['nama_produk']}' (SKU: {$product['kode_produk']}). Saldo: {$stokSebelum} &rarr; {$stokSesudah}.{$refInfo}",
                (string)$dto->productId,
                $product['nama_produk'],
                [
                    'sku'          => $product['kode_produk'],
                    'nama_produk'  => $product['nama_produk'],
                    'tipe'         => $dto->tipe,
                    'jumlah'       => $jumlahTercatat,
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSesudah,
                    'supplier_id'  => $dto->supplierId,
                    'customer_id'  => $dto->customerId,
                    'keterangan'   => $dto->keterangan,
                ]
            );

            log_message('info', "[StockService::recordMovement] Mutasi {$dto->tipe} ({$jumlahTercatat} unit) pada '{$product['nama_produk']}' berhasil. Stok: {$stokSebelum} -> {$stokSesudah}.");

            return [
                'product'      => $product,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'tipe'         => $dto->tipe,
                'jumlah'       => $jumlahTercatat,
            ];
        });
    }
}
