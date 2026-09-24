<?php

namespace App\Repositories;

use App\DTOs\ReportFilterDTO;
use App\Repositories\Contracts\ReportRepositoryInterface;
use CodeIgniter\Database\BaseConnection;

/**
 * Implementasi Data Access Layer untuk Laporan & Agregasi Finansial Inventaris
 */
class ReportRepository implements ReportRepositoryInterface
{
    protected BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? db_connect();
    }

    public function getValuationSummary(): array
    {
        $builder = $this->db->table('products');
        $builder->select('
            COUNT(id) AS total_sku,
            COALESCE(SUM(stok), 0) AS total_unit,
            COALESCE(SUM(stok * harga), 0) AS total_valuasi,
            COALESCE(AVG(harga), 0) AS rata_harga,
            COALESCE(MAX(stok * harga), 0) AS valuasi_tertinggi
        ');

        $row = $builder->get()->getRowArray();

        return [
            'total_sku'         => (int)($row['total_sku'] ?? 0),
            'total_unit'        => (int)($row['total_unit'] ?? 0),
            'total_valuasi'     => (float)($row['total_valuasi'] ?? 0),
            'rata_harga'        => (float)($row['rata_harga'] ?? 0),
            'valuasi_tertinggi' => (float)($row['valuasi_tertinggi'] ?? 0),
        ];
    }

    public function getValuationByCategory(): array
    {
        $builder = $this->db->table('products');
        $builder->select('
            kategori,
            COUNT(id) AS total_sku,
            COALESCE(SUM(stok), 0) AS total_unit,
            COALESCE(SUM(stok * harga), 0) AS total_valuasi
        ');
        $builder->groupBy('kategori');
        $builder->orderBy('total_valuasi', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function getValuationBySupplier(): array
    {
        $builder = $this->db->table('products');
        $builder->select('
            COALESCE(suppliers.nama_supplier, "Tanpa Pemasok") AS nama_supplier,
            suppliers.kode_supplier,
            COUNT(products.id) AS total_sku,
            COALESCE(SUM(products.stok), 0) AS total_unit,
            COALESCE(SUM(products.stok * products.harga), 0) AS total_valuasi
        ');
        $builder->join('suppliers', 'suppliers.id = products.supplier_id', 'left');
        $builder->groupBy('products.supplier_id, suppliers.nama_supplier, suppliers.kode_supplier');
        $builder->orderBy('total_valuasi', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function getDetailedValuation(?string $kategori = null, ?int $supplierId = null): array
    {
        $builder = $this->db->table('products');
        $builder->select('
            products.*,
            (products.stok * products.harga) AS nilai_aset,
            suppliers.nama_supplier,
            suppliers.kode_supplier
        ');
        $builder->join('suppliers', 'suppliers.id = products.supplier_id', 'left');

        if (!empty($kategori)) {
            $builder->where('products.kategori', $kategori);
        }
        if (!empty($supplierId)) {
            $builder->where('products.supplier_id', $supplierId);
        }

        $builder->orderBy('nilai_aset', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function getPeriodicMovements(ReportFilterDTO $filter): array
    {
        $builder = $this->db->table('stock_movements');
        $builder->select('
            stock_movements.*,
            products.nama_produk,
            products.kode_produk,
            products.kategori,
            users.nama_lengkap AS operator,
            suppliers.nama_supplier,
            customers.nama_pelanggan
        ');
        $builder->join('products', 'products.id = stock_movements.product_id', 'inner');
        $builder->join('users', 'users.id = stock_movements.user_id', 'left');
        $builder->join('suppliers', 'suppliers.id = stock_movements.supplier_id', 'left');
        $builder->join('customers', 'customers.id = stock_movements.customer_id', 'left');

        $this->applyMovementFilters($builder, $filter);

        $builder->orderBy('stock_movements.created_at', 'DESC');
        $builder->orderBy('stock_movements.id', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function getPeriodicMovementStats(ReportFilterDTO $filter): array
    {
        $builder = $this->db->table('stock_movements');
        $builder->select('
            COUNT(stock_movements.id) AS total_transaksi,
            COALESCE(SUM(CASE WHEN stock_movements.tipe = "IN" THEN stock_movements.jumlah ELSE 0 END), 0) AS total_in,
            COALESCE(SUM(CASE WHEN stock_movements.tipe = "OUT" THEN stock_movements.jumlah ELSE 0 END), 0) AS total_out,
            COALESCE(SUM(CASE WHEN stock_movements.tipe = "ADJUSTMENT" THEN 1 ELSE 0 END), 0) AS count_adjustment
        ');
        $builder->join('products', 'products.id = stock_movements.product_id', 'inner');

        $this->applyMovementFilters($builder, $filter);

        $row = $builder->get()->getRowArray();

        $totalIn  = (int)($row['total_in'] ?? 0);
        $totalOut = (int)($row['total_out'] ?? 0);

        return [
            'total_transaksi'  => (int)($row['total_transaksi'] ?? 0),
            'total_in'         => $totalIn,
            'total_out'        => $totalOut,
            'count_adjustment' => (int)($row['count_adjustment'] ?? 0),
            'net_delta'        => $totalIn - $totalOut,
        ];
    }

    public function getRestockProcurementReport(?int $supplierId = null): array
    {
        $builder = $this->db->table('products');
        $builder->select('
            products.*,
            suppliers.nama_supplier,
            suppliers.kode_supplier,
            suppliers.telepon,
            suppliers.email,
            GREATEST(1, (products.stok_minimum * 2) - products.stok) AS saran_reorder,
            (GREATEST(1, (products.stok_minimum * 2) - products.stok) * products.harga) AS estimasi_biaya
        ');
        $builder->join('suppliers', 'suppliers.id = products.supplier_id', 'left');
        $builder->where('products.stok <= products.stok_minimum');

        if (!empty($supplierId)) {
            $builder->where('products.supplier_id', $supplierId);
        }

        $builder->orderBy('products.stok', 'ASC');
        $builder->orderBy('estimasi_biaya', 'DESC');

        return $builder->get()->getResultArray();
    }

    private function applyMovementFilters(\CodeIgniter\Database\BaseBuilder $builder, ReportFilterDTO $filter): void
    {
        if (!empty($filter->startDate)) {
            $builder->where('stock_movements.created_at >=', $filter->startDate . ' 00:00:00');
        }
        if (!empty($filter->endDate)) {
            $builder->where('stock_movements.created_at <=', $filter->endDate . ' 23:59:59');
        }
        if (!empty($filter->tipe)) {
            $builder->where('stock_movements.tipe', $filter->tipe);
        }
        if (!empty($filter->kategori)) {
            $builder->where('products.kategori', $filter->kategori);
        }
        if (!empty($filter->supplierId)) {
            $builder->where('stock_movements.supplier_id', $filter->supplierId);
        }
    }
}
