<?php

namespace App\DTOs;

/**
 * Data Transfer Object untuk Parameter Filter Laporan Inventaris
 */
class ReportFilterDTO
{
    public function __construct(
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?string $kategori = null,
        public readonly ?int $supplierId = null,
        public readonly ?string $tipe = null,
        public readonly ?string $status = null
    ) {}

    public static function fromArray(array $params): self
    {
        $start = !empty($params['start_date']) ? trim($params['start_date']) : null;
        $end   = !empty($params['end_date']) ? trim($params['end_date']) : null;

        // Validasi format tanggal YYYY-MM-DD jika ada
        if ($start && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $start)) {
            $start = null;
        }
        if ($end && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end)) {
            $end = null;
        }

        $tipe = !empty($params['tipe']) ? strtoupper(trim($params['tipe'])) : null;
        if (!in_array($tipe, ['IN', 'OUT', 'ADJUSTMENT'], true)) {
            $tipe = null;
        }

        return new self(
            startDate: $start,
            endDate: $end,
            kategori: !empty($params['kategori']) ? trim($params['kategori']) : null,
            supplierId: !empty($params['supplier_id']) ? (int)$params['supplier_id'] : null,
            tipe: $tipe,
            status: !empty($params['status']) ? trim($params['status']) : null
        );
    }
}
