<?php

namespace App\Services;

use App\DTOs\CustomerDTO;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\CustomerRepository;

/**
 * Service Layer untuk Entitas Pelanggan / Departemen Penerima
 * Menangani aturan bisnis domain dan proteksi integritas relasional data keluar.
 */
class CustomerService extends BaseService
{
    protected CustomerRepositoryInterface $customerRepo;

    public function __construct(?CustomerRepositoryInterface $customerRepo = null)
    {
        parent::__construct();
        $this->customerRepo = $customerRepo ?? new CustomerRepository();
    }

    /**
     * Mengambil seluruh data pelanggan dengan statistik transaksi
     */
    public function getAllCustomers(string $search = ''): array
    {
        return $this->customerRepo->getAll($search);
    }

    /**
     * Mengambil daftar ringkas untuk pilihan dropdown di form
     */
    public function getCustomersForDropdown(): array
    {
        return $this->customerRepo->getForDropdown();
    }

    /**
     * Mengambil detail satu pelanggan
     */
    public function getCustomer(int $id): ?array
    {
        return $this->customerRepo->findById($id);
    }

    /**
     * Mendaftarkan pelanggan / departemen baru
     */
    public function createCustomer(CustomerDTO $dto): bool
    {
        return $this->executeInTransaction(function () use ($dto) {
            if ($this->customerRepo->isCodeTaken($dto->kodePelanggan)) {
                throw new \InvalidArgumentException("Kode pelanggan '{$dto->kodePelanggan}' sudah terdaftar dalam sistem.");
            }

            $inserted = $this->customerRepo->create($dto->toArray());

            if (!$inserted) {
                throw new \RuntimeException('Gagal menyimpan baris pelanggan baru.');
            }

            $this->logActivity(
                'CUSTOMER',
                'CREATE',
                "Menambahkan data pelanggan baru: '{$dto->namaPelanggan}' ({$dto->kodePelanggan})",
                (string)$inserted,
                $dto->namaPelanggan,
                $dto->toArray()
            );

            log_message('info', "[CustomerService::createCustomer] Pelanggan baru '{$dto->namaPelanggan}' ({$dto->kodePelanggan}) berhasil ditambahkan.");

            return true;
        });
    }

    /**
     * Memperbarui data pelanggan / departemen
     */
    public function updateCustomer(int $id, CustomerDTO $dto): bool
    {
        return $this->executeInTransaction(function () use ($id, $dto) {
            $existing = $this->customerRepo->findById($id);
            if (!$existing) {
                throw new \InvalidArgumentException("Data pelanggan dengan ID #{$id} tidak ditemukan.");
            }

            if ($this->customerRepo->isCodeTaken($dto->kodePelanggan, $id)) {
                throw new \InvalidArgumentException("Kode pelanggan '{$dto->kodePelanggan}' sudah digunakan oleh pihak lain.");
            }

            $updated = $this->customerRepo->update($id, $dto->toArray());

            if (!$updated) {
                throw new \RuntimeException("Gagal memperbarui data pelanggan #{$id}.");
            }

            $diff = ActivityLogService::buildDiff($existing, $dto->toArray());
            $diffText = !empty($diff) ? 'Bidang diubah: ' . implode(', ', array_keys($diff)) : 'Tanpa modifikasi nilai';

            $this->logActivity(
                'CUSTOMER',
                'UPDATE',
                "Memperbarui informasi pelanggan: '{$dto->namaPelanggan}' ({$dto->kodePelanggan}). {$diffText}",
                (string)$id,
                $dto->namaPelanggan,
                $diff
            );

            log_message('info', "[CustomerService::updateCustomer] Pelanggan #{$id} '{$dto->namaPelanggan}' berhasil diperbarui.");

            return true;
        });
    }

    /**
     * Menghapus data pelanggan dengan proteksi relasi transaksi keluar
     */
    public function deleteCustomer(int $id): bool
    {
        return $this->executeInTransaction(function () use ($id) {
            $existing = $this->customerRepo->findById($id);
            if (!$existing) {
                throw new \InvalidArgumentException("Data pelanggan tidak ditemukan.");
            }

            // Proteksi integritas relasi: Tolak hapus jika memiliki riwayat mutasi barang keluar
            $movementCount = $this->customerRepo->countMovementsByCustomer($id);
            if ($movementCount > 0) {
                throw new \InvalidArgumentException("Pelanggan '{$existing['nama_pelanggan']}' tidak dapat dihapus karena memiliki riwayat {$movementCount} transaksi barang keluar aktif.");
            }

            $deleted = $this->customerRepo->delete($id);

            if (!$deleted) {
                throw new \RuntimeException("Gagal menghapus pelanggan #{$id}.");
            }

            $this->logActivity(
                'CUSTOMER',
                'DELETE',
                "Menghapus data pelanggan: '{$existing['nama_pelanggan']}' ({$existing['kode_pelanggan']})",
                (string)$id,
                $existing['nama_pelanggan'],
                $existing
            );

            log_message('info', "[CustomerService::deleteCustomer] Pelanggan '{$existing['nama_pelanggan']}' (ID #{$id}) berhasil dihapus.");

            return true;
        });
    }
}
