<?php
/**
 * ==============================================================================
 * FILE: config/database.php
 * DESKRIPSI: Konfigurasi Koneksi Database MySQL (Sesuai Bawaan Default Laragon)
 * ==============================================================================
 * Pengaturan standar Laragon:
 * - Host     : localhost
 * - Port     : 3306
 * - User     : root
 * - Password : "" (kosong tanpa password secara bawaan)
 * - Database : db_crud_app
 * 
 * Menggunakan PDO (PHP Data Objects) dengan Prepared Statements untuk keamanan
 * dari serangan SQL Injection.
 */

// 1. Parameter koneksi default bawaan Laragon
$db_host = 'localhost';
$db_port = '3306';
$db_name = 'db_crud_app';
$db_user = 'root';
$db_pass = '';

// 2. Pengaturan opsi PDO
$options = [
    // Melemparkan exception jika terjadi kesalahan query
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    // Mengembalikan data sebagai array asosiatif
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // Mengaktifkan emulasi prepared statements bawaan PHP untuk kompatibilitas penuh MySQL
    PDO::ATTR_EMULATE_PREPARES   => true,
];

try {
    // 3. Mencoba menghubungkan langsung ke database aplikasi
    $dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4";
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);

} catch (PDOException $e) {
    // 4. Jika database 'db_crud_app' belum ada di Laragon, buat secara otomatis
    try {
        // Terhubung ke server MySQL tanpa memilih database
        $root_pdo = new PDO("mysql:host={$db_host};port={$db_port};charset=utf8mb4", $db_user, $db_pass, $options);
        
        // Buat database db_crud_app sesuai charset default Laragon
        $root_pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        
        // Hubungkan kembali ke database yang baru dibuat
        $pdo = new PDO("mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4", $db_user, $db_pass, $options);
        
        // Jalankan struktur tabel dari database.sql otomatis
        $sql_file = __DIR__ . '/../database.sql';
        if (file_exists($sql_file)) {
            $sql_content = file_get_contents($sql_file);
            $pdo->exec($sql_content);
        }
    } catch (PDOException $ex) {
        // Hentikan eksekusi dan beritahu pesan error jika MySQL Laragon belum di-start
        die("Koneksi ke MySQL Laragon gagal: " . htmlspecialchars($ex->getMessage()));
    }
}
