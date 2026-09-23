<?php
/**
 * ==============================================================================
 * FILE: includes/functions.php
 * DESKRIPSI: Kumpulan Fungsi Pembantu Global (Helper & Security Utilities)
 * ==============================================================================
 * Menerapkan prinsip DRY (Don't Repeat Yourself) dan Best Practices:
 * 1. Sanitasi output HTML (XSS Prevention).
 * 2. Proteksi Cross-Site Request Forgery (CSRF Protection).
 * 3. Manajemen Session Flash Message.
 * 4. Helper format mata uang Rupiah dan penanganan URL redirect.
 */

// Memastikan session telah aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Mengamankan output teks sebelum ditampilkan ke HTML untuk mencegah Cross-Site Scripting (XSS).
 *
 * @param mixed $string Teks yang akan ditampilkan
 * @return string Teks yang sudah disanitasi secara aman
 */
function e($string): string {
    return htmlspecialchars((string)($string ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Mengalihkan pengguna ke URL lain secara aman lalu menghentikan script.
 *
 * @param string $url URL tujuan pengalihan
 * @return void
 */
function redirect(string $url): void {
    header("Location: {$url}");
    exit;
}

/**
 * Membuat token CSRF unik jika belum ada di dalam session.
 *
 * @return string Token CSRF acak heksadesimal 64 karakter
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Menghasilkan elemen HTML input hidden berisi token CSRF untuk formulir POST.
 *
 * @return string Elemen tag <input type="hidden" ...>
 */
function csrf_field(): string {
    $token = e(csrf_token());
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Memvalidasi apakah token CSRF yang dikirim via formulir cocok dengan token di session.
 *
 * @param string|null $token Token yang dikirim dari $_POST['csrf_token']
 * @return bool True jika valid, False jika tidak cocok
 */
function verify_csrf(?string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Menyimpan pesan flash (notifikasi sementara satu kali tampil) ke dalam session.
 *
 * @param string $type Tipe pesan ('success' atau 'error')
 * @param string $message Isi teks notifikasi
 * @return void
 */
function set_flash(string $type, string $message): void {
    $_SESSION["flash_{$type}"] = $message;
}

/**
 * Mengambil dan menghapus pesan flash dari session agar tidak tampil berulang kali.
 *
 * @param string $type Tipe pesan ('success' atau 'error')
 * @return string|null Pesan flash jika ada, atau null jika tidak ada
 */
function get_flash(string $type): ?string {
    $key = "flash_{$type}";
    if (isset($_SESSION[$key])) {
        $msg = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $msg;
    }
    return null;
}

/**
 * Format angka numerik ke format standar mata uang Rupiah Indonesia.
 *
 * @param float|int|string $nominal Angka nominal
 * @param bool $withPrefix Apakah menyertakan awalan 'Rp ' (default true)
 * @return string Nominal dalam format Rupiah (contoh: "Rp 15.000.000")
 */
function format_rupiah($nominal, bool $withPrefix = true): string {
    $formatted = number_format((float)$nominal, 0, ',', '.');
    return $withPrefix ? "Rp {$formatted}" : $formatted;
}

/**
 * Mengembalikan nilai isian formulir sebelumnya (old input) untuk kenyamanan pengguna
 * saat terjadi kegagalan validasi.
 *
 * @param string $key Nama field formulir
 * @param mixed $default Nilai default jika input belum pernah diisi
 * @return mixed Nilai input lama yang aman
 */
function old(string $key, $default = '') {
    return $_POST[$key] ?? $default;
}
