<?php

namespace App\DTOs;

/**
 * Data Transfer Object (DTO) untuk Kategori Produk
 * Menjamin type safety dan enkapsulasi input kategori.
 */
class CategoryDTO
{
    public function __construct(
        public readonly string $namaKategori,
        public readonly ?string $deskripsi = null
    ) {}

    /**
     * Membentuk instance DTO dari array data input
     */
    public static function fromArray(array $data): self
    {
        return new self(
            namaKategori: trim($data['nama_kategori'] ?? ''),
            deskripsi: !empty($data['deskripsi']) ? trim($data['deskripsi']) : null
        );
    }

    /**
     * Mengubah DTO menjadi array untuk penyimpanan database
     */
    public function toArray(): array
    {
        return [
            'nama_kategori' => $this->namaKategori,
            'deskripsi'     => $this->deskripsi,
        ];
    }
}
