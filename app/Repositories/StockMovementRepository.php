<?php

namespace App\Repositories;

use App\Models\StockMovementModel;
use App\Repositories\Contracts\StockMovementRepositoryInterface;

/**
 * Implementasi Repository untuk Riwayat Mutasi Stok
 */
class StockMovementRepository implements StockMovementRepositoryInterface
{
    protected StockMovementModel $model;

    public function __construct(?StockMovementModel $model = null)
    {
        $this->model = $model ?? new StockMovementModel();
    }

    public function getRecentMovements(?string $tipe = null, ?string $search = '', int $limit = 50): array
    {
        return $this->model->getMovementsWithDetails($tipe, $search, $limit);
    }

    public function getByProductId(int $productId, int $limit = 20): array
    {
        return $this->model->getMovementsByProduct($productId, $limit);
    }

    public function create(array $data): int|string|bool
    {
        return $this->model->insert($data);
    }
}
