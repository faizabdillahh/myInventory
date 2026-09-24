<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\DTOs\UserDTO;
use App\Services\UserService;

/**
 * Controller Manajemen Pengguna & Hak Akses Web UI (Lean Controller).
 * Menangani HTTP request/response untuk pengelolaan akun sistem dan penetapan peran (RBAC).
 */
class Users extends BaseController
{
    protected UserService $userService;

    public function __construct(?UserService $userService = null)
    {
        $this->userService = $userService ?? new UserService();
    }

    /**
     * Halaman Utama Daftar Pengguna Sistem
     */
    public function index()
    {
        $search = trim($this->request->getGet('q') ?? '');
        $users  = $this->userService->getAllUsers($search);
        $stats  = $this->userService->getUserStatistics();

        return view('users/index', [
            'page_title'  => 'Manajemen Pengguna & Hak Akses',
            'users'       => $users,
            'stats'       => $stats,
            'searchQuery' => $search,
        ]);
    }

    /**
     * Halaman Formulir Pembuatan Pengguna Baru
     */
    public function new()
    {
        return view('users/create', [
            'page_title' => 'Tambah Pengguna Baru',
        ]);
    }

    /**
     * Memproses Penyimpanan Pengguna Baru
     */
    public function create()
    {
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'username'     => 'required|alpha_numeric_punct|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'        => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'password'     => 'required|min_length[6]',
            'role'         => 'required|in_list[admin,staff]',
        ];

        $messages = [
            'nama_lengkap' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'username' => [
                'required'  => 'Username wajib diisi.',
                'is_unique' => 'Username ini sudah digunakan.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email ini sudah terdaftar.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal 6 karakter.',
            ],
            'role' => [
                'required' => 'Peran (Role) wajib dipilih.',
                'in_list'  => 'Peran harus berupa Administrator atau Staff.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $dto = UserDTO::fromArray($this->request->getPost());
        $result = $this->userService->createUser($dto);

        if (!$result['success']) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', $result['message']);
        }

        return redirect()->to(base_url('users'))
                         ->with('success', $result['message']);
    }

    /**
     * Halaman Formulir Edit Pengguna
     */
    public function edit(int $id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return redirect()->to(base_url('users'))
                             ->with('error', 'Data pengguna tidak ditemukan.');
        }

        return view('users/edit', [
            'page_title' => 'Edit Pengguna: ' . esc($user['nama_lengkap']),
            'user'       => $user,
        ]);
    }

    /**
     * Memproses Pembaruan Data Pengguna
     */
    public function update(int $id)
    {
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'username'     => "required|alpha_numeric_punct|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'email'        => "required|valid_email|max_length[100]|is_unique[users.email,id,{$id}]",
            'password'     => 'permit_empty|min_length[6]',
            'role'         => 'required|in_list[admin,staff]',
        ];

        $messages = [
            'nama_lengkap' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'username' => [
                'required'  => 'Username wajib diisi.',
                'is_unique' => 'Username ini sudah digunakan oleh akun lain.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email ini sudah terdaftar oleh akun lain.',
            ],
            'password' => [
                'min_length' => 'Password baru minimal harus 6 karakter.',
            ],
            'role' => [
                'required' => 'Peran (Role) wajib dipilih.',
                'in_list'  => 'Peran harus berupa Administrator atau Staff.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $dto = UserDTO::fromArray($this->request->getPost());
        $currentUserId = (int) session()->get('user_id');

        $result = $this->userService->updateUser($id, $dto, $currentUserId);

        if (!$result['success']) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', $result['message']);
        }

        if ($id === $currentUserId) {
            session()->set([
                'nama_lengkap' => $dto->namaLengkap,
                'username'     => $dto->username,
                'email'        => $dto->email,
                'role'         => $dto->role,
            ]);
        }

        return redirect()->to(base_url('users'))
                         ->with('success', $result['message']);
    }

    /**
     * Memproses Penghapusan Akun Pengguna
     */
    public function delete(int $id)
    {
        $currentUserId = (int) session()->get('user_id');
        $result = $this->userService->deleteUser($id, $currentUserId);

        if (!$result['success']) {
            return redirect()->to(base_url('users'))
                             ->with('error', $result['message']);
        }

        return redirect()->to(base_url('users'))
                         ->with('success', $result['message']);
    }
}
