-- ==============================================================================
-- DOKUMENTASI STRUKTUR & DATA DATABASE: `db_InventarisPro`
-- Sistem Manajemen Web Inventory (InventarisPro) - Berbasis CodeIgniter 4
-- Kompatibel Penuh: MySQL 8.x / MariaDB & phpMyAdmin (Laragon Environment)
-- Collation Standar: utf8mb4_unicode_ci
-- ==============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ==============================================================================
-- 1. PEMBUATAN & INISIALISASI DATABASE
-- Membuat basis data db_InventarisPro jika belum tersedia di server MySQL
-- ==============================================================================
CREATE DATABASE IF NOT EXISTS `db_InventarisPro` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_InventarisPro`;

-- ------------------------------------------------------------------------------
-- 2. TABEL: `users`
-- Fungsi: Menyimpan kredensial pengguna, profil, dan hak akses (Role-Based Access Control)
-- Penjelasan Kolom:
--   `id`           : Primary Key unik pengguna (Auto Increment).
--   `nama_lengkap` : Nama lengkap resmi pengguna untuk representasi akun.
--   `username`     : Username unik untuk otentikasi login sistem.
--   `email`        : Alamat email aktif dan unik.
--   `password`     : Kata sandi terenkripsi hash Bcrypt aman.
--   `role`         : Peran otorisasi sistem ('admin' = hak penuh, 'staff' = operasional terbatas).
--   `created_at`   : Timestamp waktu akun dibuat.
--   `updated_at`   : Waktu terakhir data profil/kata sandi diperbarui.
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID unik pengguna',
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama lengkap pengguna',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Username unik untuk login',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email pengguna yang terdaftar',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hash password terenkripsi Bcrypt',
  `role` enum('admin','staff') COLLATE utf8mb4_unicode_ci DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan akun',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping Data untuk Tabel `users`
-- Kredensial Default:
--   - Admin : username `admin` | password `admin123`
--   - Staf  : username `staff` | password `admin123`
--
INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Administrator Sistem', 'admin', 'admin@example.com', '$2y$10$2X5n1yfWsl37LwTNGXLXUeowNa.8aQJPORJStAMO9GA1gwfkdAWoe', 'admin', '2026-09-23 07:45:33', NULL),
(2, 'Staf Operasional Gudang', 'staff', 'staff@example.com', '$2y$10$54o.X3.YQZn//b4/LaV2Xu1NVk0FQRe1aexhaL1CqYPKX6CawfIbW', 'staff', '2026-09-23 10:02:13', '2026-09-23 17:02:13'),
(4, 'Staff Test', 'staff_1790183046', 'staff_1790183046@test.com', '$2y$10$F9u5bG4dq53/091ZhoDMqOHGulAWAjd3kexsrWOFEvNakxDLA1lvO', 'staff', '2026-09-23 10:04:06', '2026-09-23 17:04:06'),
(5, 'Staff Test', 'staff_505', 'staff_505@test.com', '$2y$10$GNSUCSocKC7YkZux4vtBmO9kQB8.D7U.gLWXrFS/ZdPiPVosLooSa', 'staff', '2026-09-23 10:04:48', '2026-09-23 17:04:48'),
(6, 'Staff Test', 'staff_266', 'staff_266@test.com', '$2y$10$.sAap3YgdXC9IneaTcMSVO1ekVxKpBYI28r0mzUXsCem8PQv8hRIi', 'staff', '2026-09-23 10:05:43', '2026-09-23 17:05:43'),
(8, 'Staff Uji Diupdate', 'staff_245', 'staff_245@test.com', '$2y$10$aI.b4fVQ6W9xvnY3rRzgsO4Bhx2brTGXiw8UGVUuS.g57asasklm6', 'staff', '2026-09-23 10:06:53', '2026-09-23 17:07:59'),
(9, 'Staff Uji Diupdate', 'staff_737', 'staff_737@test.com', '$2y$10$.wO.R9c.VY1HdgNyAUzDdeetBS7VtxiaIStKmtbWpvZMlHYtVHuOW', 'staff', '2026-09-23 10:08:12', '2026-09-23 17:08:13');

-- ------------------------------------------------------------------------------
-- 3. TABEL: `categories`
-- Fungsi: Menyimpan master kategori produk untuk taksonomi dan penyaringan stok
-- Penjelasan Kolom:
--   `id`            : Primary Key unik kategori (Auto Increment).
--   `nama_kategori` : Nama kelompok kategori barang (Unik).
--   `deskripsi`     : Keterangan cakupan barang pada kategori ini.
--   `created_at`    : Waktu kategori dibuat.
--   `updated_at`    : Waktu data kategori terakhir diperbarui.
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama_kategori` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping Data untuk Tabel `categories`
--
INSERT INTO `categories` (`id`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Elektronik', 'Perangkat komputer, laptop, monitor, dan gadget', '2026-09-23 12:14:44', '2026-09-23 12:14:44'),
(2, 'Aksesoris Komputer', 'Mouse, keyboard, kabel, dongle, dan peripheral', '2026-09-23 12:14:44', '2026-09-23 12:14:44'),
(3, 'Perabotan', 'Meja kantor, kursi ergonomis, dan lemari arsip', '2026-09-23 12:14:44', '2026-09-23 12:14:44'),
(4, 'Pakaian', 'Seragam kerja, jaket, dan pakaian staf', '2026-09-23 12:14:44', '2026-09-23 12:14:44'),
(5, 'Makanan & Minuman', 'Konsumsi kantor, snack, dan persediaan pantry', '2026-09-23 12:14:44', '2026-09-23 12:14:44'),
(6, 'Kesehatan & Kecantikan', 'P3K, sabun cuci tangan, dan sanitasi', '2026-09-23 12:14:44', '2026-09-23 12:14:44'),
(7, 'Alat Tulis', 'Kertas, pulpen, map, dan perlengkapan ATK', '2026-09-23 12:14:44', '2026-09-23 12:14:44'),
(8, 'Lainnya', 'Barang inventaris umum non-kategori khusus', '2026-09-23 12:14:44', '2026-09-23 12:14:44');

-- ------------------------------------------------------------------------------
-- 4. TABEL: `suppliers`
-- Fungsi: Menyimpan master data vendor dan pemasok barang logistik (Inbound tracking)
-- Penjelasan Kolom:
--   `id`            : Primary Key unik pemasok (Auto Increment).
--   `kode_supplier` : Kode unik identifikasi vendor (contoh: SPL-001).
--   `nama_supplier` : Nama resmi badan usaha / perusahaan pemasok.
--   `kontak_person` : Nama penanggung jawab (PIC) vendor.
--   `telepon`       : Nomor telepon resmi untuk pemesanan/pengadaan.
--   `email`         : Surel korespondensi PO / penawaran barang.
--   `alamat`        : Lokasi kantor atau gudang pusat vendor.
--   `created_at`    : Waktu pencatatan pemasok.
--   `updated_at`    : Waktu pembaruan profil pemasok.
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_supplier` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_supplier` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kontak_person` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_supplier` (`kode_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping Data untuk Tabel `suppliers`
--
INSERT INTO `suppliers` (`id`, `kode_supplier`, `nama_supplier`, `kontak_person`, `telepon`, `email`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'SPL-001', 'PT Mega Graha Distribusi', 'Bambang Sudirgo', '021-5558901', 'sales@megagraha.co.id', 'Kawasan Industri Pulogadung Blok B No. 12, Jakarta Timur', '2026-09-23 12:44:18', '2026-09-23 12:44:18'),
(2, 'SPL-002', 'CV Sumber Komputer Utama', 'Hendra Setiawan', '031-8976543', 'order@sumberkomputer.com', 'Jl. Raya Darmo No. 88, Surabaya', '2026-09-23 12:44:18', '2026-09-23 12:44:18'),
(3, 'SPL-003', 'PT Mitra Office Mandiri', 'Siti Nurhaliza', '022-7234567', 'supplier@mitraoffice.id', 'Jl. Soekarno Hatta No. 450, Bandung', '2026-09-23 12:44:18', '2026-09-23 12:44:18'),
(4, 'SPL-004', 'Grosir Aksesoris Nusantara', 'Rahmat Hidayat', '0812-9876-5432', 'info@aksesorisnusantara.com', 'Pusat Niaga Mangga Dua Lt. 3 No. 45, Jakarta Pusat', '2026-09-23 12:44:18', '2026-09-23 12:44:18');

-- ------------------------------------------------------------------------------
-- 5. TABEL: `customers`
-- Fungsi: Menyimpan master pelanggan eksternal atau departemen internal tujuan stok (Outbound tracking)
-- Penjelasan Kolom:
--   `id`             : Primary Key unik pelanggan (Auto Increment).
--   `kode_pelanggan` : Kode identitas unik (contoh: CST-001, DEPT-001).
--   `nama_pelanggan` : Nama entitas bisnis, individu, atau nama departemen internal.
--   `tipe`           : Klasifikasi tipe entitas ('BISNIS', 'INDIVIDUAL', 'DEPARTEMEN').
--   `kontak_person`  : Nama narahubung penerima barang.
--   `telepon`        : Nomor kontak aktif.
--   `email`          : Surel korespondensi pengiriman faktur/DO.
--   `alamat`         : Alamat kantor, toko, atau lokasi divisi penerima.
--   `created_at`     : Waktu pencatatan pelanggan.
--   `updated_at`     : Waktu pembaruan data pelanggan.
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_pelanggan` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pelanggan` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` enum('BISNIS','INDIVIDUAL','DEPARTEMEN') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BISNIS',
  `kontak_person` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_pelanggan` (`kode_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping Data untuk Tabel `customers`
--
INSERT INTO `customers` (`id`, `kode_pelanggan`, `nama_pelanggan`, `tipe`, `kontak_person`, `telepon`, `email`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'CST-001', 'PT Mitra Distribusi Ritel', 'BISNIS', 'Hendro Wijaya', '081211223344', 'hendro@mitradistribusi.co.id', 'Jl. Gatot Subroto No. 88, Jakarta Selatan', '2026-09-23 13:16:32', '2026-09-23 13:16:32'),
(2, 'CST-002', 'Toko Berkah Mandiri', 'BISNIS', 'Ibu Siti Aisyah', '081399887766', 'berkahmandiri@gmail.com', 'Ruko Sentra Niaga Blok B-12, Bekasi', '2026-09-23 13:16:32', '2026-09-23 13:16:32'),
(3, 'DEPT-001', 'Divisi Operasional & Gudang Cabang', 'DEPARTEMEN', 'Darmawan (SPV)', '081544332211', 'ops.internal@inventarispro.id', 'Internal Office Lt. 2 - Hub Logistik Barat', '2026-09-23 13:16:32', '2026-09-23 13:16:32'),
(4, 'DEPT-002', 'Divisi IT & Infrastruktur Jaringan', 'DEPARTEMEN', 'Fajar Nugraha', '081755667788', 'it.support@inventarispro.id', 'Head Office Lt. 4 - Ruang Server Utama', '2026-09-23 13:16:32', '2026-09-23 13:16:32'),
(5, 'CST-4227', 'PT Klien Outbound Uji Coba 918 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 13:23:03', '2026-09-23 13:23:04'),
(6, 'CST-5915', 'PT Klien Outbound Uji Coba 244 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 13:34:41', '2026-09-23 13:34:41'),
(7, 'CST-7048', 'PT Klien Outbound Uji Coba 710 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 13:41:53', '2026-09-23 13:41:54'),
(8, 'CST-9544', 'PT Klien Outbound Uji Coba 828 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 13:44:32', '2026-09-23 13:44:33'),
(9, 'CST-3675', 'PT Klien Outbound Uji Coba 746 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 13:50:15', '2026-09-23 13:50:16'),
(10, 'CST-5415', 'PT Klien Outbound Uji Coba 462 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 13:58:34', '2026-09-23 13:58:35'),
(12, 'CST-7617', 'PT Klien Outbound Uji Coba 437 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 14:09:23', '2026-09-23 14:09:23'),
(13, 'CST-9943', 'PT Klien Outbound Uji Coba 396 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 14:26:44', '2026-09-23 14:26:45'),
(14, 'CST-2180', 'PT Klien Outbound Uji Coba 443 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 14:35:09', '2026-09-23 14:35:10'),
(15, 'CST-1852', 'PT Klien Outbound Uji Coba 268 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 15:04:34', '2026-09-23 15:04:35'),
(16, 'CST-4523', 'PT Klien Outbound Uji Coba 657 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 17:02:38', '2026-09-23 17:02:39'),
(17, 'CST-8924', 'PT Klien Outbound Uji Coba 928 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 17:03:24', '2026-09-23 17:03:24'),
(18, 'CST-4697', 'PT Klien Outbound Uji Coba 977 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 17:06:26', '2026-09-23 17:06:27'),
(19, 'CST-7166', 'PT Klien Outbound Uji Coba 247 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 17:08:38', '2026-09-23 17:08:39'),
(20, 'CST-7505', 'PT Klien Outbound Uji Coba 658 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 17:14:23', '2026-09-23 17:14:23'),
(21, 'CST-6186', 'PT Klien Outbound Uji Coba 628 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-23 17:25:04', '2026-09-23 17:25:04'),
(22, 'CST-3560', 'PT Klien Outbound Uji Coba 856 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 00:34:33', '2026-09-24 00:34:33'),
(23, 'CST-4608', 'PT Klien Outbound Uji Coba 157 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 00:38:12', '2026-09-24 00:38:12'),
(24, 'CST-9576', 'PT Klien Outbound Uji Coba 367 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 01:22:33', '2026-09-24 01:22:34'),
(25, 'CST-4372', 'PT Klien Outbound Uji Coba 954 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 01:24:08', '2026-09-24 01:24:08'),
(26, 'CST-9391', 'PT Klien Outbound Uji Coba 827 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 01:27:11', '2026-09-24 01:27:12'),
(27, 'CST-8318', 'PT Klien Outbound Uji Coba 320 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 01:33:16', '2026-09-24 01:33:16'),
(28, 'CST-9330', 'PT Klien Outbound Uji Coba 470 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 01:37:41', '2026-09-24 01:37:41'),
(29, 'CST-8598', 'PT Klien Outbound Uji Coba 466 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 01:40:43', '2026-09-24 01:40:43'),
(30, 'CST-8839', 'PT Klien Outbound Uji Coba 534 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 01:45:35', '2026-09-24 01:45:35'),
(31, 'CST-8110', 'PT Klien Outbound Uji Coba 369 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 01:48:51', '2026-09-24 01:48:51'),
(32, 'CST-8600', 'PT Klien Outbound Uji Coba 635 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 01:55:11', '2026-09-24 01:55:12'),
(33, 'CST-3604', 'PT Klien Outbound Uji Coba 900 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 01:57:03', '2026-09-24 01:57:04'),
(34, 'CST-4944', 'PT Klien Outbound Uji Coba 223 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 02:00:12', '2026-09-24 02:00:12'),
(35, 'CST-1859', 'PT Klien Outbound Uji Coba 936 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 02:05:59', '2026-09-24 02:05:59'),
(36, 'CST-8471', 'PT Klien Outbound Uji Coba 617 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 02:11:29', '2026-09-24 02:11:30'),
(37, 'CST-9631', 'PT Klien Outbound Uji Coba 451 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 02:14:53', '2026-09-24 02:14:54'),
(38, 'CST-7393', 'PT Klien Outbound Uji Coba 119 (Terverifikasi)', 'BISNIS', 'Agus Pratama, S.E.', '081288776655', 'agus.procurement@klienoutbound.com', 'Kawasan Pergudangan Marunda Blok D-4 - Revisi', '2026-09-24 02:18:46', '2026-09-24 02:18:46');

-- ------------------------------------------------------------------------------
-- 6. TABEL: `products`
-- Fungsi: Menyimpan katalog barang inventaris, harga valuasi, dan level stok
-- Penjelasan Kolom:
--   `id`           : Primary Key unik produk (Auto Increment).
--   `user_id`      : Foreign Key ke `users.id` (pencatat data awal produk).
--   `supplier_id`  : Foreign Key ke `suppliers.id` (vendor utama/rekomendasi).
--   `kode_produk`  : Kode SKU barang unik (contoh: PRD-001).
--   `nama_produk`  : Nama lengkap spesifikasi produk/barang.
--   `kategori`     : Nama kategori produk terkait.
--   `harga`        : Harga satuan dalam format Rupiah (Desimal 12,2).
--   `stok`         : Kuantitas fisik stok barang yang tersedia saat ini.
--   `stok_minimum` : Ambang batas safety stock untuk pemicu alert restock otomatis.
--   `deskripsi`    : Catatan teknis, spesifikasi, dan kelengkapan barang.
--   `created_at`   : Timestamp waktu penambahan produk.
--   `updated_at`   : Waktu terakhir data produk diedit/stok termutasi.
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID unik produk',
  `user_id` int NOT NULL COMMENT 'ID pengguna pembuat produk (Relasi ke tabel users)',
  `supplier_id` int DEFAULT NULL,
  `kode_produk` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode unik/SKU produk',
  `nama_produk` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama barang atau produk',
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kategori produk',
  `harga` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Harga satuan produk dalam Rupiah',
  `stok` int NOT NULL DEFAULT '0' COMMENT 'Jumlah stok barang yang tersedia',
  `stok_minimum` int DEFAULT '5',
  `deskripsi` text COLLATE utf8mb4_unicode_ci COMMENT 'Catatan/deskripsi detail mengenai produk',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu penambahan produk',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu terakhir data diedit',
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_produk` (`kode_produk`),
  KEY `fk_products_users` (`user_id`),
  KEY `fk_products_supplier_id` (`supplier_id`),
  KEY `idx_products_kategori` (`kategori`),
  KEY `idx_products_stok_min` (`stok`,`stok_minimum`),
  CONSTRAINT `fk_products_supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_products_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping Data untuk Tabel `products`
--
INSERT INTO `products` (`id`, `user_id`, `supplier_id`, `kode_produk`, `nama_produk`, `kategori`, `harga`, `stok`, `stok_minimum`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'PRD-001', 'Laptop Asus Zenbook 14 OLED', 'Elektronik', 15499000.00, 12, 5, 'Laptop ultra tipis layar OLED 2.8K dengan prosesor Intel Core i7.', '2026-09-23 07:45:33', '2026-09-23 07:45:33'),
(2, 1, NULL, 'PRD-002', 'Mouse Wireless Logitech MX Master 3S', 'Aksesoris Komputer', 1650000.00, 25, 5, 'Mouse ergonomis nirkabel dengan sensor 8000 DPI yang hening.', '2026-09-23 07:45:33', '2026-09-23 07:45:33'),
(3, 1, NULL, 'PRD-003', 'Keyboard Mechanical Keychron K2 V2', 'Aksesoris Komputer', 1250000.00, 4, 5, 'Keyboard mekanikal wireless 75% layout dengan Gateron Brown switch.', '2026-09-23 07:45:33', '2026-09-23 07:45:33'),
(4, 1, NULL, 'PRD-004', 'Monitor Gaming LG UltraGear 27 Inch', 'Elektronik', 3800000.00, 0, 5, 'Monitor gaming IPS 144Hz 1ms dengan dukungan G-Sync Compatible.', '2026-09-23 07:45:33', '2026-09-23 07:45:33'),
(5, 1, NULL, 'PRD-005', 'Meja Kerja Minimalis Ergonomis', 'Perabotan', 850000.00, 13, 5, 'Meja kerja kayu solid dengan rangka baja kokoh dan lubang kabel rapi.', '2026-09-23 07:45:33', '2026-09-23 12:30:57');

-- ------------------------------------------------------------------------------
-- 7. TABEL: `stock_movements`
-- Fungsi: Audit trail & log mutasi stok transaksi barang (Masuk, Keluar, Opname)
-- Penjelasan Kolom:
--   `id`           : Primary Key unik pencatatan mutasi (Auto Increment).
--   `product_id`   : Foreign Key ke `products.id` barang yang dimutasi.
--   `user_id`      : Foreign Key ke `users.id` staf/admin penanggung jawab aksi.
--   `supplier_id`  : Foreign Key ke `suppliers.id` (jika jenis mutasi IN / penerimaan vendor).
--   `customer_id`  : Foreign Key ke `customers.id` (jika jenis mutasi OUT / pengeluaran klien).
--   `tipe`         : Jenis transaksi logistik ('IN' = masuk, 'OUT' = keluar, 'ADJUSTMENT' = opname).
--   `jumlah`       : Jumlah kuantitas unit yang dimutasi.
--   `stok_sebelum` : Snapshot jumlah saldo fisik sebelum transaksi dieksekusi.
--   `stok_sesudah` : Snapshot jumlah saldo fisik setelah transaksi berhasil (Strict Audit Trail).
--   `keterangan`   : Berita acara, nomor dokumen PO/DO, atau alasan penyesuaian opname.
--   `created_at`   : Timestamp waktu transaksi dicatat di database.
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `stock_movements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `tipe` enum('IN','OUT','ADJUSTMENT') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IN',
  `jumlah` int NOT NULL DEFAULT '1',
  `stok_sebelum` int NOT NULL DEFAULT '0',
  `stok_sesudah` int NOT NULL DEFAULT '0',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_movements_created_tipe` (`created_at`,`tipe`),
  KEY `idx_movements_supplier_id` (`supplier_id`),
  KEY `idx_movements_customer_id` (`customer_id`),
  CONSTRAINT `fk_stock_movements_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_stock_movements_supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping Data untuk Tabel `stock_movements`
--
INSERT INTO `stock_movements` (`id`, `product_id`, `user_id`, `supplier_id`, `customer_id`, `tipe`, `jumlah`, `stok_sebelum`, `stok_sesudah`, `keterangan`, `created_at`) VALUES
(13, 5, 1, NULL, NULL, 'OUT', 5, 18, 13, NULL, '2026-09-23 12:30:57');

-- ------------------------------------------------------------------------------
-- 8. TABEL: `migrations`
-- Fungsi: Melacak versi migrasi skema database framework CodeIgniter 4
-- Penjelasan Kolom:
--   `id`        : Primary Key unik riwayat migrasi.
--   `version`   : Tanggal & stempel versi migrasi (format YYYY-MM-DD-HHIISS).
--   `class`     : Nama kelas migrasi terdaftar di namespace aplikasi.
--   `group`     : Database connection group (default: 'default').
--   `namespace` : Namespace module aplikasi ('App').
--   `time`      : Unix timestamp saat migrasi dijalankan.
--   `batch`     : Nomor kelompok eksekusi batch `php spark migrate`.
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping Data untuk Tabel `migrations`
-- Seluruh 12 Migrasi Resmi InventarisPro (Batch 1 - 8)
--
INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-09-23-000001', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1790164684, 1),
(2, '2026-09-23-000002', 'App\\Database\\Migrations\\CreateProductsTable', 'default', 'App', 1790164684, 1),
(3, '2026-09-23-000003', 'App\\Database\\Migrations\\CreateCategoriesTable', 'default', 'App', 1790165674, 2),
(4, '2026-09-23-000004', 'App\\Database\\Migrations\\CreateStockMovementsTable', 'default', 'App', 1790166207, 3),
(5, '2026-09-23-000005', 'App\\Database\\Migrations\\AddMinStockToProductsTable', 'default', 'App', 1790166851, 4),
(6, '2026-09-23-000006', 'App\\Database\\Migrations\\CreateSuppliersTable', 'default', 'App', 1790167414, 5),
(7, '2026-09-23-000007', 'App\\Database\\Migrations\\AddSupplierIdToProductsAndMovements', 'default', 'App', 1790167414, 5),
(8, '2026-09-23-000008', 'App\\Database\\Migrations\\CreateCustomersTable', 'default', 'App', 1790169365, 6),
(9, '2026-09-23-000009', 'App\\Database\\Migrations\\AddCustomerIdToStockMovements', 'default', 'App', 1790169365, 6),
(10, '2026-09-23-000010', 'App\\Database\\Migrations\\AddPerformanceIndexes', 'default', 'App', 1790172449, 7),
(11, '2026-09-23-000011', 'App\\Database\\Migrations\\AddRoleToUsersTable', 'default', 'App', 1790172449, 7),
(12, '2026-09-23-000012', 'App\\Database\\Migrations\\AddUpdatedAtToUsersTable', 'default', 'App', 1790182911, 8),
(13, '2026-09-24-000013', 'App\\Database\\Migrations\\CreateActivityLogsTable', 'default', 'App', 1790217409, 9);

-- ------------------------------------------------------------------------------
-- 9. TABEL: `activity_logs`
-- Fungsi: Audit trail & log aktivitas menyeluruh (Pemasok, Pelanggan, Produk, Kategori, User, Auth)
-- Penjelasan Kolom:
--   `id`          : Primary Key unik log aktivitas (BigInt Auto Increment).
--   `user_id`     : Foreign Key ke `users.id` operator pelaksana (bisa NULL jika akun dihapus).
--   `user_name`   : Snapshot nama lengkap pengguna saat melakukan aksi.
--   `user_role`   : Snapshot peran otorisasi ('admin' atau 'staff').
--   `module`      : Modul sistem terkait ('SUPPLIER', 'CUSTOMER', 'PRODUCT', 'CATEGORY', 'STOCK', 'USER', 'AUTH').
--   `action`      : Jenis operasi ('CREATE', 'UPDATE', 'DELETE', 'STOCK_IN', 'STOCK_OUT', 'STOCK_ADJUSTMENT', 'LOGIN', 'LOGOUT').
--   `record_id`   : ID unik entitas data yang dimanipulasi/dimutasi.
--   `item_name`   : Nama/kode identitas objek yang terlibat.
--   `description` : Deskripsi naratif lengkap dari perubahan yang dilakukan.
--   `details`     : Snapshot JSON perbandingan nilai sebelum & sesudah (Field Diff).
--   `ip_address`  : Alamat IP jaringan perangkat operator.
--   `user_agent`  : Info browser & perangkat lunak klien.
--   `created_at`  : Timestamp audit trail yang tidak dapat dimanipulasi.
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `user_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `module` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`),
  KEY `module` (`module`),
  KEY `action` (`action`),
  KEY `user_id` (`user_id`),
  KEY `module_action_created_at` (`module`,`action`,`created_at`),
  CONSTRAINT `fk_activity_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 10. RESET AUTO_INCREMENT & COMMIT TRANSAKSI
-- Menyesuaikan penomoran auto-increment sesuai data terkini
-- ------------------------------------------------------------------------------
ALTER TABLE `categories` AUTO_INCREMENT = 52;
ALTER TABLE `customers` AUTO_INCREMENT = 39;
ALTER TABLE `migrations` AUTO_INCREMENT = 14;
ALTER TABLE `products` AUTO_INCREMENT = 203;
ALTER TABLE `stock_movements` AUTO_INCREMENT = 232;
ALTER TABLE `suppliers` AUTO_INCREMENT = 45;
ALTER TABLE `users` AUTO_INCREMENT = 30;
ALTER TABLE `activity_logs` AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
