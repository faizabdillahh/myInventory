<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\DTOs\ProductDTO;
use App\Services\CategoryService;
use App\Services\CustomerService;
use App\Services\ProductService;
use App\Services\SupplierService;

/**
 * Controller Produk Web UI (Presentation Layer - Lean Controller).
 * Bertugas mengoordinasikan HTTP request/response antarmuka web dengan ProductService.
 */
class Products extends BaseController
{
    protected ProductService $productService;
    protected CategoryService $categoryService;
    protected SupplierService $supplierService;
    protected CustomerService $customerService;

    public function __construct(
        ?ProductService $productService = null, 
        ?CategoryService $categoryService = null,
        ?SupplierService $supplierService = null,
        ?CustomerService $customerService = null
    ) {
        $this->productService  = $productService ?? new ProductService();
        $this->categoryService = $categoryService ?? new CategoryService();
        $this->supplierService = $supplierService ?? new SupplierService();
        $this->customerService = $customerService ?? new CustomerService();
    }

    /**
     * Dashboard Utama & Daftar Seluruh Produk Inventaris
     */
    public function index()
    {
        $search   = trim($this->request->getGet('q') ?? '');
        $category = trim($this->request->getGet('kategori') ?? '');
        $status   = trim($this->request->getGet('status') ?? '');
        $data     = $this->productService->getDashboardData($search, $category, $status);

        return view('products/index', [
            'page_title'       => 'Dashboard Produk',
            'stats'            => $data['stats'],
            'products'         => $data['products'],
            'searchQuery'      => $search,
            'selectedCategory' => $category,
            'selectedStatus'   => $status,
            'categories'       => $this->categoryService->getCategoriesForDropdown(),
            'suppliers'        => $this->supplierService->getSuppliersForDropdown(),
            'customers'        => $this->customerService->getCustomersForDropdown(),
        ]);
    }

    /**
     * Halaman Tambah Produk Baru
     */
    public function new()
    {
        return view('products/create', [
            'page_title' => 'Tambah Produk Baru',
            'categories' => $this->categoryService->getCategoriesForDropdown(),
            'suppliers'  => $this->supplierService->getSuppliersForDropdown(),
        ]);
    }

