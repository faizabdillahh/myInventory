<?php

namespace App\Models;

use CodeIgniter\Model;

class StockMovementModel extends Model
{
    protected $table            = 'stock_movements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'user_id',
        'supplier_id',
        'customer_id',
        'tipe',
        'jumlah',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = false; // created_at manual saat transaksi mutasi

    /**
     * Mengambil riwayat mutasi stok lengkap dengan data produk, supplier, customer, dan nama pengguna
     */
    public function getMovementsWithDetails(?string $tipe = null, ?string $search = '', int $limit = 50): array
    {
        $builder = $this->select('stock_movements.*, products.kode_produk, products.nama_produk, products.kategori, users.nama_lengkap AS operator, suppliers.nama_supplier, customers.nama_pelanggan')
                        ->join('products', 'products.id = stock_movements.product_id', 'left')
                        ->join('users', 'users.id = stock_movements.user_id', 'left')
                        ->join('suppliers', 'suppliers.id = stock_movements.supplier_id', 'left')
                        ->join('customers', 'customers.id = stock_movements.customer_id', 'left');

        if (!empty($tipe) && in_array(strtoupper($tipe), ['IN', 'OUT', 'ADJUSTMENT'], true)) {
            $builder->where('stock_movements.tipe', strtoupper($tipe));
        }

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('products.kode_produk', $search)
                    ->orLike('products.nama_produk', $search)
                    ->orLike('stock_movements.keterangan', $search)
                    ->orLike('suppliers.nama_supplier', $search)
                    ->orLike('customers.nama_pelanggan', $search)
                    ->groupEnd();
        }

        return $builder->orderBy('stock_movements.id', 'DESC')
                       ->findAll($limit);
    }

    /**
     * Mengambil riwayat mutasi khusus untuk 1 produk tertentu
     */
    public function getMovementsByProduct(int $productId, int $limit = 20): array
    {
        return $this->select('stock_movements.*, users.nama_lengkap AS operator')
                    ->join('users', 'users.id = stock_movements.user_id', 'left')
                    ->where('stock_movements.product_id', $productId)
                    ->orderBy('stock_movements.id', 'DESC')
                    ->findAll($limit);
    }
}
