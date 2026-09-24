<?php

namespace App\Services;

use App\DTOs\CategoryDTO;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;

/**
 * Service Layer untuk Entitas Kategori Produk
 * Menangani aturan domain bisnis kategori dan validasi relasi produk.
 */
class CategoryService extends BaseService
{
    protected CategoryRepositoryInterface $categoryRepo;

    public function __construct(?CategoryRepositoryInterface $categoryRepo = null)
    {
        parent::__construct();
        $this->categoryRepo = $categoryRepo ?? new CategoryRepository();
    }

    /**
     * Mengambil seluruh kategori beserta jumlah produk untuk halaman index kategori
     */
    public function getCategoriesList(): array
    {
        return $this->categoryRepo->getAllWithProductCount();
    }

    /**
     * Mengambil daftar nama kategori untuk opsi dropdown form produk
     */
    public function getCategoriesForDropdown(): array
    {
        return $this->categoryRepo->getAll();
    }

    /**
     * Mengambil satu data kategori berdasarkan ID
     */
    public function getCategory(int $id): ?array
    {
        return $this->categoryRepo->findById($id);
    }

    /**
     * Menambahkan kategori baru ke dalam sistem
     */
    public function createCategory(CategoryDTO $dto): bool
    {
        return $this->executeInTransaction(function () use ($dto) {
            // Validasi keunikan nama kategori
            if ($this->categoryRepo->isNameTaken($dto->namaKategori)) {
                throw new \InvalidArgumentException("Kategori dengan nama '{$dto->namaKategori}' sudah ada.");
            }

            $inserted = $this->categoryRepo->create($dto->toArray());

            if (!$inserted) {
                throw new \RuntimeException('Gagal menyimpan kategori baru.');
            }

            $this->logActivity(
                'CATEGORY',
                'CREATE',
                "Menambahkan kategori baru: '{$dto->namaKategori}'",
                (string)$inserted,
                $dto->namaKategori,
                $dto->toArray()
            );

            log_message('info', "[CategoryService::createCategory] Kategori baru '{$dto->namaKategori}' berhasil ditambahkan.");

            return true;
        });
    }

    /**
     * Memperbarui data kategori
     */
    public function updateCategory(int $id, CategoryDTO $dto): bool
    {
        return $this->executeInTransaction(function () use ($id, $dto) {
            $existing = $this->categoryRepo->findById($id);
            if (!$existing) {
                throw new \InvalidArgumentException("Kategori dengan ID #{$id} tidak ditemukan.");
            }

            // Validasi keunikan nama selain ID saat ini
            if ($this->categoryRepo->isNameTaken($dto->namaKategori, $id)) {
                throw new \InvalidArgumentException("Kategori dengan nama '{$dto->namaKategori}' sudah terdaftar.");
            }

            $oldName = $existing['nama_kategori'];
            $newName = $dto->namaKategori;

            $updated = $this->categoryRepo->update($id, $dto->toArray());

            if (!$updated) {
                throw new \RuntimeException("Gagal memperbarui kategori #{$id}.");
            }

            // Jika nama kategori berubah, otomatis sinkronkan nama kategori di tabel products!
            if ($oldName !== $newName) {
                $this->db->table('products')
                         ->where('kategori', $oldName)
                         ->update(['kategori' => $newName]);
            }

            $diff = ActivityLogService::buildDiff($existing, $dto->toArray());

            $this->logActivity(
                'CATEGORY',
                'UPDATE',
                "Memperbarui nama kategori dari '{$oldName}' menjadi '{$newName}'",
                (string)$id,
                $newName,
                $diff
            );

            log_message('info', "[CategoryService::updateCategory] Kategori #{$id} diubah dari '{$oldName}' menjadi '{$newName}'.");

            return true;
        });
    }

    /**
     * Menghapus kategori dari sistem
     */
    public function deleteCategory(int $id): bool
    {
        return $this->executeInTransaction(function () use ($id) {
            $existing = $this->categoryRepo->findById($id);
            if (!$existing) {
                throw new \InvalidArgumentException("Kategori tidak ditemukan.");
            }

            // Aturan Bisnis: Cegah penghapusan jika kategori masih digunakan oleh produk aktif!
            $usedCount = $this->categoryRepo->countProductsUsingCategory($existing['nama_kategori']);
            if ($usedCount > 0) {
                throw new \InvalidArgumentException("Kategori '{$existing['nama_kategori']}' tidak dapat dihapus karena masih digunakan oleh {$usedCount} produk inventaris.");
            }

            $deleted = $this->categoryRepo->delete($id);

            if (!$deleted) {
                throw new \RuntimeException("Gagal menghapus kategori #{$id}.");
            }

            $this->logActivity(
                'CATEGORY',
                'DELETE',
                "Menghapus kategori: '{$existing['nama_kategori']}'",
                (string)$id,
                $existing['nama_kategori'],
                $existing
            );

            log_message('info', "[CategoryService::deleteCategory] Kategori '{$existing['nama_kategori']}' (ID #{$id}) berhasil dihapus.");

            return true;
        });
    }
}
