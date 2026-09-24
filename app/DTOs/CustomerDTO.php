<?php

namespace App\DTOs;

/**
 * Data Transfer Object (DTO) untuk Entitas Pelanggan / Departemen Penerima
 * Menjamin type-safety data input form pelanggan.
 */
class CustomerDTO
{
    public function __construct(
        public readonly string $kodePelanggan,
        public readonly string $namaPelanggan,
        public readonly string $tipe = 'BISNIS',
        public readonly ?string $kontakPerson = null,
        public readonly ?string $telepon = null,
        public readonly ?string $email = null,
        public readonly ?string $alamat = null
    ) {}

    public static function fromArray(array $data): self
    {
        $tipe = strtoupper(trim($data['tipe'] ?? 'BISNIS'));
        if (!in_array($tipe, ['BISNIS', 'INDIVIDUAL', 'DEPARTEMEN'], true)) {
            $tipe = 'BISNIS';
        }

        return new self(
            kodePelanggan: strtoupper(trim($data['kode_pelanggan'] ?? '')),
            namaPelanggan: trim($data['nama_pelanggan'] ?? ''),
            tipe: $tipe,
            kontakPerson: !empty($data['kontak_person']) ? trim($data['kontak_person']) : null,
            telepon: !empty($data['telepon']) ? trim($data['telepon']) : null,
            email: !empty($data['email']) ? trim($data['email']) : null,
            alamat: !empty($data['alamat']) ? trim($data['alamat']) : null
        );
    }

    public function toArray(): array
    {
        return [
            'kode_pelanggan' => $this->kodePelanggan,
            'nama_pelanggan' => $this->namaPelanggan,
            'tipe'           => $this->tipe,
            'kontak_person'  => $this->kontakPerson,
            'telepon'        => $this->telepon,
            'email'          => $this->email,
            'alamat'         => $this->alamat,
        ];
    }
}
