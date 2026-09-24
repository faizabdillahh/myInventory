<?php

declare(strict_types=1);

namespace App\Exceptions;

/**
 * Dilempar saat permintaan barang keluar melebihi stok fisik gudang.
 * HTTP 422 Unprocessable Entity.
 */
class InsufficientStockException extends AppException
{
    protected int $statusCode = 422;

    public function __construct(string $message = 'Kuantitas barang keluar melebihi jumlah stok fisik yang tersedia.')
    {
        parent::__construct($message, 422);
    }
}
