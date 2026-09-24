<?php

namespace App\Services;

use App\Repositories\ActivityLogRepository;
use App\Repositories\Contracts\ActivityLogRepositoryInterface;

/**
 * Service Layer untuk Activity & Audit Logging
 * Mencatat seluruh aktivitas operasional dan perubahan data master untuk audit transparansi.
 */
class ActivityLogService extends BaseService
{
    protected ActivityLogRepositoryInterface $activityRepo;

    public function __construct(?ActivityLogRepositoryInterface $activityRepo = null)
    {
        parent::__construct();
        $this->activityRepo = $activityRepo ?? new ActivityLogRepository();
    }

    /**
     * Mencatat satu entri aktivitas ke dalam audit log
     */
    public function record(
        string $module,
        string $action,
        string $description,
        ?string $recordId = null,
        ?string $itemName = null,
        ?array $details = null
    ): bool {
        try {
            $session = session();
            $userId   = $session ? $session->get('user_id') : null;
            $userName = $session && $session->get('nama_lengkap') ? $session->get('nama_lengkap') : 'Sistem / Anonim';
            $userRole = $session && $session->get('role') ? $session->get('role') : 'system';

            $request   = service('request');
            $ipAddress = $request ? $request->getIPAddress() : '127.0.0.1';
            $userAgent = $request && $request->getUserAgent() ? substr($request->getUserAgent()->getAgentString(), 0, 250) : null;

            $data = [
                'user_id'     => $userId,
                'user_name'   => $userName,
                'user_role'   => $userRole,
                'module'      => strtoupper($module),
                'action'      => strtoupper($action),
                'record_id'   => $recordId !== null ? (string)$recordId : null,
                'item_name'   => $itemName,
                'description' => $description,
                'details'     => !empty($details) ? json_encode($details, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : null,
                'ip_address'  => $ipAddress,
                'user_agent'  => $userAgent,
                'created_at'  => date('Y-m-d H:i:s'),
            ];

            return $this->activityRepo->log($data);
        } catch (\Throwable $e) {
            log_message('error', '[ActivityLogService::record] Gagal mencatat log aktivitas: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mengambil daftar log aktivitas dengan filter
     */
    public function getLogs(?string $module = null, ?string $action = null, string $search = '', int $limit = 100, int $offset = 0): array
    {
        return $this->activityRepo->getLogs($module, $action, $search, $limit, $offset);
    }

    /**
     * Menghitung total entri log
     */
    public function countLogs(?string $module = null, ?string $action = null, string $search = ''): int
    {
        return $this->activityRepo->countLogs($module, $action, $search);
    }

    /**
     * Mengambil ringkasan statistik
     */
    public function getSummaryStats(): array
    {
        return $this->activityRepo->getSummaryStats();
    }

    /**
     * Utilitas untuk menghitung perubahan field antara data lama dan data baru
     */
    public static function buildDiff(array $before, array $after, array $ignoredKeys = ['updated_at', 'created_at', 'password']): array
    {
        $changes = [];

        foreach ($after as $key => $newVal) {
            if (in_array($key, $ignoredKeys, true)) {
                continue;
            }

            $oldVal = $before[$key] ?? null;

            // Samakan tipe string untuk perbandingan adil
            if ((string)$oldVal !== (string)$newVal) {
                $changes[$key] = [
                    'sebelum' => $oldVal,
                    'sesudah' => $newVal,
                ];
            }
        }

        return $changes;
    }
}
