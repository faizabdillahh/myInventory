<?php

namespace App\Repositories\Contracts;

/**
 * Interface Kontrak Data Access Layer untuk Entitas Supplier
 */
interface SupplierRepositoryInterface
{
    /**
     * Mengambil daftar seluruh supplier dengan hitungan jumlah produk yang dipasok
     */
    public function getAll(string $search = ''): array;

    /**
     * Mengambil daftar ringkas supplier untuk dropdown pilihan formulir
     */
    public function getForDropdown(): array;

    /**
     * Mencari satu data supplier berdasarkan ID
     */
    public function findById(int $id): ?array;

    /**
     * Memeriksa apakah kode supplier sudah digunakan oleh supplier lain
     */
    public function isCodeTaken(string $code, ?int $excludeId = null): bool;

    /**
     * Menghitung berapa produk yang ditautkan ke supplier ini
     */
    public function countProductsBySupplier(int $supplierId): int;

    /**
     * Menambahkan baris supplier baru
     */
    public function create(array $data): int|string|bool;

    /**
     * Memperbarui baris data supplier
     */
    public function update(int $id, array $data): bool;

    /**
     * Menghapus baris supplier
     */
    public function delete(int $id): bool;
}
