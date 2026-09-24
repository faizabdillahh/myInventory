<?php

namespace App\Services;

use App\DTOs\SupplierDTO;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use App\Repositories\SupplierRepository;

/**
 * Service Layer untuk Entitas Supplier
 * Menangani aturan bisnis domain supplier dan proteksi integritas data relasional.
 */
class SupplierService extends BaseService
{
    protected SupplierRepositoryInterface $supplierRepo;

    public function __construct(?SupplierRepositoryInterface $supplierRepo = null)
    {
        parent::__construct();
        $this->supplierRepo = $supplierRepo ?? new SupplierRepository();
    }

    /**
     * Mengambil seluruh daftar supplier
     */
    public function getAllSuppliers(string $search = ''): array
    {
        return $this->supplierRepo->getAll($search);
    }

    /**
     * Mengambil daftar ringkas untuk pilihan dropdown di form
     */
    public function getSuppliersForDropdown(): array
    {
        return $this->supplierRepo->getForDropdown();
    }

    /**
     * Mengambil detail satu supplier
     */
    public function getSupplier(int $id): ?array
    {
        return $this->supplierRepo->findById($id);
    }

    /**
     * Mendaftarkan supplier baru
     */
    public function createSupplier(SupplierDTO $dto): bool
    {
        return $this->executeInTransaction(function () use ($dto) {
            if ($this->supplierRepo->isCodeTaken($dto->kodeSupplier)) {
                throw new \InvalidArgumentException("Kode supplier '{$dto->kodeSupplier}' sudah terdaftar dalam sistem.");
            }

            $inserted = $this->supplierRepo->create($dto->toArray());

            if (!$inserted) {
                throw new \RuntimeException('Gagal menyimpan baris supplier baru.');
            }

            $this->logActivity(
                'SUPPLIER',
                'CREATE',
                "Menambahkan data pemasok baru: '{$dto->namaSupplier}' ({$dto->kodeSupplier})",
                (string)$inserted,
                $dto->namaSupplier,
                $dto->toArray()
            );

            log_message('info', "[SupplierService::createSupplier] Supplier baru '{$dto->namaSupplier}' ({$dto->kodeSupplier}) berhasil ditambahkan.");

            return true;
        });
    }

    /**
     * Memperbarui informasi supplier
     */
    public function updateSupplier(int $id, SupplierDTO $dto): bool
    {
        return $this->executeInTransaction(function () use ($id, $dto) {
            $existing = $this->supplierRepo->findById($id);
            if (!$existing) {
                throw new \InvalidArgumentException("Data supplier dengan ID #{$id} tidak ditemukan.");
            }

            if ($this->supplierRepo->isCodeTaken($dto->kodeSupplier, $id)) {
                throw new \InvalidArgumentException("Kode supplier '{$dto->kodeSupplier}' sudah digunakan oleh supplier lain.");
            }

            $updated = $this->supplierRepo->update($id, $dto->toArray());

            if (!$updated) {
                throw new \RuntimeException("Gagal memperbarui data supplier #{$id}.");
            }

            $diff = ActivityLogService::buildDiff($existing, $dto->toArray());
            $diffText = !empty($diff) ? 'Bidang diubah: ' . implode(', ', array_keys($diff)) : 'Tanpa modifikasi nilai';

            $this->logActivity(
                'SUPPLIER',
                'UPDATE',
                "Memperbarui informasi pemasok: '{$dto->namaSupplier}' ({$dto->kodeSupplier}). {$diffText}",
                (string)$id,
                $dto->namaSupplier,
                $diff
            );

            log_message('info', "[SupplierService::updateSupplier] Supplier #{$id} '{$dto->namaSupplier}' berhasil diperbarui.");

            return true;
        });
    }

    /**
     * Menghapus supplier dengan proteksi relasi produk aktif
     */
    public function deleteSupplier(int $id): bool
    {
        return $this->executeInTransaction(function () use ($id) {
            $existing = $this->supplierRepo->findById($id);
            if (!$existing) {
                throw new \InvalidArgumentException("Data supplier tidak ditemukan.");
            }

            // Proteksi integritas relasi: Tolak hapus jika masih memasok produk aktif
            $productCount = $this->supplierRepo->countProductsBySupplier($id);
            if ($productCount > 0) {
                throw new \InvalidArgumentException("Supplier '{$existing['nama_supplier']}' tidak dapat dihapus karena masih menjadi pemasok utama bagi {$productCount} produk inventaris.");
            }

            $deleted = $this->supplierRepo->delete($id);

            if (!$deleted) {
                throw new \RuntimeException("Gagal menghapus supplier #{$id}.");
            }

            $this->logActivity(
                'SUPPLIER',
                'DELETE',
                "Menghapus data pemasok: '{$existing['nama_supplier']}' ({$existing['kode_supplier']})",
                (string)$id,
                $existing['nama_supplier'],
                $existing
            );

            log_message('info', "[SupplierService::deleteSupplier] Supplier '{$existing['nama_supplier']}' (ID #{$id}) berhasil dihapus.");

            return true;
        });
    }
}
