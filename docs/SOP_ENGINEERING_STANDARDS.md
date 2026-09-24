# Standard Operating Procedure (SOP): Engineering Standards & Clean Code

Dokumen ini adalah pedoman baku dan standar kualitas rekayasa perangkat lunak (*software engineering standards*) untuk proyek **Web Inventory**. Setiap pengembang (manusia maupun asisten AI) wajib mematuhi seluruh ketentuan di dalam dokumen ini.

---

## 1. Prinsip Fundamental (Core Philosophy)

1. **Clean Code Over Clever Code**: Tulis kode yang mudah dibaca oleh manusia lain 6 bulan ke depan, bukan kode rumit yang hanya dimengerti pembuatnya.
2. **SOLID Principles**:
   - **S (Single Responsibility)**: Satu kelas atau fungsi hanya boleh memiliki satu tugas dan satu alasan untuk berubah.
   - **O (Open/Closed)**: Terbuka untuk ekstensi, tertutup untuk modifikasi langsung kode inti.
   - **L (Liskov Substitution)**: Subkelas atau implementasi interface harus dapat menggantikan kelas induknya tanpa merusak fungsionalitas.
   - **I (Interface Segregation)**: Interface harus spesifik dan ramping, jangan memaksa kelas mengimplementasikan metode yang tidak digunakan.
   - **D (Dependency Inversion)**: Bergantunglah pada abstraksi (Interface/Contracts), bukan pada implementasi konkret.
3. **DRY (Don't Repeat Yourself)**: Ekstraksi logika berulang menjadi fungsi helper, trait, atau service.
4. **KISS (Keep It Simple, Stupid)** & **YAGNI (You Aren't Gonna Need It)**: Hindari over-engineering sebelum kebutuhan bisnis benar-benar menuntutnya.

---

## 2. Aturan Penamaan (Naming Conventions)

| Elemen | Format | Contoh Baik | Contoh Buruk |
|---|---|---|---|
| **Kelas & Interface** | `PascalCase` | `ProductService`, `UserRepositoryInterface` | `product_service`, `userRepo` |
| **Method / Fungsi** | `camelCase` | `getProductsWithUser()`, `calculateTotalAsset()` | `get_products()`, `CalculateAsset()` |
| **Variabel & Properti** | `camelCase` | `$totalStock`, `$searchKeyword` | `$total_stock`, `$x`, `$data1` |
| **Konstanta / Enum** | `UPPER_SNAKE_CASE` | `STATUS_ACTIVE`, `DEFAULT_PAGE_SIZE` | `StatusActive`, `pageSize` |
| **Tabel Database** | `snake_case` (jamak) | `products`, `users`, `audit_logs` | `tbl_product`, `UserTable` |
| **Kolom Database** | `snake_case` (tunggal) | `kode_produk`, `nama_produk`, `created_at` | `KodeProduk`, `prod_nm` |
| **Rute URL Web** | `kebab-case` | `/products/stock-in`, `/audit-logs` | `/products/stockIn`, `/audit_logs` |
| **File View** | `snake_case` | `index.php`, `stock_movement.php` | `IndexView.php`, `stockMovement.php` |

---

## 3. Standar Struktur & Kualitas Kode (Code Quality)

### A. Pembatasan Ukuran & Kompleksitas
- **Panjang Method**: Maksimal **30-40 baris kode**. Jika lebih, pecah menjadi *private helper methods*.
- **Kedalaman Indentasi**: Maksimal **3 tingkat indentasi** (hindari nested `if/else` yang dalam; gunakan *Guard Clauses* atau *Early Returns*).

```php
// ❌ BURUK: Nested conditionals (Deep nesting)
public function processOrder($order) {
    if ($order !== null) {
        if ($order->isValid()) {
            if ($order->hasStock()) {
                // logika...
            }
        }
    }
}

// ✅ BAIK: Early returns (Guard Clauses)
public function processOrder($order) {
    if ($order === null || !$order->isValid()) {
        return false;
    }
    if (!$order->hasStock()) {
        return false;
    }
    // logika inti di baris utama...
    return true;
}
```

### B. Type Safety (PHP 8.3+)
- Wajib mendeklarasikan tipe data parameter dan return type pada setiap fungsi/metode.
- Manfaatkan fitur modern PHP 8.3: `readonly` properties, Union Types, dan Typed Properties.

```php
public function updateStock(int $productId, int $quantity, string $reason): bool
```

---

## 4. Standar Penanganan Error & Logging

1. **Dilarang Mengabaikan Error (*No Empty Catch Blocks*)**:
   ```php
   // ❌ DILARANG KERAS
   try { ... } catch (Exception $e) {}

   // ✅ WAJIB: Catat log dan berikan respon yang aman
   try {
       ...
   } catch (\Throwable $e) {
       log_message('error', '[ProductService::create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
       throw new BusinessException('Gagal memproses penambahan produk.');
   }
   ```
2. **Sanitasi Pesan Error Pengguna**:
   - Jangan pernah menampilkan pesan error SQL mentah (*raw database exception*) ke antarmuka pengguna pada lingkungan produksi (*production*).
   - Tampilkan pesan ramah pengguna: *"Terjadi kesalahan saat memproses data. Silakan coba beberapa saat lagi."*

---

## 5. Standar Keamanan Enterprise (Security Hardening)

1. **Cross-Site Scripting (XSS)**:
   - Setiap output variabel ke HTML **wajib** menggunakan fungsi `esc()` bawaan CodeIgniter 4.
   - Contoh: `<?= esc($item['nama_produk']) ?>`.
2. **SQL Injection**:
   - Dilarang merangkai kueri SQL menggunakan konkatenasi string mentah (`$db->query("SELECT * WHERE id = " . $id)`).
   - Wajib menggunakan **Query Builder** atau **Prepared Statements** dengan parameter binding.
3. **Cross-Site Request Forgery (CSRF)**:
   - Setiap formulir yang mengubah data (`POST`, `PUT`, `DELETE`) **wajib** menyertakan `<?= csrf_field() ?>`.
4. **Keamanan Kata Sandi**:
   - Kata sandi wajib di-hash menggunakan algoritma Bcrypt atau Argon2id (`password_hash($pass, PASSWORD_BCRYPT)`).
   - Dilarang menyimpan atau mencatat *plain-text password* di log aplikasi.

---

## 6. Standar Transaksi Database (ACID Compliance)

Setiap operasi yang mengubah lebih dari satu tabel atau mengubah stok inventaris **wajib** dibungkus di dalam transaksi database atomik:

```php
$db = \Config\Database::connect();
$db->transBegin();

try {
    // 1. Kurangi stok produk
    // 2. Tambahkan riwayat mutasi stok
    // 3. Catat audit trail

    $db->transCommit();
} catch (\Throwable $e) {
    $db->transRollback();
    log_message('error', 'Transaksi gagal: ' . $e->getMessage());
    throw $e;
}
```

---

## 7. Checklist Review Sebelum Rilis Fitur Baru
- [ ] Apakah seluruh input telah divalidasi dengan aturan formal (*validation rules*)?
- [ ] Apakah output ke view telah di-escape dengan `esc()`?
- [ ] Apakah fungsi memiliki type-hinting lengkap?
- [ ] Apakah ada file migrasi database untuk perubahan skema?
- [ ] Apakah sudah diuji di web server Laragon dan tidak memunculkan error PHP?
