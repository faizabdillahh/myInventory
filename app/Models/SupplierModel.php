<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table            = 'suppliers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_supplier',
        'nama_supplier',
        'kontak_person',
        'telepon',
        'email',
        'alamat',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'id'            => 'permit_empty|integer',
        'kode_supplier' => 'required|min_length[2]|max_length[30]|is_unique[suppliers.kode_supplier,id,{id}]',
        'nama_supplier' => 'required|min_length[3]|max_length[150]',
        'kontak_person' => 'permit_empty|max_length[100]',
        'telepon'       => 'permit_empty|max_length[30]',
        'email'         => 'permit_empty|valid_email|max_length[100]',
        'alamat'        => 'permit_empty',
    ];

    protected $validationMessages = [
        'kode_supplier' => [
            'required'  => 'Kode supplier wajib diisi.',
            'is_unique' => 'Kode supplier ini sudah terdaftar dalam sistem.',
        ],
        'nama_supplier' => [
            'required'   => 'Nama supplier wajib diisi.',
            'min_length' => 'Nama supplier minimal 3 karakter.',
            'max_length' => 'Nama supplier maksimal 150 karakter.',
        ],
        'email' => [
            'valid_email' => 'Format alamat email tidak valid.',
        ],
    ];

    /**
     * Mengambil daftar supplier beserta total ragam produk yang dipasok
     */
    public function getSuppliersWithProductCount(string $search = ''): array
    {
        $builder = $this->select('suppliers.*, COUNT(products.id) AS total_produk')
                        ->join('products', 'products.supplier_id = suppliers.id', 'left')
                        ->groupBy('suppliers.id');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('suppliers.kode_supplier', $search)
                    ->orLike('suppliers.nama_supplier', $search)
                    ->orLike('suppliers.kontak_person', $search)
                    ->orLike('suppliers.telepon', $search)
                    ->orLike('suppliers.email', $search)
                    ->groupEnd();
        }

        return $builder->orderBy('suppliers.id', 'DESC')->findAll();
    }
}
