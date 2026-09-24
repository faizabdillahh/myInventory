<?php

namespace App\Repositories\Contracts;

/**
 * Interface Kontrak Data Access Layer untuk Kategori Produk
 */
interface CategoryRepositoryInterface
{
    /**
     * Mengambil seluruh kategori beserta jumlah produk yang ada di dalamnya
     */
    public function getAllWithProductCount(): array;

    /**
     * Mengambil seluruh kategori murni untuk dropdown opsi
     */
    public function getAll(): array;

    /**
     * Mencari kategori berdasarkan ID
     */
    public function findById(int $id): ?array;

    /**
     * Mencari kategori berdasarkan nama kategori
     */
    public function findByName(string $name): ?array;

    /**
     * Memeriksa apakah nama kategori sudah digunakan
     */
    public function isNameTaken(string $name, ?int $excludeId = null): bool;

    /**
     * Menambahkan kategori baru
     */
    public function create(array $data): int|string|bool;

    /**
     * Memperbarui data kategori
     */
    public function update(int $id, array $data): bool;

    /**
     * Menghapus kategori
     */
    public function delete(int $id): bool;

    /**
     * Menghitung berapa banyak produk yang sedang menggunakan kategori ini
     */
    public function countProductsUsingCategory(string $categoryName): int;
}
