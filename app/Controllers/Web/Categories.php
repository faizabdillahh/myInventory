<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\DTOs\CategoryDTO;
use App\Services\CategoryService;

/**
 * Controller Kategori Produk Web UI (Presentation Layer - Lean Controller).
 * Menangani HTTP request untuk manajemen master data kategori.
 */
class Categories extends BaseController
{
    protected CategoryService $categoryService;

    public function __construct(?CategoryService $categoryService = null)
    {
        $this->categoryService = $categoryService ?? new CategoryService();
    }

    /**
     * Menampilkan daftar seluruh kategori produk
     */
    public function index()
    {
        return view('categories/index', [
            'page_title' => 'Master Kategori Produk',
            'categories' => $this->categoryService->getCategoriesList(),
        ]);
    }

    /**
     * Tampilan formulir tambah kategori baru
     */
    public function new()
    {
        return view('categories/create', [
            'page_title' => 'Tambah Kategori Baru',
        ]);
    }

    /**
     * Memproses penyimpanan kategori baru
     */
    public function create()
    {
        $rules = [
            'nama_kategori' => 'required|min_length[2]|max_length[100]|is_unique[categories.nama_kategori]',
            'deskripsi'     => 'permit_empty',
        ];

        $messages = [
            'nama_kategori' => [
                'required'   => 'Nama kategori wajib diisi.',
                'min_length' => 'Nama kategori minimal 2 karakter.',
                'max_length' => 'Nama kategori maksimal 100 karakter.',
                'is_unique'  => 'Nama kategori ini sudah terdaftar. Masukkan nama lain.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        try {
            $dto = CategoryDTO::fromArray($this->request->getPost());
            $this->categoryService->createCategory($dto);

            return redirect()->to(base_url('categories'))
                             ->with('success', 'Kategori "' . esc($dto->namaKategori) . '" berhasil ditambahkan!');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[Categories::create] ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan kategori.');
        }
    }

    /**
     * Tampilan formulir edit kategori
     */
    public function edit(int $id)
    {
        $category = $this->categoryService->getCategory($id);

        if (!$category) {
            return redirect()->to(base_url('categories'))
                             ->with('error', "Kategori dengan ID #{$id} tidak ditemukan.");
        }

        return view('categories/edit', [
            'page_title' => 'Edit Kategori: ' . $category['nama_kategori'],
            'category'   => $category,
        ]);
    }

    /**
     * Memproses pembaruan data kategori
     */
    public function update(int $id)
    {
        $rules = [
            'nama_kategori' => "required|min_length[2]|max_length[100]|is_unique[categories.nama_kategori,id,{$id}]",
            'deskripsi'     => 'permit_empty',
        ];

        $messages = [
            'nama_kategori' => [
                'required'   => 'Nama kategori wajib diisi.',
                'min_length' => 'Nama kategori minimal 2 karakter.',
                'max_length' => 'Nama kategori maksimal 100 karakter.',
                'is_unique'  => 'Nama kategori ini sudah digunakan oleh kategori lain.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        try {
            $dto = CategoryDTO::fromArray($this->request->getPost());
            $this->categoryService->updateCategory($id, $dto);

            return redirect()->to(base_url('categories'))
                             ->with('success', 'Data kategori "' . esc($dto->namaKategori) . '" berhasil diperbarui!');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[Categories::update] ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui kategori.');
        }
    }

    /**
     * Memproses penghapusan kategori
     */
    public function delete(int $id)
    {
        try {
            $this->categoryService->deleteCategory($id);

            return redirect()->to(base_url('categories'))
                             ->with('success', 'Kategori berhasil dihapus dari sistem.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->to(base_url('categories'))->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[Categories::delete] ' . $e->getMessage());
            return redirect()->to(base_url('categories'))->with('error', 'Gagal menghapus kategori dari database.');
        }
    }
}
