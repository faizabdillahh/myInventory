<?php
/**
 * ==============================================================================
 * FILE: classes/User.php
 * DESKRIPSI: Model Pengguna (User Entity & Authentication Business Logic)
 * ==============================================================================
 * Kelas ini membungkus semua operasi database terkait entitas pengguna:
 * 1. Pengecekan autentikasi akun login.
 * 2. Pendaftaran akun baru dengan enkripsi Bcrypt.
 * 3. Validasi keunikan username dan email.
 */

class User {
    /**
     * Instance koneksi PDO database
     * @var PDO
     */
    private PDO $db;

    /**
     * Konstruktor kelas User
     *
     * @param PDO $pdo Instance koneksi database aktif
     */
    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    /**
     * Memeriksa kredensial login pengguna berdasarkan username/email dan password.
     *
     * @param string $identity Username atau email yang diinputkan pengguna
     * @param string $password Password dalam format teks biasa (plain text)
     * @return array|false Data pengguna jika valid, atau false jika kredensial salah
     */
    public function login(string $identity, string $password) {
        // Query mencari akun user dengan prepared statement terpisah (:user & :email)
        $stmt = $this->db->prepare("
            SELECT id, nama_lengkap, username, email, password 
            FROM users 
            WHERE username = :user OR email = :email 
            LIMIT 1
        ");

        $stmt->execute([
            ':user'  => $identity,
            ':email' => $identity
        ]);

        $user = $stmt->fetch();

        // Verifikasi password menggunakan hash Bcrypt yang tersimpan
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Memeriksa apakah username atau alamat email sudah pernah terdaftar di sistem.
     *
     * @param string $username Username yang akan dicek
     * @param string $email Email yang akan dicek
     * @return bool True jika sudah ada yang memakai, False jika masih tersedia
     */
    public function isUsernameOrEmailTaken(string $username, string $email): bool {
        $stmt = $this->db->prepare("
            SELECT id 
            FROM users 
            WHERE username = :username OR email = :email 
            LIMIT 1
        ");

        $stmt->execute([
            ':username' => $username,
            ':email'    => $email
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Mendaftarkan akun pengguna baru ke database.
     *
     * @param string $namaLengkap Nama asli pengguna
     * @param string $username Username unik
     * @param string $email Alamat email valid dan unik
     * @param string $password Kata sandi polos (akan otomatis di-hash Bcrypt)
     * @return bool True jika pendaftaran berhasil
     */
    public function register(string $namaLengkap, string $username, string $email, string $password): bool {
        // Enkripsi kata sandi dengan algoritma Bcrypt bawaan PHP
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("
            INSERT INTO users (nama_lengkap, username, email, password) 
            VALUES (:nama_lengkap, :username, :email, :password)
        ");

        return $stmt->execute([
            ':nama_lengkap' => $namaLengkap,
            ':username'     => $username,
            ':email'        => $email,
            ':password'     => $hashedPassword
        ]);
    }

    /**
     * Mengambil profil pengguna berdasarkan ID unik.
     *
     * @param int $id ID pengguna
     * @return array|null Data pengguna atau null jika tidak ditemukan
     */
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT id, nama_lengkap, username, email, created_at 
            FROM users 
            WHERE id = :id 
            LIMIT 1
        ");

        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }
}
