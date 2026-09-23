<?php
/**
 * ==============================================================================
 * FILE: includes/header.php
 * DESKRIPSI: Template Header HTML & Navbar Navigasi Utama Aplikasi
 * ==============================================================================
 * Komponen ini dimuat di bagian atas setiap halaman utama. Berisi tag <head>,
 * stylesheet CSS, font modern Google 'Plus Jakarta Sans', dan navigasi profil.
 */

// Memastikan helper fungsi global telah dimuat
require_once __DIR__ . '/functions.php';

// Menentukan judul halaman default jika variabel $page_title belum diset
$page_title = $page_title ?? 'Sistem Manajemen Inventaris';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= e($page_title) ?> - InventarisPro</title>
    
    <!-- Integrasi Font Modern Google: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- File Stylesheet Kustom Utama -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- =========================================================================
     * NAVBAR UTAMA
     * ========================================================================= -->
    <nav class="navbar">
        <div class="container navbar-inner">
            <!-- Brand Logo dan Nama Aplikasi -->
            <a href="index.php" class="brand">
                <div class="brand-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                <span>InventarisPro</span>
            </a>

            <!-- Navigasi Sisi Kanan (Informasi Pengguna & Aksi) -->
            <div class="nav-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Menampilkan info nama akun yang sedang aktif login -->
                    <div class="user-badge" title="Login sebagai <?= e($_SESSION['username'] ?? '') ?>">
                        <div class="user-avatar">
                            <?= strtoupper(substr($_SESSION['nama_lengkap'] ?? 'U', 0, 1)) ?>
                        </div>
                        <span><?= e($_SESSION['nama_lengkap'] ?? 'Pengguna') ?></span>
                    </div>

                    <!-- Tombol Keluar / Logout -->
                    <a href="logout.php" class="btn btn-secondary btn-sm" onclick="return confirm('Apakah Anda yakin ingin logout dari sistem?');">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Logout
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary btn-sm">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Kontainer Pembuka Halaman -->
    <main style="flex: 1; padding: 32px 0;">
        <div class="container">
            <!-- Menampilkan Flash Message Success jika ada -->
            <?php if ($flash_success = get_flash('success')): ?>
                <div class="alert alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div><?= e($flash_success) ?></div>
                </div>
            <?php endif; ?>

            <!-- Menampilkan Flash Message Error jika ada -->
            <?php if ($flash_error = get_flash('error')): ?>
                <div class="alert alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div><?= e($flash_error) ?></div>
                </div>
            <?php endif; ?>
