<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['nama_kategori' => 'Elektronik', 'deskripsi' => 'Perangkat komputer, laptop, monitor, dan gadget'],
            ['nama_kategori' => 'Aksesoris Komputer', 'deskripsi' => 'Mouse, keyboard, kabel, dongle, dan peripheral'],
            ['nama_kategori' => 'Perabotan', 'deskripsi' => 'Meja kantor, kursi ergonomis, dan lemari arsip'],
            ['nama_kategori' => 'Pakaian', 'deskripsi' => 'Seragam kerja, jaket, dan pakaian staf'],
            ['nama_kategori' => 'Makanan & Minuman', 'deskripsi' => 'Konsumsi kantor, snack, dan persediaan pantry'],
            ['nama_kategori' => 'Kesehatan & Kecantikan', 'deskripsi' => 'P3K, sabun cuci tangan, dan sanitasi'],
            ['nama_kategori' => 'Alat Tulis', 'deskripsi' => 'Kertas, pulpen, map, dan perlengkapan ATK'],
            ['nama_kategori' => 'Lainnya', 'deskripsi' => 'Barang inventaris umum non-kategori khusus'],
        ];

        foreach ($categories as $cat) {
            $exists = $this->db->table('categories')->where('nama_kategori', $cat['nama_kategori'])->countAllResults();
            if ($exists === 0) {
                $this->db->table('categories')->insert([
                    'nama_kategori' => $cat['nama_kategori'],
                    'deskripsi'     => $cat['deskripsi'],
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
