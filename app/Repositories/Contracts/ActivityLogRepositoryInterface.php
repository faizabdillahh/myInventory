<?php

namespace App\Repositories\Contracts;

interface ActivityLogRepositoryInterface
{
    public function log(array $data): bool;

    public function getLogs(?string $module = null, ?string $action = null, string $search = '', int $limit = 100, int $offset = 0): array;

    public function countLogs(?string $module = null, ?string $action = null, string $search = ''): int;

    public function getSummaryStats(): array;
}
