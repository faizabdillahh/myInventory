<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\DTOs\CustomerDTO;
use App\Services\CustomerService;

/**
 * Controller Pelanggan / Departemen Penerima Web UI (Presentation Layer - Lean Controller).
 */
class Customers extends BaseController
{
    protected CustomerService $customerService;

    public function __construct(?CustomerService $customerService = null)
    {
        $this->customerService = $customerService ?? new CustomerService();
    }

    /**
     * Daftar Master Pelanggan / Departemen
     */
    public function index()
    {
        $search = trim($this->request->getGet('q') ?? '');

        return view('customers/index', [
            'page_title'  => 'Master Data Pelanggan & Departemen',
            'customers'   => $this->customerService->getAllCustomers($search),
            'searchQuery' => $search,
        ]);
    }

    /**
     * Formulir Pendaftaran Pelanggan / Departemen Baru
     */
    public function new()
    {
        return view('customers/create', [
            'page_title' => 'Tambah Pelanggan / Departemen Baru',
        ]);
    }

    /**
     * Memproses Penyimpanan Pelanggan Baru
     */
    public function create()
    {
        $rules = [
            'kode_pelanggan' => 'required|min_length[2]|max_length[30]|is_unique[customers.kode_pelanggan]',
            'nama_pelanggan' => 'required|min_length[3]|max_length[150]',
            'tipe'           => 'required|in_list[BISNIS,INDIVIDUAL,DEPARTEMEN]',
            'kontak_person'  => 'permit_empty|max_length[100]',
            'telepon'        => 'permit_empty|max_length[30]',
            'email'          => 'permit_empty|valid_email|max_length[100]',
            'alamat'         => 'permit_empty',
        ];

        $messages = [
            'kode_pelanggan' => [
                'required'  => 'Kode pelanggan / departemen wajib diisi.',
                'is_unique' => 'Kode pelanggan ini sudah terdaftar dalam sistem.',
            ],
            'nama_pelanggan' => [
                'required'   => 'Nama pelanggan / departemen wajib diisi.',
                'min_length' => 'Nama pelanggan minimal 3 karakter.',
            ],
            'email' => [
                'valid_email' => 'Format alamat email tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $dto = CustomerDTO::fromArray($this->request->getPost());
            $this->customerService->createCustomer($dto);

            return redirect()->to(base_url('customers'))
                             ->with('success', "Pelanggan '{$dto->namaPelanggan}' berhasil didaftarkan ke sistem!");
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[Customers::create] ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data pelanggan.');
        }
    }

    /**
     * Formulir Edit Pelanggan / Departemen
     */
    public function edit(int $id)
    {
        $customer = $this->customerService->getCustomer($id);
        if (!$customer) {
            return redirect()->to(base_url('customers'))
                             ->with('error', "Data pelanggan dengan ID #{$id} tidak ditemukan.");
        }

        return view('customers/edit', [
            'page_title' => 'Edit Pelanggan: ' . $customer['nama_pelanggan'],
            'customer'   => $customer,
        ]);
    }

    /**
     * Memproses Pembaruan Data Pelanggan
     */
    public function update(int $id)
    {
        $rules = [
            'kode_pelanggan' => "required|min_length[2]|max_length[30]|is_unique[customers.kode_pelanggan,id,{$id}]",
            'nama_pelanggan' => 'required|min_length[3]|max_length[150]',
            'tipe'           => 'required|in_list[BISNIS,INDIVIDUAL,DEPARTEMEN]',
            'kontak_person'  => 'permit_empty|max_length[100]',
            'telepon'        => 'permit_empty|max_length[30]',
            'email'          => 'permit_empty|valid_email|max_length[100]',
            'alamat'         => 'permit_empty',
        ];

        $messages = [
            'kode_pelanggan' => [
                'required'  => 'Kode pelanggan wajib diisi.',
                'is_unique' => 'Kode pelanggan ini sudah terdaftar.',
            ],
            'nama_pelanggan' => [
                'required'   => 'Nama pelanggan wajib diisi.',
                'min_length' => 'Nama pelanggan minimal 3 karakter.',
            ],
            'email' => [
                'valid_email' => 'Format alamat email tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $dto = CustomerDTO::fromArray($this->request->getPost());
            $this->customerService->updateCustomer($id, $dto);

            return redirect()->to(base_url('customers'))
                             ->with('success', "Data pelanggan '{$dto->namaPelanggan}' berhasil diperbarui!");
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[Customers::update] ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui pelanggan.');
        }
    }

    /**
     * Memproses Penghapusan Pelanggan
     */
    public function delete(int $id)
    {
        try {
            $this->customerService->deleteCustomer($id);

            return redirect()->to(base_url('customers'))
                             ->with('success', 'Data pelanggan berhasil dihapus dari sistem.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->to(base_url('customers'))->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[Customers::delete] ' . $e->getMessage());
            return redirect()->to(base_url('customers'))->with('error', 'Terjadi kesalahan sistem saat menghapus data pelanggan.');
        }
    }
}
