<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Models\UserModel;

/**
 * Controller Otentikasi Web UI (Login, Register, Logout).
 */
class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Tampilan Formulir Login Pengguna
     */
    public function login()
    {
        return view('auth/login', [
            'page_title' => 'Login'
        ]);
    }

    /**
     * Memproses Otentikasi Kredensial Login
     */
    public function attemptLogin()
    {
        $rules = [
            'identity' => 'required',
            'password' => 'required',
        ];

        $messages = [
            'identity' => [
                'required' => 'Silakan masukkan username atau email Anda.'
            ],
            'password' => [
                'required' => 'Silakan masukkan kata sandi Anda.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $throttler = service('throttler');
        $throttleKey = 'auth_login_' . md5($this->request->getIPAddress());

        // Cek kuota percobaan gagal (5 kali per 60 detik)
        if ($throttler->check($throttleKey, 5, 60, 0) === false) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Terlalu banyak percobaan masuk yang gagal. Silakan tunggu 1 menit sebelum mencoba kembali.');
        }

        $identity = trim((string)$this->request->getPost('identity'));
        $password = (string)$this->request->getPost('password');

        $user = $this->userModel->verifyCredentials($identity, $password);

        if (!$user) {
            $throttler->check($throttleKey, 5, 60, 1);

            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Kredensial tidak valid. Periksa kembali username/email dan password Anda.');
        }

        session()->regenerate(true);

        session()->set([
            'user_id'      => $user['id'],
            'nama_lengkap' => $user['nama_lengkap'],
            'username'     => $user['username'],
            'email'        => $user['email'],
            'role'         => $user['role'] ?? 'admin',
            'isLoggedIn'   => true,
        ]);

        (new \App\Services\ActivityLogService())->record(
            'AUTH',
            'LOGIN',
            "Pengguna berhasil login ke sistem: '{$user['nama_lengkap']}' (@{$user['username']})",
            (string)$user['id'],
            $user['username']
        );

        return redirect()->to(base_url('products'))
                         ->with('success', 'Selamat datang kembali, ' . esc($user['nama_lengkap']) . '!');
    }

    /**
     * Tampilan Formulir Pendaftaran Akun Pengguna Baru
     */
    public function register()
    {
        return view('auth/register', [
            'page_title' => 'Pendaftaran Akun'
        ]);
    }

    /**
     * Memproses Registrasi Akun Pengguna Baru
     */
    public function attemptRegister()
    {
        $rules = [
            'nama_lengkap'     => 'required|min_length[3]|max_length[100]',
            'username'         => 'required|alpha_numeric_punct|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'            => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'nama_lengkap' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'username' => [
                'required'  => 'Username wajib diisi.',
                'is_unique' => 'Username ini sudah terdaftar oleh pengguna lain.',
            ],
            'email' => [
                'required'    => 'Alamat email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Alamat email ini sudah terdaftar.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal harus 6 karakter.',
            ],
            'password_confirm' => [
                'required' => 'Konfirmasi password wajib diisi.',
                'matches'  => 'Konfirmasi password tidak cocok dengan password yang dimasukkan.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $saveData = [
            'nama_lengkap' => trim((string)$this->request->getPost('nama_lengkap')),
            'username'     => trim((string)$this->request->getPost('username')),
            'email'        => trim((string)$this->request->getPost('email')),
            'password'     => password_hash((string)$this->request->getPost('password'), PASSWORD_BCRYPT),
        ];

        if ($this->userModel->insert($saveData)) {
            return redirect()->to(base_url('login'))
                             ->with('success', 'Pendaftaran akun berhasil! Silakan masuk dengan kredensial baru Anda.');
        }

        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Terjadi kesalahan saat menyimpan data akun pengguna.');
    }

    /**
     * Memproses Logout Pengguna
     */
    public function logout()
    {
        $userName = session()->get('nama_lengkap') ?? 'Pengguna';
        $userId   = session()->get('user_id');

        (new \App\Services\ActivityLogService())->record(
            'AUTH',
            'LOGOUT',
            "Pengguna keluar dari sistem (logout): '{$userName}'",
            $userId ? (string)$userId : null,
            $userName
        );

        session()->destroy();
        return redirect()->to(base_url('login'))
                         ->with('success', 'Anda telah berhasil keluar dari sistem inventaris.');
    }
}
