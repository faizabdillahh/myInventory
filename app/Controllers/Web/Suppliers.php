<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\DTOs\SupplierDTO;
use App\Services\SupplierService;

/**
 * Controller Supplier Web UI (Presentation Layer - Lean Controller).
 * Menangani HTTP request/response untuk pengelolaan data master pemasok.
 */
class Suppliers extends BaseController
{
    protected SupplierService $supplierService;

    public function __construct(?SupplierService $supplierService = null)
    {
        $this->supplierService = $supplierService ?? new SupplierService();
    }

    /**
     * Halaman Utama Master Data Supplier
     */
    public function index()
    {
        $search = trim($this->request->getGet('q') ?? '');
        $suppliers = $this->supplierService->getAllSuppliers($search);

        return view('suppliers/index', [
            'page_title'  => 'Master Data Pemasok (Suppliers)',
            'suppliers'   => $suppliers,
            'searchQuery' => $search,
        ]);
    }

    /**
     * Halaman Tambah Supplier Baru
     */
    public function new()
    {
        return view('suppliers/create', [
            'page_title' => 'Tambah Pemasok Baru',
        ]);
    }

    /**
     * Memproses Penyimpanan Supplier Baru
     */
    public function create()
    {
        $rules = [
            'kode_supplier' => 'required|min_length[2]|max_length[30]|is_unique[suppliers.kode_supplier]',
            'nama_supplier' => 'required|min_length[3]|max_length[150]',
            'kontak_person' => 'permit_empty|max_length[100]',
            'telepon'       => 'permit_empty|max_length[30]',
            'email'         => 'permit_empty|valid_email|max_length[100]',
            'alamat'        => 'permit_empty',
        ];

        $messages = [
            'kode_supplier' => [
                'required'  => 'Kode supplier wajib diisi.',
                'is_unique' => 'Kode supplier ini sudah terdaftar. Gunakan kode unik.',
            ],
            'nama_supplier' => [
                'required'   => 'Nama supplier wajib diisi.',
                'min_length' => 'Nama supplier minimal 3 karakter.',
            ],
            'email' => [
                'valid_email' => 'Format alamat email tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $dto = SupplierDTO::fromArray($this->request->getPost());
            $this->supplierService->createSupplier($dto);

            return redirect()->to(base_url('suppliers'))
                             ->with('success', "Supplier '{$dto->namaSupplier}' berhasil ditambahkan ke sistem!");
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[Suppliers::create] ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data supplier.');
        }
    }

    /**
     * Halaman Edit Data Supplier
     */
    public function edit(int $id)
    {
        $supplier = $this->supplierService->getSupplier($id);
        if (!$supplier) {
            return redirect()->to(base_url('suppliers'))
                             ->with('error', "Data supplier dengan ID #{$id} tidak ditemukan.");
        }

        return view('suppliers/edit', [
            'page_title' => 'Edit Supplier: ' . $supplier['nama_supplier'],
            'supplier'   => $supplier,
        ]);
    }

    /**
     * Memproses Pembaruan Data Supplier
     */
    public function update(int $id)
    {
        $rules = [
            'kode_supplier' => "required|min_length[2]|max_length[30]|is_unique[suppliers.kode_supplier,id,{$id}]",
            'nama_supplier' => 'required|min_length[3]|max_length[150]',
            'kontak_person' => 'permit_empty|max_length[100]',
            'telepon'       => 'permit_empty|max_length[30]',
            'email'         => 'permit_empty|valid_email|max_length[100]',
            'alamat'        => 'permit_empty',
        ];

        $messages = [
            'kode_supplier' => [
                'required'  => 'Kode supplier wajib diisi.',
                'is_unique' => 'Kode supplier ini sudah terdaftar.',
            ],
            'nama_supplier' => [
                'required'   => 'Nama supplier wajib diisi.',
                'min_length' => 'Nama supplier minimal 3 karakter.',
            ],
            'email' => [
                'valid_email' => 'Format alamat email tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $dto = SupplierDTO::fromArray($this->request->getPost());
            $this->supplierService->updateSupplier($id, $dto);

            return redirect()->to(base_url('suppliers'))
                             ->with('success', "Data supplier '{$dto->namaSupplier}' berhasil diperbarui!");
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[Suppliers::update] ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui supplier.');
        }
    }

    /**
     * Memproses Penghapusan Supplier
     */
    public function delete(int $id)
    {
        try {
            $this->supplierService->deleteSupplier($id);

            return redirect()->to(base_url('suppliers'))
                             ->with('success', 'Data supplier berhasil dihapus dari sistem.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->to(base_url('suppliers'))->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[Suppliers::delete] ' . $e->getMessage());
            return redirect()->to(base_url('suppliers'))->with('error', 'Terjadi kesalahan sistem saat menghapus data supplier.');
        }
    }
}
