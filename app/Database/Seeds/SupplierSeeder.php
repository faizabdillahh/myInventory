<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'kode_supplier' => 'SPL-001',
                'nama_supplier' => 'PT Mega Graha Distribusi',
                'kontak_person' => 'Bambang Sudirgo',
                'telepon'       => '021-5558901',
                'email'         => 'sales@megagraha.co.id',
                'alamat'        => 'Kawasan Industri Pulogadung Blok B No. 12, Jakarta Timur',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'kode_supplier' => 'SPL-002',
                'nama_supplier' => 'CV Sumber Komputer Utama',
                'kontak_person' => 'Hendra Setiawan',
                'telepon'       => '031-8976543',
                'email'         => 'order@sumberkomputer.com',
                'alamat'        => 'Jl. Raya Darmo No. 88, Surabaya',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'kode_supplier' => 'SPL-003',
                'nama_supplier' => 'PT Mitra Office Mandiri',
                'kontak_person' => 'Siti Nurhaliza',
                'telepon'       => '022-7234567',
                'email'         => 'supplier@mitraoffice.id',
                'alamat'        => 'Jl. Soekarno Hatta No. 450, Bandung',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'kode_supplier' => 'SPL-004',
                'nama_supplier' => 'Grosir Aksesoris Nusantara',
                'kontak_person' => 'Rahmat Hidayat',
                'telepon'       => '0812-9876-5432',
                'email'         => 'info@aksesorisnusantara.com',
                'alamat'        => 'Pusat Niaga Mangga Dua Lt. 3 No. 45, Jakarta Pusat',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($suppliers as $data) {
            $existing = $this->db->table('suppliers')->where('kode_supplier', $data['kode_supplier'])->get()->getRow();
            if (!$existing) {
                $this->db->table('suppliers')->insert($data);
            }
        }
    }
}
