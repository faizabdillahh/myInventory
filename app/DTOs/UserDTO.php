<?php

namespace App\DTOs;

/**
 * Data Transfer Object (DTO) untuk Entitas Pengguna (User)
 * Menjamin type-safety dan standarisasi data input pengguna.
 */
class UserDTO
{
    public function __construct(
        public readonly string $namaLengkap,
        public readonly string $username,
        public readonly string $email,
        public readonly ?string $password = null,
        public readonly string $role = 'staff'
    ) {}

    public static function fromArray(array $data): self
    {
        $role = trim(strtolower($data['role'] ?? 'staff'));
        if (!in_array($role, ['admin', 'staff'], true)) {
            $role = 'staff';
        }

        return new self(
            namaLengkap: trim($data['nama_lengkap'] ?? ''),
            username:    trim(strtolower($data['username'] ?? '')),
            email:       trim(strtolower($data['email'] ?? '')),
            password:    !empty($data['password']) ? $data['password'] : null,
            role:        $role
        );
    }

    public function toArray(): array
    {
        $data = [
            'nama_lengkap' => $this->namaLengkap,
            'username'     => $this->username,
            'email'        => $this->email,
            'role'         => $this->role,
        ];

        if ($this->password !== null && $this->password !== '') {
            $data['password'] = password_hash($this->password, PASSWORD_BCRYPT);
        }

        return $data;
    }
}