    /**
     * Memproses Penyimpanan Data Produk Baru
     */
    public function create()
    {
        $rules = [
            'kode_produk'  => 'required|min_length[2]|max_length[30]|is_unique[products.kode_produk]',
            'nama_produk'  => 'required|min_length[3]|max_length[150]',
            'kategori'     => 'required|min_length[2]|max_length[50]',
            'harga'        => 'required|numeric|greater_than_equal_to[0]',
            'stok'         => 'required|integer|greater_than_equal_to[0]',
            'stok_minimum' => 'permit_empty|integer|greater_than_equal_to[0]',
            'supplier_id'  => 'permit_empty|is_not_unique[suppliers.id]',
            'deskripsi'    => 'permit_empty',
        ];

        $messages = [
            'kode_produk' => [
                'required'  => 'Kode produk (SKU) wajib diisi.',
                'is_unique' => 'Kode SKU ini sudah digunakan oleh produk lain. Masukkan kode unik.',
            ],
            'nama_produk' => [
                'required'   => 'Nama produk wajib diisi.',
                'min_length' => 'Nama produk minimal 3 karakter.',
            ],
            'kategori' => [
                'required' => 'Kategori produk wajib dipilih atau diisi.',
            ],
            'harga' => [
                'required'              => 'Harga produk wajib diisi.',
                'numeric'               => 'Harga harus berupa angka nominal yang valid.',
                'greater_than_equal_to' => 'Harga tidak boleh bernilai negatif.',
            ],
            'stok' => [
                'required'              => 'Jumlah stok fisik wajib diisi.',
                'integer'               => 'Stok harus berupa bilangan bulat.',
                'greater_than_equal_to' => 'Stok tidak boleh bernilai negatif.',
            ],
            'stok_minimum' => [
                'integer'               => 'Batas stok minimum harus berupa bilangan bulat.',
                'greater_than_equal_to' => 'Batas stok minimum tidak boleh bernilai negatif.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        try {
            $dto = ProductDTO::fromArray(
                $this->request->getPost(),
                (int)session()->get('user_id')
            );

            $this->productService->createProduct($dto);

            return redirect()->to(base_url('products'))
                             ->with('success', 'Produk "' . esc($dto->namaProduk) . '" berhasil ditambahkan ke inventaris!');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[Products::create] ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan produk.');
        }
    }

    /**
     * Halaman Edit Informasi Produk
     */
    public function edit(int $id)
    {
        $product = $this->productService->getProduct($id);

        if (!$product) {
            return redirect()->to(base_url('products'))
                             ->with('error', "Data produk dengan ID #{$id} tidak ditemukan.");
        }

        return view('products/edit', [
            'page_title' => 'Edit Produk: ' . $product['nama_produk'],
            'product'    => $product,
            'categories' => $this->categoryService->getCategoriesForDropdown(),
            'suppliers'  => $this->supplierService->getSuppliersForDropdown(),
        ]);
    }

    /**
     * Memproses Pembaruan Data Produk
     */
    public function update(int $id)
    {
        $rules = [
            'kode_produk'  => "required|min_length[2]|max_length[30]|is_unique[products.kode_produk,id,{$id}]",
            'nama_produk'  => 'required|min_length[3]|max_length[150]',
            'kategori'     => 'required|min_length[2]|max_length[50]',
            'harga'        => 'required|numeric|greater_than_equal_to[0]',
            'stok'         => 'required|integer|greater_than_equal_to[0]',
            'stok_minimum' => 'permit_empty|integer|greater_than_equal_to[0]',
            'supplier_id'  => 'permit_empty|is_not_unique[suppliers.id]',
            'deskripsi'    => 'permit_empty',
        ];

        $messages = [
            'kode_produk' => [
                'required'  => 'Kode produk (SKU) wajib diisi.',
                'is_unique' => 'Kode SKU ini sudah digunakan oleh produk lain.',
            ],
            'nama_produk' => [
                'required'   => 'Nama produk wajib diisi.',
                'min_length' => 'Nama produk minimal 3 karakter.',
            ],
            'kategori' => [
                'required' => 'Kategori produk wajib dipilih atau diisi.',
            ],
            'harga' => [
                'required'              => 'Harga produk wajib diisi.',
                'numeric'               => 'Harga harus berupa angka nominal yang valid.',
                'greater_than_equal_to' => 'Harga tidak boleh bernilai negatif.',
            ],
            'stok' => [
                'required'              => 'Jumlah stok fisik wajib diisi.',
                'integer'               => 'Stok harus berupa bilangan bulat.',
                'greater_than_equal_to' => 'Stok tidak boleh bernilai negatif.',
            ],
            'stok_minimum' => [
                'integer'               => 'Batas stok minimum harus berupa bilangan bulat.',
                'greater_than_equal_to' => 'Batas stok minimum tidak boleh bernilai negatif.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        try {
            $dto = ProductDTO::fromArray(
                $this->request->getPost(),
                (int)session()->get('user_id')
            );

            $this->productService->updateProduct($id, $dto);

            return redirect()->to(base_url('products'))
                             ->with('success', 'Data produk "' . esc($dto->namaProduk) . '" berhasil diperbarui!');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[Products::update] ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui data produk.');
        }
    }

    /**
     * Memproses Penghapusan Data Produk
     */
    public function delete(int $id)
    {
        try {
            $this->productService->deleteProduct($id);

            return redirect()->to(base_url('products'))
                             ->with('success', 'Produk berhasil dihapus dari inventaris.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->to(base_url('products'))->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[Products::delete] ' . $e->getMessage());
            return redirect()->to(base_url('products'))->with('error', 'Gagal menghapus produk dari database.');
        }
    }
}
