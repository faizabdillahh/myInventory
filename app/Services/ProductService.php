<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\ProductRepository;

/**
 * Service Layer untuk Entitas Produk
 * Menangani seluruh logika bisnis, validasi aturan domain, dan transaksi database.
 */
class ProductService extends BaseService
{
    protected ProductRepositoryInterface $productRepo;

    public function __construct(?ProductRepositoryInterface $productRepo = null)
    {
        parent::__construct();
        $this->productRepo = $productRepo ?? new ProductRepository();
    }

    /**
     * Mengambil data lengkap untuk dashboard (daftar produk + ringkasan statistik)
     */
    public function getDashboardData(string $search = '', string $category = '', string $status = ''): array
    {
        return [
            'products' => $this->productRepo->getAllWithUser($search, $category, $status),
            'stats'    => $this->productRepo->getSummaryStats(),
        ];
    }

    /**
     * Mengambil detail satu data produk berdasarkan ID
     */
    public function getProduct(int $id): ?array
    {
        return $this->productRepo->findById($id);
    }

    /**
     * Menambahkan produk baru ke dalam inventaris
     */
    public function createProduct(ProductDTO $dto): bool
    {
        return $this->executeInTransaction(function () use ($dto) {
            // Validasi aturan bisnis: SKU tidak boleh duplikat
            if ($this->productRepo->isSkuTaken($dto->kodeProduk)) {
                throw new \InvalidArgumentException("Kode SKU '{$dto->kodeProduk}' sudah terdaftar dalam sistem.");
            }

            $inserted = $this->productRepo->create($dto->toArray());

            if (!$inserted) {
                throw new \RuntimeException('Gagal menyimpan baris produk ke database.');
            }

            $this->logActivity(
                'PRODUCT',
                'CREATE',
                "Menambahkan produk baru: '{$dto->namaProduk}' (SKU: {$dto->kodeProduk}), Kategori: {$dto->kategori}, Stok: {$dto->stok}",
                (string)$inserted,
                $dto->namaProduk,
                $dto->toArray()
            );

            // Catat log info penambahan produk
            log_message('info', "[ProductService::createProduct] Produk baru '{$dto->namaProduk}' (SKU: {$dto->kodeProduk}) berhasil ditambahkan oleh User #{$dto->userId}.");

            return true;
        });
    }

    /**
     * Memperbarui informasi produk
     */
    public function updateProduct(int $id, ProductDTO $dto): bool
    {
        return $this->executeInTransaction(function () use ($id, $dto) {
            $existing = $this->productRepo->findById($id);
            if (!$existing) {
                throw new \InvalidArgumentException("Data produk dengan ID #{$id} tidak ditemukan.");
            }

            // Validasi SKU unik selain produk yang sedang diedit
            if ($this->productRepo->isSkuTaken($dto->kodeProduk, $id)) {
                throw new \InvalidArgumentException("Kode SKU '{$dto->kodeProduk}' sudah digunakan oleh produk lain.");
            }

            $updated = $this->productRepo->update($id, $dto->toArray());

            if (!$updated) {
                throw new \RuntimeException("Gagal memperbarui data produk #{$id}.");
            }

            $diff = ActivityLogService::buildDiff($existing, $dto->toArray());
            $diffText = !empty($diff) ? 'Bidang diubah: ' . implode(', ', array_keys($diff)) : 'Tanpa modifikasi nilai';

            $this->logActivity(
                'PRODUCT',
                'UPDATE',
                "Memperbarui produk: '{$dto->namaProduk}' (SKU: {$dto->kodeProduk}). {$diffText}",
                (string)$id,
                $dto->namaProduk,
                $diff
            );

            log_message('info', "[ProductService::updateProduct] Data produk #{$id} berhasil diperbarui oleh User #{$dto->userId}.");

            return true;
        });
    }

    /**
     * Menghapus produk dari inventaris
     */
    public function deleteProduct(int $id): bool
    {
        return $this->executeInTransaction(function () use ($id) {
            $existing = $this->productRepo->findById($id);
            if (!$existing) {
                throw new \InvalidArgumentException("Data produk tidak ditemukan.");
            }

            $deleted = $this->productRepo->delete($id);

            if (!$deleted) {
                throw new \RuntimeException("Gagal menghapus produk #{$id}.");
            }

            $this->logActivity(
                'PRODUCT',
                'DELETE',
                "Menghapus data produk: '{$existing['nama_produk']}' (SKU: {$existing['kode_produk']})",
                (string)$id,
                $existing['nama_produk'],
                $existing
            );

            log_message('info', "[ProductService::deleteProduct] Produk '{$existing['nama_produk']}' (ID #{$id}) berhasil dihapus.");

            return true;
        });
    }
}
