<?php

namespace App\Repositories;

use App\Models\ProductModel;
use App\Repositories\Contracts\ProductRepositoryInterface;

/**
 * Implementasi Repository untuk Entitas Produk
 */
class ProductRepository implements ProductRepositoryInterface
{
    protected ProductModel $model;

    public function __construct(?ProductModel $model = null)
    {
        $this->model = $model ?? new ProductModel();
    }

    public function getAllWithUser(string $search = '', string $category = '', string $status = ''): array
    {
        return $this->model->getProductsWithUser($search, $category, $status);
    }

    public function findById(int $id, bool $forUpdate = false): ?array
    {
        if ($forUpdate) {
            $row = db_connect()->query('SELECT * FROM products WHERE id = ? FOR UPDATE', [$id])->getRowArray();
            return $row ?: null;
        }
        $product = $this->model->find($id);
        return $product ?: null;
    }

    public function findBySku(string $sku): ?array
    {
        $row = $this->model->where('kode_produk', strtoupper(trim($sku)))->first();
        return $row ?: null;
    }

    public function isSkuTaken(string $sku, ?int $excludeId = null): bool
    {
        $builder = $this->model->where('kode_produk', strtoupper(trim($sku)));
        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
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

    public function getSummaryStats(): array
    {
        return $this->model->getSummaryStats();
    }
}
