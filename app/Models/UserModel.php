<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'role'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation Rules
    protected $validationRules = [
        'id'           => 'permit_empty|integer',
        'nama_lengkap' => 'required|min_length[3]|max_length[100]',
        'username'     => 'required|alpha_numeric_punct|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'email'        => 'required|valid_email|max_length[100]|is_unique[users.email,id,{id}]',
        'password'     => 'permit_empty|min_length[6]',
    ];

    protected $validationMessages = [
        'nama_lengkap' => [
            'required'   => 'Nama lengkap wajib diisi.',
            'min_length' => 'Nama lengkap minimal 3 karakter.',
            'max_length' => 'Nama lengkap maksimal 100 karakter.',
        ],
        'username' => [
            'required'  => 'Username wajib diisi.',
            'is_unique' => 'Username ini sudah terdaftar oleh pengguna lain.',
        ],
        'email' => [
            'required'    => 'Alamat email wajib diisi.',
            'valid_email' => 'Format alamat email tidak valid.',
            'is_unique'   => 'Alamat email ini sudah terdaftar.',
        ],
        'password' => [
            'min_length' => 'Password minimal harus terdiri dari 6 karakter.',
        ],
    ];

    /**
     * Mencari data pengguna berdasarkan username atau email
     */
    public function findByUsernameOrEmail(string $identity): ?array
    {
        return $this->where('username', $identity)
                    ->orWhere('email', $identity)
                    ->first();
    }

    /**
     * Memvalidasi kredensial login pengguna
     */
    public function verifyCredentials(string $identity, string $password): ?array
    {
        $user = $this->findByUsernameOrEmail($identity);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }
}
