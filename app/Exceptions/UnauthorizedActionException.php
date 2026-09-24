<?php

declare(strict_types=1);

namespace App\Exceptions;

/**
 * Dilempar saat aksi dilarang oleh aturan integritas bisnis atau batasan wewenang.
 * HTTP 403 Forbidden.
 */
class UnauthorizedActionException extends AppException
{
    protected int $statusCode = 403;

    public function __construct(string $message = 'Anda tidak memiliki hak wewenang untuk mengeksekusi aksi ini.')
    {
        parent::__construct($message, 403);
    }
}
