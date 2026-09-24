<?php

namespace App\Repositories;

use App\Models\ActivityLogModel;
use App\Repositories\Contracts\ActivityLogRepositoryInterface;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    protected ActivityLogModel $model;

    public function __construct(?ActivityLogModel $model = null)
    {
        $this->model = $model ?? new ActivityLogModel();
    }

    public function log(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    public function getLogs(?string $module = null, ?string $action = null, string $search = '', int $limit = 100, int $offset = 0): array
    {
        return $this->model->getLogs($module, $action, $search, $limit, $offset);
    }

    public function countLogs(?string $module = null, ?string $action = null, string $search = ''): int
    {
        return $this->model->countLogs($module, $action, $search);
    }

    public function getSummaryStats(): array
    {
        return $this->model->getSummaryStats();
    }
}
