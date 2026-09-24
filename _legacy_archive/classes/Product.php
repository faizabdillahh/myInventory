<?php
/**
 * ==============================================================================
 * FILE: classes/Product.php
 * DESKRIPSI: Model Produk (Product Entity & CRUD Operations)
 * ==============================================================================
 * Kelas ini membungkus semua operasi database terkait entitas produk:
 * 1. Mengambil seluruh data produk beserta filter pencarian (READ).
 * 2. Menambahkan produk baru dengan validasi SKU (CREATE).
 * 3. Memperbarui informasi produk (UPDATE).
 * 4. Menghapus produk dari inventaris (DELETE).
 * 5. Menghitung ringkasan statistik inventaris untuk widget dashboard.
 */

class Product {
    /**
     * Instance koneksi PDO database
     * @var PDO
     */
    private PDO $db;

    /**
     * Konstruktor kelas Product
     *
     * @param PDO $pdo Instance koneksi database aktif
     */
    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    /**
     * Mengambil daftar produk dari database dengan dukungan pencarian kata kunci.
     *
     * @param string $search Kata kunci pencarian (berdasarkan kode, nama, atau kategori)
     * @return array Kumpulan baris data produk
     */
    public function all(string $search = ''): array {
        $sql = "
            SELECT 
                p.*, 
                u.nama_lengkap AS pembuat 
            FROM products p 
            LEFT JOIN users u ON p.user_id = u.id 
        ";

        // Tambahkan filter jika terdapat kata kunci pencarian
        if (!empty($search)) {
            $sql .= " WHERE p.kode_produk LIKE :q1 OR p.nama_produk LIKE :q2 OR p.kategori LIKE :q3 ";
        }

        $sql .= " ORDER BY p.id DESC";

        $stmt = $this->db->prepare($sql);

        if (!empty($search)) {
            $paramLike = "%{$search}%";
            $stmt->execute([
                ':q1' => $paramLike,
                ':q2' => $paramLike,
                ':q3' => $paramLike
            ]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }

    /**
     * Mencari satu data produk berdasarkan ID uniknya.
     *
     * @param int $id ID produk yang dicari
     * @return array|null Data produk jika ditemukan, atau null
     */
    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Memeriksa apakah kode produk (SKU) sudah digunakan oleh barang lain.
     *
     * @param string $sku Kode produk yang akan divalidasi
     * @param int|null $excludeId ID produk yang dikecualikan (berguna saat proses Edit)
     * @return bool True jika kode sudah ada yang pakai, False jika aman
     */
    public function isSkuTaken(string $sku, ?int $excludeId = null): bool {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare("SELECT id FROM products WHERE kode_produk = :sku AND id != :exclude_id LIMIT 1");
            $stmt->execute([
                ':sku'        => $sku,
                ':exclude_id' => $excludeId
            ]);
        } else {
            $stmt = $this->db->prepare("SELECT id FROM products WHERE kode_produk = :sku LIMIT 1");
            $stmt->execute([':sku' => $sku]);
        }

        return $stmt->rowCount() > 0;
    }

    /**
     * Menambahkan produk baru ke dalam tabel products.
     *
     * @param array $data Data produk (user_id, kode_produk, nama_produk, kategori, harga, stok, deskripsi)
     * @return bool True jika penyimpanan berhasil
     */
    public function create(array $data): bool {
        $stmt = $this->db->prepare("
            INSERT INTO products (user_id, kode_produk, nama_produk, kategori, harga, stok, deskripsi)
            VALUES (:user_id, :kode_produk, :nama_produk, :kategori, :harga, :stok, :deskripsi)
        ");

        return $stmt->execute([
            ':user_id'     => $data['user_id'],
            ':kode_produk' => strtoupper(trim($data['kode_produk'])),
            ':nama_produk' => trim($data['nama_produk']),
            ':kategori'    => trim($data['kategori']),
            ':harga'       => (float)$data['harga'],
            ':stok'        => (int)$data['stok'],
            ':deskripsi'   => !empty($data['deskripsi']) ? trim($data['deskripsi']) : null,
        ]);
    }

    /**
     * Memperbarui data produk yang sudah ada di database.
     *
     * @param int $id ID produk yang akan diperbarui
     * @param array $data Nilai data baru produk
     * @return bool True jika pembaruan berhasil
     */
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE products 
            SET 
                kode_produk = :kode_produk,
                nama_produk = :nama_produk,
                kategori    = :kategori,
                harga       = :harga,
                stok        = :stok,
                deskripsi   = :deskripsi
            WHERE id = :id
        ");

        return $stmt->execute([
            ':kode_produk' => strtoupper(trim($data['kode_produk'])),
            ':nama_produk' => trim($data['nama_produk']),
            ':kategori'    => trim($data['kategori']),
            ':harga'       => (float)$data['harga'],
            ':stok'        => (int)$data['stok'],
            ':deskripsi'   => !empty($data['deskripsi']) ? trim($data['deskripsi']) : null,
            ':id'          => $id
        ]);
    }

    /**
     * Menghapus data produk berdasarkan ID.
     *
     * @param int $id ID produk yang akan dihapus
     * @return bool True jika berhasil dihapus
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Menghitung statistik ringkasan inventaris untuk ditampilkan di dashboard.
     *
     * @return array Asosiasi statistik: total_products, total_stock, low_stock, total_asset
     */
    public function getSummaryStats(): array {
        // Query agregasi dalam satu panggilan efisien
        $sql = "
            SELECT 
                COUNT(*) AS total_products,
                COALESCE(SUM(stok), 0) AS total_stock,
                COALESCE(SUM(CASE WHEN stok <= 5 THEN 1 ELSE 0 END), 0) AS low_stock,
                COALESCE(SUM(harga * stok), 0) AS total_asset
            FROM products
        ";

        return $this->db->query($sql)->fetch() ?: [
            'total_products' => 0,
            'total_stock'    => 0,
            'low_stock'      => 0,
            'total_asset'    => 0
        ];
    }
}
