<?php

declare(strict_types=1);

namespace App\Exceptions;

/**
 * Dilempar saat produk, SKU, kategori, atau entitas database tidak ditemukan.
 * HTTP 404 Not Found.
 */
class ResourceNotFoundException extends AppException
{
    protected int $statusCode = 404;

    public function __construct(string $message = 'Data yang diminta tidak ditemukan di dalam sistem.')
    {
        parent::__construct($message, 404);
    }
}
