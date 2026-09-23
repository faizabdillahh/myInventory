-- ==============================================================================
-- phpMyAdmin SQL Dump Format
-- Database: `db_crud_app`
-- Kompatibel penuh dengan bawaan Laragon (MySQL 8.x / MariaDB & phpMyAdmin)
-- ==============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 1. PEMBUATAN DATABASE
-- Membuat database db_crud_app jika belum ada di server MySQL bawaan Laragon
--
CREATE DATABASE IF NOT EXISTS `db_crud_app` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_crud_app`;

-- --------------------------------------------------------

--
-- 2. STRUKTUR TABEL `users` (Tabel Pengguna untuk Autentikasi Login & Register)
-- Penjelasan Kolom:
--   `id`           : Primary key unik, auto-increment (nomor ID pengguna).
--   `nama_lengkap` : Nama lengkap user untuk ditampilkan pada sistem.
--   `username`     : Username unik untuk identifikasi saat login.
--   `email`        : Alamat email unik pengguna.
--   `password`     : Hash Bcrypt aman untuk kata sandi akun.
--   `created_at`   : Timestamp waktu akun dibuat.
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`),
  UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- DATA AWAL TABEL `users` (Akun Admin Default)
-- Username : admin
-- Password : admin123 (Terenkripsi dengan Bcrypt)
--
INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Administrator Sistem', 'admin', 'admin@example.com', '$2y$10$2X5n1yfWsl37LwTNGXLXUeowNa.8aQJPORJStAMO9GA1gwfkdAWoe', CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE `id` = `id`;

-- --------------------------------------------------------

--
-- 3. STRUKTUR TABEL `products` (Tabel Data Produk untuk Fitur CRUD)
-- Penjelasan Kolom:
--   `id`          : Primary key unik, auto-increment produk.
--   `user_id`     : Foreign key merujuk ke id pada tabel users (audit pembuat data).
--   `kode_produk` : Kode SKU barang unik (contoh: PRD-001).
--   `nama_produk` : Nama lengkap barang/produk.
--   `kategori`    : Kategori klasifikasi barang.
--   `harga`       : Nilai harga satuan (desimal format Rupiah).
--   `stok`        : Jumlah unit stok fisik yang tersedia.
--   `deskripsi`   : Deskripsi detail / spesifikasi produk (opsional).
--   `created_at`  : Waktu penambahan data produk.
--   `updated_at`  : Waktu pengubahan data produk terakhir.
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `kode_produk` varchar(30) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT '0.00',
  `stok` int NOT NULL DEFAULT '0',
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_kode_produk` (`kode_produk`),
  KEY `fk_products_users_idx` (`user_id`),
  CONSTRAINT `fk_products_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- DATA AWAL TABEL `products` (Data Dummy Awal)
--
INSERT INTO `products` (`id`, `user_id`, `kode_produk`, `nama_produk`, `kategori`, `harga`, `stok`, `deskripsi`, `created_at`) VALUES
(1, 1, 'PRD-001', 'Laptop Asus Zenbook 14 OLED', 'Elektronik', 15499000.00, 12, 'Laptop ultra tipis layar OLED 2.8K dengan prosesor Intel Core i7.', CURRENT_TIMESTAMP),
(2, 1, 'PRD-002', 'Mouse Wireless Logitech MX Master 3S', 'Aksesoris Komputer', 1650000.00, 25, 'Mouse ergonomis nirkabel dengan sensor 8000 DPI yang hening.', CURRENT_TIMESTAMP),
(3, 1, 'PRD-003', 'Keyboard Mechanical Keychron K2 V2', 'Aksesoris Komputer', 1250000.00, 4, 'Keyboard mekanikal wireless 75% layout dengan Gateron Brown switch.', CURRENT_TIMESTAMP),
(4, 1, 'PRD-004', 'Monitor Gaming LG UltraGear 27 Inch', 'Elektronik', 3800000.00, 0, 'Monitor gaming IPS 144Hz 1ms dengan dukungan G-Sync Compatible.', CURRENT_TIMESTAMP),
(5, 1, 'PRD-005', 'Meja Kerja Minimalis Ergonomis', 'Perabotan', 850000.00, 18, 'Meja kerja kayu solid dengan rangka baja kokoh dan lubang kabel rapi.', CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE `id` = `id`;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
