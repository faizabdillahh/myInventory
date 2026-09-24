<?php

namespace App\Repositories;

use App\Models\SupplierModel;
use App\Repositories\Contracts\SupplierRepositoryInterface;

/**
 * Implementasi Data Access Layer untuk Entitas Supplier
 */
class SupplierRepository implements SupplierRepositoryInterface
{
    protected SupplierModel $model;

    public function __construct(?SupplierModel $model = null)
    {
        $this->model = $model ?? new SupplierModel();
    }

    public function getAll(string $search = ''): array
    {
        return $this->model->getSuppliersWithProductCount($search);
    }

    public function getForDropdown(): array
    {
        return $this->model->select('id, kode_supplier, nama_supplier')
                           ->orderBy('nama_supplier', 'ASC')
                           ->findAll();
    }

    public function findById(int $id): ?array
    {
        $supplier = $this->model->find($id);
        return $supplier ?: null;
    }

    public function isCodeTaken(string $code, ?int $excludeId = null): bool
    {
        $builder = $this->model->where('kode_supplier', strtoupper(trim($code)));
        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
    }

    public function countProductsBySupplier(int $supplierId): int
    {
        return db_connect()->table('products')
                           ->where('supplier_id', $supplierId)
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
