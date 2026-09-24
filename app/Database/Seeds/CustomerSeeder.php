<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_pelanggan' => 'CST-001',
                'nama_pelanggan' => 'PT Mitra Distribusi Ritel',
                'tipe'           => 'BISNIS',
                'kontak_person'  => 'Hendro Wijaya',
                'telepon'        => '081211223344',
                'email'          => 'hendro@mitradistribusi.co.id',
                'alamat'         => 'Jl. Gatot Subroto No. 88, Jakarta Selatan',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'kode_pelanggan' => 'CST-002',
                'nama_pelanggan' => 'Toko Berkah Mandiri',
                'tipe'           => 'BISNIS',
                'kontak_person'  => 'Ibu Siti Aisyah',
                'telepon'        => '081399887766',
                'email'          => 'berkahmandiri@gmail.com',
                'alamat'         => 'Ruko Sentra Niaga Blok B-12, Bekasi',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'kode_pelanggan' => 'DEPT-001',
                'nama_pelanggan' => 'Divisi Operasional & Gudang Cabang',
                'tipe'           => 'DEPARTEMEN',
                'kontak_person'  => 'Darmawan (SPV)',
                'telepon'        => '081544332211',
                'email'          => 'ops.internal@inventarispro.id',
                'alamat'         => 'Internal Office Lt. 2 - Hub Logistik Barat',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'kode_pelanggan' => 'DEPT-002',
                'nama_pelanggan' => 'Divisi IT & Infrastruktur Jaringan',
                'tipe'           => 'DEPARTEMEN',
                'kontak_person'  => 'Fajar Nugraha',
                'telepon'        => '081755667788',
                'email'          => 'it.support@inventarispro.id',
                'alamat'         => 'Head Office Lt. 4 - Ruang Server Utama',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        $builder = $this->db->table('customers');

        foreach ($data as $item) {
            $existing = $builder->where('kode_pelanggan', $item['kode_pelanggan'])->countAllResults();
            if ($existing === 0) {
                $builder->insert($item);
            }
        }
    }
}
