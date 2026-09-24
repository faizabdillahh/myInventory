<?php
/**
 * ==============================================================================
 * FILE: includes/auth_check.php
 * DESKRIPSI: Middleware / Filter Keamanan Autentikasi Pengguna
 * ==============================================================================
 * File ini bertugas memastikan bahwa halaman-halaman internal (CRUD)
 * hanya dapat diakses oleh user yang telah berhasil login.
 */

// Memuat helper functions global jika belum dimuat
require_once __DIR__ . '/functions.php';

// Memeriksa apakah session user_id sudah diset
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    set_flash('error', 'Silakan login terlebih dahulu untuk mengakses halaman tersebut.');
    redirect('login.php');
}

// Data pengguna aktif saat ini
$current_user_id    = $_SESSION['user_id'];
$current_username   = $_SESSION['username'] ?? 'User';
$current_user_nama  = $_SESSION['nama_lengkap'] ?? 'Pengguna';
$current_user_email = $_SESSION['email'] ?? '';
