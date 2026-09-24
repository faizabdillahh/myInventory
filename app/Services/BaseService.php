<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use Config\Database;

/**
 * Kelas Dasar Service Layer
 * Menyediakan utilitas koneksi database dan pembungkus transaksi atomik (ACID).
 */
abstract class BaseService
{
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Menjalankan operasi bisnis di dalam transaksi database atomik
     *
     * @template T
     * @param callable(): T $operation Fungsi atau closure yang akan dieksekusi dalam transaksi
     * @return T Hasil kembalian dari closure operasi
     * @throws \Throwable Jika operasi gagal, transaksi di-rollback dan exception dilemparkan ulang
     */
    protected function executeInTransaction(callable $operation)
    {
        $this->db->transBegin();

        try {
            $result = $operation();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                throw new \RuntimeException('Database transaction failed status check.');
            }

            $this->db->transCommit();
            return $result;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', '[BaseService::executeInTransaction] ' . $e->getMessage(), [
                'exception' => $e
            ]);
            throw $e;
        }
    }

    /**
     * Helper terpusat untuk mencatat audit trail aktivitas pengguna
     */
    protected function logActivity(
        string $module,
        string $action,
        string $description,
        ?string $recordId = null,
        ?string $itemName = null,
        ?array $details = null
    ): void {
        try {
            (new ActivityLogService())->record($module, $action, $description, $recordId, $itemName, $details);
        } catch (\Throwable $e) {
            log_message('warning', '[BaseService::logActivity] Gagal mencatat log aktivitas: ' . $e->getMessage());
        }
    }
}
