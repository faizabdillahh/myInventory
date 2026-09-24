<?php

namespace App\Repositories\Contracts;

/**
 * Kontrak Data Access Layer untuk Entitas Pelanggan / Departemen
 */
interface CustomerRepositoryInterface
{
    /**
     * Mengambil seluruh data pelanggan dengan total transaksi barang keluar
     */
    public function getAll(string $search = ''): array;

    /**
     * Mengambil daftar ringkas untuk pilihan dropdown di form
     */
    public function getForDropdown(): array;

    /**
     * Mencari data pelanggan berdasarkan ID
     */
    public function findById(int $id): ?array;

    /**
     * Memeriksa keunikan kode pelanggan
     */
    public function isCodeTaken(string $code, ?int $excludeId = null): bool;

    /**
     * Menghitung total mutasi barang keluar yang terhubung ke pelanggan
     */
    public function countMovementsByCustomer(int $customerId): int;

    /**
     * Menyimpan data pelanggan baru
     */
    public function create(array $data): int|string|bool;

    /**
     * Memperbarui data pelanggan
     */
    public function update(int $id, array $data): bool;

    /**
     * Menghapus data pelanggan
     */
    public function delete(int $id): bool;
}
