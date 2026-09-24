<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\DTOs\ReportFilterDTO;
use App\Services\CategoryService;
use App\Services\ReportService;
use App\Services\SupplierService;

/**
 * Controller Laporan & Valuasi Inventaris Web UI (Lean Presentation Layer).
 */
class Reports extends BaseController
{
    protected ReportService $reportService;
    protected CategoryService $categoryService;
    protected SupplierService $supplierService;

    public function __construct(
        ?ReportService $reportService = null,
        ?CategoryService $categoryService = null,
        ?SupplierService $supplierService = null
    ) {
        $this->reportService   = $reportService ?? new ReportService();
        $this->categoryService = $categoryService ?? new CategoryService();
        $this->supplierService = $supplierService ?? new SupplierService();
    }

    /**
     * Halaman Utama: Laporan Valuasi Aset Inventaris
     */
    public function index()
    {
        $kategori   = trim($this->request->getGet('kategori') ?? '');
        $supplierId = !empty($this->request->getGet('supplier_id')) ? (int)$this->request->getGet('supplier_id') : null;

        $data = $this->reportService->getValuationDashboardData(
            !empty($kategori) ? $kategori : null,
            $supplierId
        );

        return view('reports/valuation', [
            'page_title'       => 'Laporan Valuasi Aset Inventaris',
            'summary'          => $data['summary'],
            'byCategory'       => $data['by_category'],
            'bySupplier'       => $data['by_supplier'],
            'details'          => $data['details'],
            'selectedCategory' => $kategori,
            'selectedSupplier' => $supplierId,
            'categories'       => $this->categoryService->getCategoriesForDropdown(),
            'suppliers'        => $this->supplierService->getSuppliersForDropdown(),
        ]);
    }

    /**
     * Halaman Laporan Rekapitulasi Mutasi Stok Berkala
     */
    public function movements()
    {
        $params = $this->request->getGet();

        if (empty($params['start_date'])) {
            $params['start_date'] = date('Y-m-01');
        }
        if (empty($params['end_date'])) {
            $params['end_date'] = date('Y-m-d');
        }

        $filter = ReportFilterDTO::fromArray($params);
        $data   = $this->reportService->getPeriodicMovementData($filter);

        return view('reports/movements', [
            'page_title' => 'Laporan Rekapitulasi Mutasi Stok',
            'filter'     => $filter,
            'stats'      => $data['stats'],
            'movements'  => $data['movements'],
            'categories' => $this->categoryService->getCategoriesForDropdown(),
            'suppliers'  => $this->supplierService->getSuppliersForDropdown(),
        ]);
    }

    /**
     * Halaman Laporan Kebutuhan Restock Pengadaan (Reorder Report)
     */
    public function restock()
    {
        $supplierId = !empty($this->request->getGet('supplier_id')) ? (int)$this->request->getGet('supplier_id') : null;
        $data       = $this->reportService->getRestockProcurementData($supplierId);

        return view('reports/restock', [
            'page_title'       => 'Laporan Kebutuhan Restock Pengadaan',
            'items'            => $data['items'],
            'totalSku'         => $data['total_sku'],
            'totalUnit'        => $data['total_unit_kebutuhan'],
            'totalEstimasi'    => $data['total_estimasi_biaya'],
            'selectedSupplier' => $supplierId,
            'suppliers'        => $this->supplierService->getSuppliersForDropdown(),
        ]);
    }

    /**
     * Ekspor CSV: Laporan Valuasi Aset
     */
    public function exportValuationCsv()
    {
        $kategori   = trim($this->request->getGet('kategori') ?? '');
        $supplierId = !empty($this->request->getGet('supplier_id')) ? (int)$this->request->getGet('supplier_id') : null;

        $csv = $this->reportService->generateValuationCsv(
            !empty($kategori) ? $kategori : null,
            $supplierId
        );

        $filename = 'laporan-valuasi-aset-' . date('Ymd-His') . '.csv';

        return $this->response
                    ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
                    ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                    ->setBody($csv);
    }

    /**
     * Ekspor CSV: Rekapitulasi Mutasi Stok
     */
    public function exportMovementsCsv()
    {
        $params = $this->request->getGet();
        $filter = ReportFilterDTO::fromArray($params);

        $csv      = $this->reportService->generateMovementCsv($filter);
        $filename = 'laporan-mutasi-stok-' . date('Ymd-His') . '.csv';

        return $this->response
                    ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
                    ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                    ->setBody($csv);
    }

    /**
     * Ekspor CSV: Kebutuhan Restock Pengadaan
     */
    public function exportRestockCsv()
    {
        $supplierId = !empty($this->request->getGet('supplier_id')) ? (int)$this->request->getGet('supplier_id') : null;

        $csv      = $this->reportService->generateRestockCsv($supplierId);
        $filename = 'laporan-kebutuhan-restock-' . date('Ymd-His') . '.csv';

        return $this->response
                    ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
                    ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                    ->setBody($csv);
    }
}
