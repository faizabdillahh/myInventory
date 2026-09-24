<?php
/**
 * ==============================================================================
 * FILE: logout.php
 * DESKRIPSI: Controller Penghancuran Sesi & Logout Pengguna
 * ==============================================================================
 * Menerapkan Clean Code:
 * 1. Menghapus seluruh array data session.
 * 2. Menghapus cookie session pada browser pengguna.
 * 3. Menghancurkan session server dengan session_destroy().
 * 4. Menyimpan flash message perpisahan dan redirect ke login.php.
 */

// Memuat helper fungsi global
require_once __DIR__ . '/includes/functions.php';

// 1. Mengosongkan data superglobal $_SESSION
$_SESSION = [];

// 2. Menghapus cookie session pada browser klien
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 3. Menghancurkan sesi aktif di server
session_destroy();

// 4. Memulai session baru untuk menyimpan pesan flash notifikasi
session_start();
set_flash('success', 'Anda telah berhasil keluar (logout) dari sistem. Sampai jumpa kembali!');

// 5. Alihkan ke halaman login
redirect('login.php');
