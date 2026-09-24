<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\DTOs\StockMovementDTO;
use App\Exceptions\AppException;
use App\Services\ActivityLogService;
use App\Services\CustomerService;
use App\Services\ProductService;
use App\Services\StockService;
use App\Services\SupplierService;

/**
 * Controller Mutasi Stok Web UI (Presentation Layer - Lean Controller).
 * Menangani HTTP request untuk pencatatan dan pelaporan riwayat mutasi stok.
 */
class Stock extends BaseController
{
    protected StockService $stockService;
    protected ProductService $productService;
    protected SupplierService $supplierService;
    protected CustomerService $customerService;
    protected ActivityLogService $activityLogService;

    public function __construct(
        ?StockService $stockService = null,
        ?ProductService $productService = null,
        ?SupplierService $supplierService = null,
        ?CustomerService $customerService = null,
        ?ActivityLogService $activityLogService = null
    ) {
        $this->stockService       = $stockService ?? new StockService();
        $this->productService     = $productService ?? new ProductService();
        $this->supplierService    = $supplierService ?? new SupplierService();
        $this->customerService    = $customerService ?? new CustomerService();
        $this->activityLogService = $activityLogService ?? new ActivityLogService();
    }

    /**
     * Halaman Formulir Pencatatan Barang Masuk (+)
     */
    public function stockIn()
    {
        return $this->renderMovementForm('IN');
    }

    /**
     * Halaman Formulir Pencatatan Barang Keluar (-)
     */
    public function stockOut()
    {
        return $this->renderMovementForm('OUT');
    }

    /**
     * Helper render formulir transaksi mutasi stok
     */
    protected function renderMovementForm(string $defaultTipe)
    {
        $dashboardData     = $this->productService->getDashboardData();
        $selectedProductId = (int)($this->request->getGet('product_id') ?? 0);

        return view('stock/movement_form', [
            'page_title'        => ($defaultTipe === 'IN') ? 'Pencatatan Barang Masuk' : 'Pencatatan Barang Keluar',
            'defaultTipe'       => $defaultTipe,
            'products'          => $dashboardData['products'],
            'suppliers'         => $this->supplierService->getSuppliersForDropdown(),
            'customers'         => $this->customerService->getCustomersForDropdown(),
            'selectedProductId' => $selectedProductId,
        ]);
    }

    /**
     * Halaman Buku Riwayat Transaksi Mutasi Stok & Log Audit Aktivitas
     */
    public function history()
    {
        $tipe    = trim((string)$this->request->getGet('tipe'));
        $search  = trim((string)$this->request->getGet('q'));
        $tab     = trim((string)$this->request->getGet('tab')) ?: 'all';
        $module  = trim((string)$this->request->getGet('module'));
        $action  = trim((string)$this->request->getGet('action'));

        $movements = $this->stockService->getMovementHistory(
            !empty($tipe) ? $tipe : null,
            $search
        );

        $activityLogs = $this->activityLogService->getLogs(
            !empty($module) ? $module : null,
            !empty($action) ? $action : null,
            $search,
            150
        );

        $summaryStats = $this->activityLogService->getSummaryStats();

        return view('stock/history', [
            'page_title'     => 'Buku Riwayat Mutasi Stok & Log Audit Aktivitas',
            'movements'      => $movements,
            'activityLogs'   => $activityLogs,
            'summaryStats'   => $summaryStats,
            'selectedTipe'   => $tipe,
            'selectedTab'    => $tab,
            'selectedModule' => $module,
            'selectedAction' => $action,
            'searchQuery'    => $search,
        ]);
    }

    /**
     * Memproses Pengiriman Transaksi Mutasi Stok Baru
     */
    public function createMovement()
    {
        $isAdjustment = (strtoupper(trim($this->request->getPost('tipe') ?? '')) === 'ADJUSTMENT');
        $rules = [
            'product_id'  => 'required|is_not_unique[products.id]',
            'supplier_id' => 'permit_empty|is_not_unique[suppliers.id]',
            'customer_id' => 'permit_empty|is_not_unique[customers.id]',
            'tipe'        => 'required|in_list[IN,OUT,ADJUSTMENT]',
            'jumlah'      => $isAdjustment ? 'required|integer|greater_than_equal_to[0]' : 'required|integer|greater_than[0]',
            'keterangan'  => 'permit_empty|max_length[255]',
        ];

        $messages = [
            'product_id' => [
                'required'      => 'ID Produk wajib disertakan.',
                'is_not_unique' => 'Produk yang dipilih tidak ditemukan dalam sistem.',
            ],
            'tipe' => [
                'required' => 'Pilih jenis mutasi (Barang Masuk, Barang Keluar, atau Penyesuaian).',
                'in_list'  => 'Jenis mutasi tidak valid.',
            ],
            'jumlah' => [
                'required'              => 'Jumlah unit mutasi wajib diisi.',
                'integer'               => 'Jumlah harus berupa bilangan bulat.',
                'greater_than'          => 'Jumlah mutasi harus lebih besar dari 0 unit.',
                'greater_than_equal_to' => 'Hasil penyesuaian fisik (opname) tidak boleh bernilai negatif.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->to(base_url('products'))
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        try {
            $dto = StockMovementDTO::fromArray(
                $this->request->getPost(),
                (int)session()->get('user_id')
            );

            $result = $this->stockService->recordMovement($dto);
            $namaProduk = $result['product']['nama_produk'];

            $labelTipe = match ($result['tipe']) {
                'IN'         => 'Barang Masuk (+)',
                'OUT'        => 'Barang Keluar (-)',
                'ADJUSTMENT' => 'Penyesuaian Stok',
                default      => $result['tipe'],
            };

            $pesan = "Mutasi {$labelTipe} sebesar {$result['jumlah']} unit pada '{$namaProduk}' berhasil dicatat! (Stok saat ini: {$result['stok_sesudah']} unit)";

            return redirect()->to(base_url('products'))->with('success', $pesan);
        } catch (AppException $e) {
            return redirect()->to(base_url('products'))->withInput()->with('error', $e->getMessage());
        } catch (\InvalidArgumentException $e) {
            return redirect()->to(base_url('products'))->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[Stock::createMovement] ' . $e->getMessage());
            return redirect()->to(base_url('products'))->withInput()->with('error', 'Terjadi kesalahan sistem saat memproses transaksi mutasi.');
        }
    }
}
