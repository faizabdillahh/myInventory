<?php

declare(strict_types=1);

namespace App\Controllers\Api\V1;

use App\Controllers\Api\BaseApiController;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * REST API Controller untuk entitas Produk (v1).
 * Melayani aplikasi mobile, barcode scanner, dan integrasi eksternal.
 */
class ProductApiController extends BaseApiController
{
    protected ProductRepositoryInterface $productRepo;

    public function __construct(?ProductRepositoryInterface $productRepo = null)
    {
        $this->productRepo = $productRepo ?? new ProductRepository();
    }

    /**
     * Mendapatkan daftar produk dengan filter pencarian dan kategori opsional.
     * GET /api/v1/products
     */
    public function index(): ResponseInterface
    {
        $searchQuery = trim((string)$this->request->getGet('q'));
        $category    = trim((string)$this->request->getGet('kategori'));
        $status      = trim((string)$this->request->getGet('status'));

        $products = $this->productRepo->getAllWithUser($searchQuery, $category, $status);

        return $this->respondSuccess([
            'total'    => count($products),
            'products' => $products,
        ], 'Daftar produk berhasil diambil.');
    }

    /**
     * Mendapatkan detail produk berdasarkan ID numerik.
     * GET /api/v1/products/(:num)
     */
    public function show(int $id): ResponseInterface
    {
        $product = $this->productRepo->findById($id);

        if (!$product) {
            return $this->respondError("Produk dengan ID #{$id} tidak ditemukan.", ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->respondSuccess($product, 'Detail produk berhasil ditemukan.');
    }

    /**
     * Lookup produk instan berdasarkan Kode SKU / Barcode fisik (Scanner camera/hardware).
     * GET /api/v1/products/barcode/(:segment)
     */
    public function barcode(string $sku): ResponseInterface
    {
        $decodedSku = urldecode($sku);
        $product = $this->productRepo->findBySku($decodedSku);

        if (!$product) {
            return $this->respondError("Produk dengan barcode/SKU '{$decodedSku}' tidak terdaftar di sistem.", ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->respondSuccess($product, "Produk '{$product['nama_produk']}' teridentifikasi.");
    }
}
