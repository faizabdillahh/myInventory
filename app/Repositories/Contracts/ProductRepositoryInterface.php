<?php

namespace App\Repositories\Contracts;

/**
 * Interface Kontrak Data Access Layer untuk Produk
 */
interface ProductRepositoryInterface
{
    /**
     * Mengambil daftar produk beserta nama penginput dengan filter pencarian, kategori, dan status stok
     */
    public function getAllWithUser(string $search = '', string $category = '', string $status = ''): array;

    /**
     * Mencari data produk berdasarkan ID unik (mendukung pessimistic locking jika $forUpdate = true)
     */
    public function findById(int $id, bool $forUpdate = false): ?array;

    /**
     * Mencari data produk berdasarkan Kode SKU / Barcode fisik
     */
    public function findBySku(string $sku): ?array;

    /**
     * Memeriksa apakah SKU sudah digunakan produk lain
     */
    public function isSkuTaken(string $sku, ?int $excludeId = null): bool;

    /**
     * Menambahkan baris produk baru ke database
     */
    public function create(array $data): int|string|bool;

    /**
     * Memperbarui baris data produk
     */
    public function update(int $id, array $data): bool;

    /**
     * Menghapus produk dari database
     */
    public function delete(int $id): bool;

    /**
     * Mengambil ringkasan statistik agregasi inventaris
     */
    public function getSummaryStats(): array;
}
