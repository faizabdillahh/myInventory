<?php

namespace App\Repositories\Contracts;

/**
 * Interface Kontrak Data Access Layer untuk Mutasi Stok
 */
interface StockMovementRepositoryInterface
{
    /**
     * Mengambil riwayat mutasi stok lengkap dengan filter tipe dan pencarian
     */
    public function getRecentMovements(?string $tipe = null, ?string $search = '', int $limit = 50): array;

    /**
     * Mengambil riwayat mutasi stok untuk satu produk tertentu
     */
    public function getByProductId(int $productId, int $limit = 20): array;

    /**
     * Mencatat baris transaksi mutasi stok baru
     */
    public function create(array $data): int|string|bool;
}
