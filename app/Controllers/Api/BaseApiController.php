<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Controller dasar untuk semua endpoint RESTful API InventarisPro.
 * Mengimplementasikan ResponseTrait CI4 untuk standardisasi respons JSON.
 */
abstract class BaseApiController extends BaseController
{
    use ResponseTrait;

    /**
     * Mengembalikan respons sukses terstandarisasi (JSend/Enterprise Envelope)
     *
     * @param mixed $data Data muatan respons
     * @param string $message Pesan informatif untuk klien API
     * @param int $statusCode HTTP status code (default: 200 OK)
     * @return ResponseInterface
     */
    protected function respondSuccess(mixed $data = null, string $message = 'Permintaan berhasil diproses.', int $statusCode = ResponseInterface::HTTP_OK): ResponseInterface
    {
        return $this->response->setStatusCode($statusCode)->setJSON([
            'success'   => true,
            'message'   => $message,
            'data'      => $data,
            'timestamp' => date('c'),
        ]);
    }

    /**
     * Mengembalikan respons kegagalan terstandarisasi
     *
     * @param string $message Pesan kegagalan
     * @param int $statusCode HTTP status code (misal: 400, 404, 422, 500)
     * @param mixed $errors Detail error / validasi jika ada
     * @return ResponseInterface
     */
    protected function respondError(string $message = 'Terjadi kesalahan pada permintaan Anda.', int $statusCode = ResponseInterface::HTTP_BAD_REQUEST, mixed $errors = null): ResponseInterface
    {
        $payload = [
            'success'   => false,
            'message'   => $message,
            'timestamp' => date('c'),
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return $this->response->setStatusCode($statusCode)->setJSON($payload);
    }
}
