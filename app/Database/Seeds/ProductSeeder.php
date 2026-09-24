<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'id'          => 1,
                'user_id'     => 1,
                'kode_produk' => 'PRD-001',
                'nama_produk' => 'Laptop Asus Zenbook 14 OLED',
                'kategori'    => 'Elektronik',
                'harga'       => 15499000.00,
                'stok'        => 12,
                'deskripsi'   => 'Laptop ultra tipis layar OLED 2.8K dengan prosesor Intel Core i7.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 2,
                'user_id'     => 1,
                'kode_produk' => 'PRD-002',
                'nama_produk' => 'Mouse Wireless Logitech MX Master 3S',
                'kategori'    => 'Aksesoris Komputer',
                'harga'       => 1650000.00,
                'stok'        => 25,
                'deskripsi'   => 'Mouse ergonomis nirkabel dengan sensor 8000 DPI yang hening.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 3,
                'user_id'     => 1,
                'kode_produk' => 'PRD-003',
                'nama_produk' => 'Keyboard Mechanical Keychron K2 V2',
                'kategori'    => 'Aksesoris Komputer',
                'harga'       => 1250000.00,
                'stok'        => 4,
                'deskripsi'   => 'Keyboard mekanikal wireless 75% layout dengan Gateron Brown switch.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 4,
                'user_id'     => 1,
                'kode_produk' => 'PRD-004',
                'nama_produk' => 'Monitor Gaming LG UltraGear 27 Inch',
                'kategori'    => 'Elektronik',
                'harga'       => 3800000.00,
                'stok'        => 0,
                'deskripsi'   => 'Monitor gaming IPS 144Hz 1ms dengan dukungan G-Sync Compatible.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 5,
                'user_id'     => 1,
                'kode_produk' => 'PRD-005',
                'nama_produk' => 'Meja Kerja Minimalis Ergonomis',
                'kategori'    => 'Perabotan',
                'harga'       => 850000.00,
                'stok'        => 18,
                'deskripsi'   => 'Meja kerja kayu solid dengan rangka baja kokoh dan lubang kabel rapi.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($products as $product) {
            $exists = $this->db->table('products')->where('kode_produk', $product['kode_produk'])->countAllResults();
            if ($exists === 0) {
                $this->db->table('products')->insert($product);
            }
        }
    }
}
