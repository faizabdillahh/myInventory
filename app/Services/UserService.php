<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;

/**
 * Service Layer untuk Entitas Pengguna (User Management & RBAC)
 * Menangani aturan bisnis pendaftaran, pembaruan profil, dan kontrol akses akun.
 */
class UserService extends BaseService
{
    protected UserRepositoryInterface $userRepo;

    public function __construct(?UserRepositoryInterface $userRepo = null)
    {
        parent::__construct();
        $this->userRepo = $userRepo ?? new UserRepository();
    }

    /**
     * Mengambil daftar seluruh pengguna dengan filter pencarian
     */
    public function getAllUsers(string $search = ''): array
    {
        return $this->userRepo->getAll($search);
    }

    /**
     * Mengambil data pengguna berdasarkan ID
     */
    public function getUserById(int $id): ?array
    {
        return $this->userRepo->findById($id);
    }

    /**
     * Mengambil ringkasan metrik statistik pengguna (total, admin, staff)
     */
    public function getUserStatistics(): array
    {
        return [
            'total_users' => $this->userRepo->countAll(),
            'total_admin' => $this->userRepo->countByRole('admin'),
            'total_staff' => $this->userRepo->countByRole('staff'),
        ];
    }

    /**
     * Mendaftarkan akun pengguna baru
     *
     * @return array{success: bool, message: string, id: ?int}
     */
    public function createUser(UserDTO $dto): array
    {
        // 1. Validasi nama lengkap
        if (mb_strlen($dto->namaLengkap) < 3) {
            return [
                'success' => false,
                'message' => 'Nama lengkap minimal harus terdiri dari 3 karakter.',
                'id'      => null
            ];
        }

        // 2. Validasi keunikan username
        if ($this->userRepo->isUsernameTaken($dto->username)) {
            return [
                'success' => false,
                'message' => 'Username "' . $dto->username . '" sudah digunakan oleh pengguna lain.',
                'id'      => null
            ];
        }

        // 3. Validasi keunikan email
        if ($this->userRepo->isEmailTaken($dto->email)) {
            return [
                'success' => false,
                'message' => 'Alamat email "' . $dto->email . '" sudah terdaftar dalam sistem.',
                'id'      => null
            ];
        }

        // 4. Validasi password wajib ada saat pembuatan akun baru
        if (empty($dto->password) || mb_strlen($dto->password) < 6) {
            return [
                'success' => false,
                'message' => 'Password wajib diisi minimal 6 karakter untuk akun baru.',
                'id'      => null
            ];
        }

        return $this->executeInTransaction(function () use ($dto) {
            $insertId = $this->userRepo->create($dto->toArray());

            if (!$insertId) {
                return [
                    'success' => false,
                    'message' => 'Gagal menyimpan data pengguna ke dalam basis data.',
                    'id'      => null
                ];
            }

            $this->logActivity(
                'USER',
                'CREATE',
                "Membuat akun pengguna baru: '{$dto->namaLengkap}' (@{$dto->username}) dengan peran " . strtoupper($dto->role),
                (string)$insertId,
                $dto->username,
                [
                    'nama_lengkap' => $dto->namaLengkap,
                    'username'     => $dto->username,
                    'email'        => $dto->email,
                    'role'         => $dto->role,
                ]
            );

            return [
                'success' => true,
                'message' => 'Akun pengguna (' . strtoupper($dto->role) . ') berhasil dibuat.',
                'id'      => (int) $insertId
            ];
        });
    }

    /**
     * Memperbarui profil akun pengguna
     *
     * @return array{success: bool, message: string}
     */
    public function updateUser(int $id, UserDTO $dto, int $currentUserId): array
    {
        $existing = $this->userRepo->findById($id);
        if (!$existing) {
            return [
                'success' => false,
                'message' => 'Data pengguna tidak ditemukan.'
            ];
        }

        // Validasi keunikan username jika diubah
        if ($this->userRepo->isUsernameTaken($dto->username, $id)) {
            return [
                'success' => false,
                'message' => 'Username "' . $dto->username . '" sudah digunakan oleh pengguna lain.'
            ];
        }

        // Validasi keunikan email jika diubah
        if ($this->userRepo->isEmailTaken($dto->email, $id)) {
            return [
                'success' => false,
                'message' => 'Alamat email "' . $dto->email . '" sudah terdaftar.'
            ];
        }

        // Proteksi anti-lockout: Jika akun sendiri yang diedit, jangan izinkan menurunkan role sendiri jika dia admin terakhir
        if ($id === $currentUserId && $existing['role'] === 'admin' && $dto->role !== 'admin') {
            $adminCount = $this->userRepo->countByRole('admin');
            if ($adminCount <= 1) {
                return [
                    'success' => false,
                    'message' => 'Anda adalah satu-satunya Administrator aktif. Tidak dapat mengubah peran menjadi Staff.'
                ];
            }
        }

        return $this->executeInTransaction(function () use ($id, $dto, $existing) {
            $updateData = $dto->toArray();
            $updated = $this->userRepo->update($id, $updateData);

            if (!$updated) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada perubahan data yang disimpan.'
                ];
            }

            $diff = ActivityLogService::buildDiff($existing, $updateData);
            $diffText = !empty($diff) ? 'Bidang diubah: ' . implode(', ', array_keys($diff)) : 'Perubahan tersimpan';

            $this->logActivity(
                'USER',
                'UPDATE',
                "Memperbarui akun pengguna: '{$dto->namaLengkap}' (@{$dto->username}). {$diffText}",
                (string)$id,
                $dto->username,
                $diff
            );

            return [
                'success' => true,
                'message' => 'Data pengguna berhasil diperbarui.'
            ];
        });
    }

    /**
     * Menghapus akun pengguna dari sistem
     *
     * @return array{success: bool, message: string}
     */
    public function deleteUser(int $id, int $currentUserId): array
    {
        // 1. Proteksi Anti-Self-Delete (Admin tidak boleh menghapus akun dirinya sendiri)
        if ($id === $currentUserId) {
            return [
                'success' => false,
                'message' => 'Aksi ditolak: Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.'
            ];
        }

        $user = $this->userRepo->findById($id);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.'
            ];
        }

        // 2. Proteksi Admin Terakhir
        if ($user['role'] === 'admin') {
            $totalAdmin = $this->userRepo->countByRole('admin');
            if ($totalAdmin <= 1) {
                return [
                    'success' => false,
                    'message' => 'Aksi ditolak: Tidak dapat menghapus Administrator terakhir di sistem.'
                ];
            }
        }

        return $this->executeInTransaction(function () use ($id, $user) {
            $deleted = $this->userRepo->delete($id);

            if (!$deleted) {
                return [
                    'success' => false,
                    'message' => 'Gagal menghapus pengguna dari basis data.'
                ];
            }

            $this->logActivity(
                'USER',
                'DELETE',
                "Menghapus akun pengguna: '{$user['nama_lengkap']}' (@{$user['username']})",
                (string)$id,
                $user['username'],
                $user
            );

            return [
                'success' => true,
                'message' => 'Akun pengguna "' . esc($user['username']) . '" berhasil dihapus.'
            ];
        });
    }
}
