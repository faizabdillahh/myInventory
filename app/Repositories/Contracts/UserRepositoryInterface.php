<?php

namespace App\Repositories\Contracts;

/**
 * Interface Kontrak Data Access Layer untuk Entitas Pengguna (User)
 */
interface UserRepositoryInterface
{
    /**
     * Mengambil daftar seluruh pengguna sistem dengan opsi pencarian kata kunci
     */
    public function getAll(string $search = ''): array;

    /**
     * Mencari satu data pengguna berdasarkan ID
     */
    public function findById(int $id): ?array;

    /**
     * Mencari pengguna berdasarkan username
     */
    public function findByUsername(string $username): ?array;

    /**
     * Mencari pengguna berdasarkan email
     */
    public function findByEmail(string $email): ?array;

    /**
     * Memeriksa apakah username sudah terdaftar
     */
    public function isUsernameTaken(string $username, ?int $excludeId = null): bool;

    /**
     * Memeriksa apakah email sudah terdaftar
     */
    public function isEmailTaken(string $email, ?int $excludeId = null): bool;

    /**
     * Menghitung jumlah pengguna berdasarkan peran (admin / staff)
     */
    public function countByRole(string $role): int;

    /**
     * Menghitung total seluruh pengguna aktif
     */
    public function countAll(): int;

    /**
     * Membuat baris pengguna baru
     */
    public function create(array $data): int|string|bool;

    /**
     * Memperbarui informasi pengguna
     */
    public function update(int $id, array $data): bool;

    /**
     * Menghapus baris akun pengguna
     */
    public function delete(int $id): bool;
}
