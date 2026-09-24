<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_pelanggan',
        'nama_pelanggan',
        'tipe',
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
        'id'             => 'permit_empty|integer',
        'kode_pelanggan' => 'required|min_length[2]|max_length[30]|is_unique[customers.kode_pelanggan,id,{id}]',
        'nama_pelanggan' => 'required|min_length[3]|max_length[150]',
        'tipe'           => 'required|in_list[BISNIS,INDIVIDUAL,DEPARTEMEN]',
        'kontak_person'  => 'permit_empty|max_length[100]',
        'telepon'        => 'permit_empty|max_length[30]',
        'email'          => 'permit_empty|valid_email|max_length[100]',
        'alamat'         => 'permit_empty',
    ];

    protected $validationMessages = [
        'kode_pelanggan' => [
            'required'  => 'Kode pelanggan / departemen wajib diisi.',
            'is_unique' => 'Kode pelanggan ini sudah terdaftar dalam sistem.',
        ],
        'nama_pelanggan' => [
            'required'   => 'Nama pelanggan / departemen wajib diisi.',
            'min_length' => 'Nama pelanggan minimal 3 karakter.',
            'max_length' => 'Nama pelanggan maksimal 150 karakter.',
        ],
        'email' => [
            'valid_email' => 'Format alamat email tidak valid.',
        ],
    ];

    /**
     * Mengambil daftar pelanggan beserta statistik transaksi barang keluar
     */
    public function getCustomersWithStats(string $search = ''): array
    {
        $builder = $this->select('customers.*, COUNT(stock_movements.id) AS total_transaksi, COALESCE(SUM(stock_movements.jumlah), 0) AS total_unit_keluar')
                        ->join('stock_movements', 'stock_movements.customer_id = customers.id AND stock_movements.tipe = "OUT"', 'left')
                        ->groupBy('customers.id');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('customers.kode_pelanggan', $search)
                    ->orLike('customers.nama_pelanggan', $search)
                    ->orLike('customers.kontak_person', $search)
                    ->orLike('customers.telepon', $search)
                    ->orLike('customers.email', $search)
                    ->groupEnd();
        }

        return $builder->orderBy('customers.id', 'DESC')->findAll();
    }
}
