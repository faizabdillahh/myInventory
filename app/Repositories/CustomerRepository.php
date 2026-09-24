<?php

namespace App\Repositories;

use App\Models\CustomerModel;
use App\Repositories\Contracts\CustomerRepositoryInterface;

/**
 * Implementasi Data Access Layer untuk Entitas Pelanggan / Departemen
 */
class CustomerRepository implements CustomerRepositoryInterface
{
    protected CustomerModel $model;

    public function __construct(?CustomerModel $model = null)
    {
        $this->model = $model ?? new CustomerModel();
    }

    public function getAll(string $search = ''): array
    {
        return $this->model->getCustomersWithStats($search);
    }

    public function getForDropdown(): array
    {
        return $this->model->select('id, kode_pelanggan, nama_pelanggan, tipe')
                           ->orderBy('nama_pelanggan', 'ASC')
                           ->findAll();
    }

    public function findById(int $id): ?array
    {
        $customer = $this->model->find($id);
        return $customer ?: null;
    }

    public function isCodeTaken(string $code, ?int $excludeId = null): bool
    {
        $builder = $this->model->where('kode_pelanggan', strtoupper(trim($code)));
        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
    }

    public function countMovementsByCustomer(int $customerId): int
    {
        return db_connect()->table('stock_movements')
                           ->where('customer_id', $customerId)
                           ->countAllResults();
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
