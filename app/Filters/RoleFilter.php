<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Memeriksa apakah pengguna yang sedang login memiliki peran yang sesuai
     *
     * @param RequestInterface $request
     * @param array|null       $arguments Daftar peran yang diizinkan (misal: ['admin'], ['admin', 'staff'])
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Pastikan pengguna sudah terautentikasi ke dalam sistem
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))
                             ->with('error', 'Silakan login terlebih dahulu untuk mengakses fitur ini.');
        }

        // 2. Jika tidak ada pembatasan peran khusus pada rute, izinkan akses
        if (empty($arguments)) {
            return;
        }

        $userRole = $session->get('role') ?? 'staff';

        // 3. Periksa apakah peran pengguna termasuk dalam daftar peran yang diizinkan
        if (!in_array($userRole, $arguments, true)) {
            // Jika panggilan berasal dari API / AJAX JSON
            if ($request->isAJAX() || ($request->hasHeader('Accept') && str_contains($request->getHeaderLine('Accept'), 'application/json'))) {
                return service('response')
                    ->setStatusCode(403)
                    ->setJSON([
                        'status'  => 'error',
                        'message' => 'Akses ditolak: Peran (' . $userRole . ') Anda tidak memiliki wewenang untuk tindakan ini.'
                    ]);
            }

            // Jika akses melalui browser biasa, kembalikan ke katalog dengan notifikasi error
            return redirect()->to(base_url('products'))
                             ->with('error', 'Akses ditolak: Peran Anda (' . strtoupper($userRole) . ') tidak memiliki wewenang untuk mengakses halaman atau fitur tersebut.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
