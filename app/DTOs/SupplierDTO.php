<?php

namespace App\DTOs;

/**
 * Data Transfer Object (DTO) untuk Entitas Supplier
 * Menjamin type-safety data input form supplier.
 */
class SupplierDTO
{
    public function __construct(
        public readonly string $kodeSupplier,
        public readonly string $namaSupplier,
        public readonly ?string $kontakPerson = null,
        public readonly ?string $telepon = null,
        public readonly ?string $email = null,
        public readonly ?string $alamat = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            kodeSupplier: strtoupper(trim($data['kode_supplier'] ?? '')),
            namaSupplier: trim($data['nama_supplier'] ?? ''),
            kontakPerson: !empty($data['kontak_person']) ? trim($data['kontak_person']) : null,
            telepon: !empty($data['telepon']) ? trim($data['telepon']) : null,
            email: !empty($data['email']) ? trim($data['email']) : null,
            alamat: !empty($data['alamat']) ? trim($data['alamat']) : null
        );
    }

    public function toArray(): array
    {
        return [
            'kode_supplier' => $this->kodeSupplier,
            'nama_supplier' => $this->namaSupplier,
            'kontak_person' => $this->kontakPerson,
            'telepon'       => $this->telepon,
            'email'         => $this->email,
            'alamat'        => $this->alamat,
        ];
    }
}
