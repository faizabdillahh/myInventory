<?php

namespace App\Repositories\Contracts;

use App\DTOs\ReportFilterDTO;

/**
 * Kontrak Data Access Layer untuk Laporan Inventaris
 */
interface ReportRepositoryInterface
{
    /**
     * Ringkasan metrik finansial valuasi aset inventaris
     */
    public function getValuationSummary(): array;

    /**
     * Agregasi valuasi aset berdasarkan kategori produk
     */
    public function getValuationByCategory(): array;

    /**
     * Agregasi valuasi aset berdasarkan pemasok / vendor
     */
    public function getValuationBySupplier(): array;

    /**
     * Rincian valuasi per item produk
     */
    public function getDetailedValuation(?string $kategori = null, ?int $supplierId = null): array;

    /**
     * Mengambil daftar mutasi stok dalam rentang tanggal dan kriteria filter
     */
    public function getPeriodicMovements(ReportFilterDTO $filter): array;

    /**
     * Menghitung total volume mutasi masuk, keluar, dan penyesuaian dalam periode
     */
    public function getPeriodicMovementStats(ReportFilterDTO $filter): array;

    /**
     * Mengambil daftar produk perlu restock beserta kalkulasi kebutuhan pemesanan
     */
    public function getRestockProcurementReport(?int $supplierId = null): array;
}
