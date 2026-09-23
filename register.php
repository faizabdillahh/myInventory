<?php
/**
 * ==============================================================================
 * FILE: register.php
 * DESKRIPSI: Controller & View Pendaftaran Akun Pengguna Baru (Register)
 * ==============================================================================
 * Menerapkan Clean Code & Best Practices:
 * 1. Logika pendaftaran dan pengecekan duplikasi akun dibungkus di model User.
 * 2. Proteksi terhadap serangan CSRF menggunakan token validasi.
 * 3. Sanitasi output menggunakan helper e() dan old().
 */

// 1. Memuat dependensi
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/User.php';

// Jika sudah login, redirect langsung ke dashboard
if (isset($_SESSION['user_id'])) {
    redirect('index.php');
}

// Inisialisasi model User
$userModel = new User($pdo);

// Variabel penampung pesan error
$errorMessage = '';

// 2. Menangani formulir pendaftaran via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi token keamanan CSRF
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $errorMessage = "Token keamanan (CSRF) tidak valid. Silakan coba lagi.";
    } else {
        $namaLengkap     = trim($_POST['nama_lengkap'] ?? '');
        $username        = trim($_POST['username'] ?? '');
        $email           = trim($_POST['email'] ?? '');
        $password        = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // Validasi input form
        if (empty($namaLengkap) || empty($username) || empty($email) || empty($password) || empty($passwordConfirm)) {
            $errorMessage = "Semua kolom pendaftaran wajib diisi!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Format alamat email tidak valid!";
        } elseif (strlen($username) < 4) {
            $errorMessage = "Username minimal harus terdiri dari 4 karakter!";
        } elseif (strlen($password) < 6) {
            $errorMessage = "Password minimal harus memiliki panjang 6 karakter!";
        } elseif ($password !== $passwordConfirm) {
            $errorMessage = "Konfirmasi password tidak cocok dengan password yang dimasukkan!";
        } elseif ($userModel->isUsernameOrEmailTaken($username, $email)) {
            // Memeriksa keunikan akun via model
            $errorMessage = "Username atau email tersebut sudah terdaftar di sistem. Gunakan yang lain!";
        } else {
            // Proses penyimpanan akun baru melalui method register
            if ($userModel->register($namaLengkap, $username, $email, $password)) {
                set_flash('success', "Pendaftaran akun berhasil! Silakan masuk dengan akun baru Anda.");
                redirect('login.php');
            } else {
                $errorMessage = "Terjadi kesalahan saat menyimpan data akun baru.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - InventarisPro</title>
    <!-- Google Fonts Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">

    <div class="auth-card" style="max-width: 480px;">
        <!-- Header Registrasi -->
        <div class="auth-header">
            <div class="brand-icon" style="margin: 0 auto 12px auto; width: 48px; height: 48px;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7.5" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
            </div>
            <h1 class="auth-title">Daftar Akun Baru</h1>
            <p class="auth-subtitle">Buat akun untuk mulai mengelola data produk inventaris Anda.</p>
        </div>

        <!-- Menampilkan Alert Pesan Error jika Ada Masalah -->
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div><?= e($errorMessage) ?></div>
            </div>
        <?php endif; ?>

        <!-- Formulir Pendaftaran -->
        <form action="register.php" method="POST" autocomplete="off">
            <!-- Proteksi CSRF Token -->
            <?= csrf_field() ?>

            <!-- Kolom Nama Lengkap -->
            <div class="form-group">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input 
                    type="text" 
                    id="nama_lengkap" 
                    name="nama_lengkap" 
                    class="form-control" 
                    placeholder="Contoh: Budi Santoso" 
                    value="<?= e(old('nama_lengkap')) ?>" 
                    required 
                    autofocus
                >
            </div>

            <!-- Kolom Username & Email (Grid 2 Kolom) -->
            <div class="form-row">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-control" 
                        placeholder="Contoh: budi123" 
                        value="<?= e(old('username')) ?>" 
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        placeholder="budi@email.com" 
                        value="<?= e(old('email')) ?>" 
                        required
                    >
                </div>
            </div>

            <!-- Kolom Password & Konfirmasi Password (Grid 2 Kolom) -->
            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Min. 6 karakter" 
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="password_confirm" class="form-label">Ulangi Sandi</label>
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        class="form-control" 
                        placeholder="Ulangi kata sandi" 
                        required
                    >
                </div>
            </div>

            <!-- Tombol Submit Pendaftaran -->
            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 9px 16px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7.5" r="4"></circle>
                        <polyline points="17 11 19 13 23 9"></polyline>
                    </svg>
                    Daftar Akun
                </button>
            </div>
        </form>

        <!-- Tautan Kembali ke Halaman Login -->
        <div style="text-align: center; margin-top: 20px; font-size: 13px; color: var(--text-muted);">
            Sudah memiliki akun? <a href="login.php" style="color: var(--primary-blue); font-weight: 500; text-decoration: none;">Masuk</a>
        </div>
    </div>

    <!-- Skrip Interaktif -->
    <script src="assets/js/app.js"></script>
</body>
</html>
