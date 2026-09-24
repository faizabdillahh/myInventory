<?php
/**
 * ==============================================================================
 * FILE: delete.php
 * DESKRIPSI: Controller Penghapusan Data Produk (DELETE)
 * ==============================================================================
 * Menerapkan Clean Code & Best Practices Keamanan:
 * 1. Hanya mengizinkan metode HTTP POST untuk tindakan destruktif (mencegah penghapusan via GET).
 * 2. Memvalidasi token CSRF sebelum menghapus data.
 * 3. Logika eksekusi query dibungkus dalam model Product.
 * 4. Memberikan feedback notifikasi flash message yang informatif.
 */

// 1. Memeriksa status login pengguna (Middleware Keamanan)
require_once __DIR__ . '/includes/auth_check.php';

// 2. Memuat koneksi database dan model Product
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Product.php';

// 3. Memastikan metode request adalah POST untuk keamanan data
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    set_flash('error', "Metode request tidak diizinkan. Penghapusan data harus melalui metode POST.");
    redirect('index.php');
}

// 4. Memvalidasi token keamanan CSRF
if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    set_flash('error', "Token keamanan (CSRF) tidak valid atau sesi telah kedaluwarsa.");
    redirect('index.php');
}

// 5. Mengambil dan memvalidasi ID produk dari URL atau POST
$productId = $_GET['id'] ?? ($_POST['id'] ?? null);

if (!$productId || !ctype_digit((string)$productId)) {
    set_flash('error', "Permintaan tidak valid: ID produk tidak ditemukan atau format salah.");
    redirect('index.php');
}

$productId = (int)$productId;

// Inisialisasi model Product
$productModel = new Product($pdo);

// 6. Mencari data produk terlebih dahulu untuk mendapatkan nama produk sebagai teks notifikasi
$product = $productModel->find($productId);

if (!$product) {
    set_flash('error', "Data produk yang ingin dihapus tidak ditemukan!");
    redirect('index.php');
}

// 7. Menjalankan penghapusan melalui model
if ($productModel->delete($productId)) {
    set_flash('success', "Produk '" . e($product['nama_produk']) . "' (" . e($product['kode_produk']) . ") berhasil dihapus dari sistem!");
} else {
    set_flash('error', "Gagal menghapus data produk dari database.");
}

// 8. Alihkan kembali ke dashboard tabel produk
redirect('index.php');
