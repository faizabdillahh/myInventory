<?php

namespace App\DTOs;

/**
 * Data Transfer Object (DTO) untuk Entitas Produk
 * Mengenkapsulasi input formulir dan menjamin type safety.
 */
class ProductDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly string $kodeProduk,
        public readonly string $namaProduk,
        public readonly string $kategori,
        public readonly float $harga,
        public readonly int $stok,
        public readonly int $stokMinimum = 5,
        public readonly ?int $supplierId = null,
        public readonly ?string $deskripsi = null
    ) {}

    /**
     * Membentuk instance DTO dari array data input
     */
    public static function fromArray(array $data, int $userId): self
    {
        $minStock = isset($data['stok_minimum']) && $data['stok_minimum'] !== '' 
            ? max(0, (int)$data['stok_minimum']) 
            : 5;

        $supplierId = !empty($data['supplier_id']) ? (int)$data['supplier_id'] : null;

        return new self(
            userId: $userId,
            kodeProduk: strtoupper(trim($data['kode_produk'] ?? '')),
            namaProduk: trim($data['nama_produk'] ?? ''),
            kategori: trim($data['kategori'] ?? ''),
            harga: (float)($data['harga'] ?? 0),
            stok: (int)($data['stok'] ?? 0),
            stokMinimum: $minStock,
            supplierId: $supplierId,
            deskripsi: !empty($data['deskripsi']) ? trim($data['deskripsi']) : null
        );
    }

    /**
     * Mengubah DTO menjadi representasi array untuk operasi database
     */
    public function toArray(): array
    {
        return [
            'user_id'      => $this->userId,
            'supplier_id'  => $this->supplierId,
            'kode_produk'  => $this->kodeProduk,
            'nama_produk'  => $this->namaProduk,
            'kategori'     => $this->kategori,
            'harga'        => $this->harga,
            'stok'         => $this->stok,
            'stok_minimum' => $this->stokMinimum,
            'deskripsi'    => $this->deskripsi,
        ];
    }
}
