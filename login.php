<?php
/**
 * ==============================================================================
 * FILE: login.php
 * DESKRIPSI: Controller & View Halaman Masuk Pengguna (Login)
 * ==============================================================================
 * Menerapkan Clean Code & Best Practices:
 * 1. Logika autentikasi didelegasikan ke model User (classes/User.php).
 * 2. Proteksi terhadap serangan CSRF menggunakan token validasi.
 * 3. Sanitasi output menggunakan helper e().
 * 4. Regenerasi ID session untuk mencegah Session Fixation.
 */

// 1. Memuat dependensi yang dibutuhkan
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/User.php';

// Jika pengguna sudah login, arahkan langsung ke halaman utama
if (isset($_SESSION['user_id'])) {
    redirect('index.php');
}

// Inisialisasi model User
$userModel = new User($pdo);

// Variabel penampung pesan error
$errorMessage = '';

// 2. Menangani pengiriman formulir login via metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi token CSRF
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $errorMessage = "Token keamanan (CSRF) tidak valid atau sesi telah kedaluwarsa. Silakan refresh halaman.";
    } else {
        $identity = trim($_POST['identity'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validasi isian formulir
        if (empty($identity) || empty($password)) {
            $errorMessage = "Silakan masukkan username/email dan password Anda.";
        } else {
            // Memanggil method login pada model User
            $user = $userModel->login($identity, $password);

            if ($user) {
                // Regenerasi session ID untuk mencegah serangan Session Fixation
                session_regenerate_id(true);

                // Menyimpan data identitas pengguna ke session
                $_SESSION['user_id']      = $user['id'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['username']     = $user['username'];
                $_SESSION['email']        = $user['email'];

                set_flash('success', "Selamat datang kembali, " . e($user['nama_lengkap']) . "!");
                redirect('index.php');
            } else {
                $errorMessage = "Kredensial tidak valid. Periksa kembali username/email dan password Anda.";
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
    <title>Login - InventarisPro</title>
    <!-- Google Fonts Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">

    <div class="auth-card">
        <!-- Header Formulir Login -->
        <div class="auth-header">
            <div class="brand-icon" style="margin: 0 auto 12px auto; width: 48px; height: 48px;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
            <h1 class="auth-title">Masuk ke Sistem</h1>
            <p class="auth-subtitle">Kelola inventaris dan produk Anda dengan mudah dan aman.</p>
        </div>

        <!-- Menampilkan Pesan Error Validasi / Login -->
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

        <!-- Menampilkan Flash Message jika ada -->
        <?php if ($flash_error = get_flash('error')): ?>
            <div class="alert alert-danger">
                <div><?= e($flash_error) ?></div>
            </div>
        <?php endif; ?>

        <?php if ($flash_success = get_flash('success')): ?>
            <div class="alert alert-success">
                <div><?= e($flash_success) ?></div>
            </div>
        <?php endif; ?>

        <!-- Formulir Login -->
        <form action="login.php" method="POST" autocomplete="off">
            <!-- Proteksi CSRF Token -->
            <?= csrf_field() ?>

            <!-- Input Username atau Email -->
            <div class="form-group">
                <label for="identity" class="form-label">Username atau Email</label>
                <input 
                    type="text" 
                    id="identity" 
                    name="identity" 
                    class="form-control" 
                    placeholder="Contoh: admin atau nama@email.com" 
                    value="<?= e(old('identity')) ?>" 
                    required 
                    autofocus
                >
            </div>

            <!-- Input Password -->
            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi (Password)</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Masukkan password Anda" 
                    required
                >
            </div>

            <!-- Tombol Submit Login -->
            <div class="form-group" style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    Masuk Sekarang
                </button>
            </div>
        </form>

        <!-- Informasi Bantuan Akun Demo -->
        <div class="info-box">
            <span style="font-weight: 600; color: var(--text-main);">Akun Demo:</span>
            <div style="margin-top: 4px; font-family: ui-monospace, monospace; color: var(--text-muted);">
                User: <code>admin</code> &bull; Pass: <code>admin123</code>
            </div>
        </div>

        <!-- Tautan ke Halaman Pendaftaran (Register) -->
        <div style="text-align: center; margin-top: 20px; font-size: 13px; color: var(--text-muted);">
            Belum memiliki akun? <a href="register.php" style="color: var(--primary-blue); font-weight: 500; text-decoration: none;">Daftar akun</a>
        </div>
    </div>

    <!-- Skrip Interaktif -->
    <script src="assets/js/app.js"></script>
</body>
</html>
