<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table            = 'activity_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'user_name',
        'user_role',
        'module',
        'action',
        'record_id',
        'item_name',
        'description',
        'details',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $useTimestamps = false;

    /**
     * Mengambil daftar log aktivitas dengan filter dan pencarian
     */
    public function getLogs(?string $module = null, ?string $action = null, string $search = '', int $limit = 100, int $offset = 0): array
    {
        $builder = $this->builder();

        if (!empty($module) && $module !== 'ALL') {
            $builder->where('module', strtoupper($module));
        }

        if (!empty($action) && $action !== 'ALL') {
            $builder->where('action', strtoupper($action));
        }

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('user_name', $search)
                    ->orLike('item_name', $search)
                    ->orLike('description', $search)
                    ->orLike('record_id', $search)
                    ->orLike('ip_address', $search)
                    ->groupEnd();
        }

        return $builder->orderBy('created_at', 'DESC')
                       ->orderBy('id', 'DESC')
                       ->limit($limit, $offset)
                       ->get()
                       ->getResultArray();
    }

    /**
     * Menghitung total log aktivitas untuk pagination
     */
    public function countLogs(?string $module = null, ?string $action = null, string $search = ''): int
    {
        $builder = $this->builder();

        if (!empty($module) && $module !== 'ALL') {
            $builder->where('module', strtoupper($module));
        }

        if (!empty($action) && $action !== 'ALL') {
            $builder->where('action', strtoupper($action));
        }

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('user_name', $search)
                    ->orLike('item_name', $search)
                    ->orLike('description', $search)
                    ->orLike('record_id', $search)
                    ->orLike('ip_address', $search)
                    ->groupEnd();
        }

        return $builder->countAllResults();
    }

    /**
     * Mengambil statistik ringkas log audit
     */
    public function getSummaryStats(): array
    {
        $totalLogs    = $this->countAllResults();
        $totalMutasi  = $this->where('module', 'STOCK')->countAllResults();
        $totalMaster  = $this->whereIn('module', ['PRODUCT', 'SUPPLIER', 'CUSTOMER', 'CATEGORY'])->countAllResults();
        $totalUserOps = $this->whereIn('module', ['USER', 'AUTH'])->countAllResults();

        return [
            'total_logs'    => $totalLogs,
            'total_mutasi'  => $totalMutasi,
            'total_master'  => $totalMaster,
            'total_user_ops'=> $totalUserOps,
        ];
    }
}
