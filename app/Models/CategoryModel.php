<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_kategori',
        'deskripsi'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation Rules
    protected $validationRules = [
        'id'            => 'permit_empty|integer',
        'nama_kategori' => 'required|min_length[2]|max_length[100]|is_unique[categories.nama_kategori,id,{id}]',
        'deskripsi'     => 'permit_empty',
    ];

    protected $validationMessages = [
        'nama_kategori' => [
            'required'   => 'Nama kategori wajib diisi.',
            'min_length' => 'Nama kategori minimal 2 karakter.',
            'max_length' => 'Nama kategori maksimal 100 karakter.',
            'is_unique'  => 'Nama kategori ini sudah terdaftar dalam sistem.',
        ],
    ];

    /**
     * Mengambil seluruh data kategori beserta jumlah produk yang terhubung
     */
    public function getCategoriesWithProductCount(): array
    {
        return $this->select('categories.*, COUNT(products.id) AS total_produk')
                    ->join('products', 'products.kategori = categories.nama_kategori', 'left')
                    ->groupBy('categories.id')
                    ->orderBy('categories.nama_kategori', 'ASC')
                    ->findAll();
    }
}
