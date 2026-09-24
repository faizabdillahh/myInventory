<?php

namespace App\Repositories;

use App\Models\UserModel;
use App\Repositories\Contracts\UserRepositoryInterface;

/**
 * Implementasi Data Access Layer untuk Entitas Pengguna (User)
 */
class UserRepository implements UserRepositoryInterface
{
    protected UserModel $model;

    public function __construct(?UserModel $model = null)
    {
        $this->model = $model ?? new UserModel();
    }

    public function getAll(string $search = ''): array
    {
        $builder = $this->model->select('id, nama_lengkap, username, email, role, created_at, updated_at')
                               ->orderBy('id', 'DESC');

        if ($search !== '') {
            $builder->groupStart()
                    ->like('nama_lengkap', $search)
                    ->orLike('username', $search)
                    ->orLike('email', $search)
                    ->orLike('role', $search)
                    ->groupEnd();
        }

        return $builder->findAll();
    }

    public function findById(int $id): ?array
    {
        $user = $this->model->find($id);
        return $user ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        return $this->model->where('username', strtolower(trim($username)))->first();
    }

    public function findByEmail(string $email): ?array
    {
        return $this->model->where('email', strtolower(trim($email)))->first();
    }

    public function isUsernameTaken(string $username, ?int $excludeId = null): bool
    {
        $builder = $this->model->where('username', strtolower(trim($username)));
        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
    }

    public function isEmailTaken(string $email, ?int $excludeId = null): bool
    {
        $builder = $this->model->where('email', strtolower(trim($email)));
        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
    }

    public function countByRole(string $role): int
    {
        return $this->model->where('role', $role)->countAllResults();
    }

    public function countAll(): int
    {
        return $this->model->countAllResults();
    }

    public function create(array $data): int|string|bool
    {
        return $this->model->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        return $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }
}
