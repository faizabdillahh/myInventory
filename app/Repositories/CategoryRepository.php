<?php

namespace App\Repositories;

use App\Models\CategoryModel;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Config\Database;

/**
 * Implementasi Repository untuk Kategori Produk
 */
class CategoryRepository implements CategoryRepositoryInterface
{
    protected CategoryModel $model;

    public function __construct(?CategoryModel $model = null)
    {
        $this->model = $model ?? new CategoryModel();
    }

    public function getAllWithProductCount(): array
    {
        return $this->model->getCategoriesWithProductCount();
    }

    public function getAll(): array
    {
        return $this->model->orderBy('nama_kategori', 'ASC')->findAll();
    }

    public function findById(int $id): ?array
    {
        $category = $this->model->find($id);
        return $category ?: null;
    }

    public function findByName(string $name): ?array
    {
        return $this->model->where('nama_kategori', trim($name))->first();
    }

    public function isNameTaken(string $name, ?int $excludeId = null): bool
    {
        $builder = $this->model->where('nama_kategori', trim($name));
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

    public function countProductsUsingCategory(string $categoryName): int
    {
        $db = Database::connect();
        return $db->table('products')->where('kategori', trim($categoryName))->countAllResults();
    }
}
