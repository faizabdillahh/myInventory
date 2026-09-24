<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'supplier_id',
        'kode_produk',
        'nama_produk',
        'kategori',
        'harga',
        'stok',
        'stok_minimum',
        'deskripsi'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation Rules
    protected $validationRules = [
        'id'           => 'permit_empty|integer',
        'user_id'      => 'required|is_not_unique[users.id]',
        'supplier_id'  => 'permit_empty|is_not_unique[suppliers.id]',
        'kode_produk'  => 'required|min_length[2]|max_length[30]|is_unique[products.kode_produk,id,{id}]',
        'nama_produk'  => 'required|min_length[3]|max_length[150]',
        'kategori'     => 'required|min_length[2]|max_length[50]',
        'harga'        => 'required|numeric|greater_than_equal_to[0]',
        'stok'         => 'required|integer|greater_than_equal_to[0]',
        'stok_minimum' => 'permit_empty|integer|greater_than_equal_to[0]',
        'deskripsi'    => 'permit_empty',
    ];

    protected $validationMessages = [
        'kode_produk' => [
            'required'  => 'Kode produk (SKU) wajib diisi.',
            'is_unique' => 'Kode produk (SKU) ini sudah digunakan pada produk lain.',
        ],
        'nama_produk' => [
            'required'   => 'Nama produk wajib diisi.',
            'min_length' => 'Nama produk minimal 3 karakter.',
            'max_length' => 'Nama produk maksimal 150 karakter.',
        ],
        'kategori' => [
            'required' => 'Kategori produk wajib diisi atau dipilih.',
        ],
        'harga' => [
            'required'              => 'Harga produk wajib diisi.',
            'numeric'               => 'Harga harus berupa angka nominal yang valid.',
            'greater_than_equal_to' => 'Harga tidak boleh bernilai negatif.',
        ],
        'stok' => [
            'required'              => 'Jumlah stok fisik wajib diisi.',
            'integer'               => 'Stok harus berupa bilangan bulat.',
            'greater_than_equal_to' => 'Stok tidak boleh bernilai negatif.',
        ],
        'stok_minimum' => [
            'integer'               => 'Batas stok minimum harus berupa bilangan bulat.',
            'greater_than_equal_to' => 'Batas stok minimum tidak boleh bernilai negatif.',
        ],
    ];

    /**
     * Mengambil daftar produk beserta pembuat & supplier dengan filter pencarian, kategori, dan status stok
     */
    public function getProductsWithUser(string $search = '', string $category = '', string $status = ''): array
    {
        $builder = $this->select('products.*, users.nama_lengkap AS pembuat, suppliers.nama_supplier')
                        ->join('users', 'products.user_id = users.id', 'left')
                        ->join('suppliers', 'products.supplier_id = suppliers.id', 'left');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('products.kode_produk', $search)
                    ->orLike('products.nama_produk', $search)
                    ->orLike('products.kategori', $search)
                    ->orLike('suppliers.nama_supplier', $search)
                    ->groupEnd();
        }

        if (!empty($category)) {
            $builder->where('products.kategori', $category);
        }

        if ($status === 'low_stock') {
            $builder->where('products.stok > 0')
                    ->where('products.stok <= products.stok_minimum');
        } elseif ($status === 'out_of_stock') {
            $builder->where('products.stok <= 0');
        } elseif ($status === 'available') {
            $builder->where('products.stok > products.stok_minimum');
        }

        return $builder->orderBy('products.id', 'DESC')->findAll();
    }

    /**
     * Menghitung statistik ringkasan agregasi inventaris untuk KPI dashboard
     */
    public function getSummaryStats(): array
    {
        $sql = "
            SELECT 
                COUNT(*) AS total_products,
                COALESCE(SUM(stok), 0) AS total_stock,
                COALESCE(SUM(CASE WHEN stok <= 0 THEN 1 ELSE 0 END), 0) AS out_of_stock,
                COALESCE(SUM(CASE WHEN stok > 0 AND stok <= stok_minimum THEN 1 ELSE 0 END), 0) AS low_stock,
                COALESCE(SUM(harga * stok), 0) AS total_asset
            FROM products
        ";

        $result = $this->db->query($sql)->getRowArray();

        return [
            'total_products' => (int)($result['total_products'] ?? 0),
            'total_stock'    => (int)($result['total_stock'] ?? 0),
            'out_of_stock'   => (int)($result['out_of_stock'] ?? 0),
            'low_stock'      => (int)($result['low_stock'] ?? 0),
            'total_asset'    => (float)($result['total_asset'] ?? 0),
        ];
    }
}
