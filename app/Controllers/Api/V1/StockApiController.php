<?php

declare(strict_types=1);

namespace App\Controllers\Api\V1;

use App\Controllers\Api\BaseApiController;
use App\DTOs\StockMovementDTO;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\ResourceNotFoundException;
use App\Services\StockService;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * REST API Controller untuk transaksi mutasi stok (v1).
 * Digunakan oleh mobile scanner app di lapangan untuk posting stock in/out instan.
 */
class StockApiController extends BaseApiController
{
    protected StockService $stockService;

    public function __construct(?StockService $stockService = null)
    {
        $this->stockService = $stockService ?? new StockService();
    }

    /**
     * Mencatat transaksi mutasi stok baru via payload JSON.
     * POST /api/v1/stock/movement
     */
    public function createMovement(): ResponseInterface
    {
        // Ambil data JSON atau Form data
        $json = $this->request->getJSON(true) ?? $this->request->getPost();

        if (empty($json) || empty($json['product_id']) || empty($json['tipe']) || !isset($json['jumlah'])) {
            return $this->respondError(
                'Data transaksi tidak lengkap. Kolom product_id, tipe, dan jumlah wajib diisi.',
                ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        // Default user ID jika via API tanpa sesi: user_id sesi aktif atau fallback sistem (1)
        $userId = (int)(session()->get('user_id') ?? $json['user_id'] ?? 1);

        try {
            $dto = StockMovementDTO::fromArray([
                'product_id'  => (int)$json['product_id'],
                'user_id'     => $userId,
                'supplier_id' => !empty($json['supplier_id']) ? (int)$json['supplier_id'] : null,
                'customer_id' => !empty($json['customer_id']) ? (int)$json['customer_id'] : null,
                'tipe'        => strtoupper(trim((string)$json['tipe'])),
                'jumlah'      => (int)$json['jumlah'],
                'keterangan'  => trim((string)($json['keterangan'] ?? 'Mutasi tercatat via REST API Scanner')),
            ]);

            $result = $this->stockService->recordMovement($dto);

            return $this->respondSuccess([
                'product_id'   => $dto->productId,
                'tipe'         => $result['tipe'],
                'jumlah'       => $result['jumlah'],
                'stok_sebelum' => $result['stok_sebelum'],
                'stok_sesudah' => $result['stok_sesudah'],
            ], "Mutasi {$result['tipe']} sebesar {$result['jumlah']} unit berhasil dicatatkan.", ResponseInterface::HTTP_CREATED);

        } catch (InsufficientStockException $e) {
            return $this->respondError($e->getMessage(), $e->getStatusCode());
        } catch (ResourceNotFoundException $e) {
            return $this->respondError($e->getMessage(), $e->getStatusCode());
        } catch (\InvalidArgumentException $e) {
            return $this->respondError($e->getMessage(), ResponseInterface::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            log_message('error', '[StockApiController::createMovement] Error: ' . $e->getMessage());
            return $this->respondError('Gagal memproses mutasi stok: ' . $e->getMessage(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
