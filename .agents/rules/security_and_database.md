# AI Rule: Security & Database Best Practices

> Aturan ini wajib dipatuhi oleh Agen AI untuk menjaga integritas data dan keamanan aplikasi.

---

## 1. Keamanan Aplikasi (Application Security)

1. **Proteksi XSS**:
   - Setiap output variabel ke HTML WAJIB dibungkus `esc()`.
   - Contoh: `<?= esc($product['nama_produk']) ?>`.
2. **Proteksi CSRF**:
   - Setiap form HTML dengan method `POST`, `PUT`, `DELETE` WAJIB menyertakan `<?= csrf_field() ?>`.
3. **Proteksi SQL Injection**:
   - Gunakan selalu Query Builder CodeIgniter 4 atau Prepared Statements.
   - Jangan pernah menyambung query string mentah dengan variabel user (`$db->query("... " . $input)`).
4. **Proteksi Akses Halaman (Auth Filters)**:
   - Daftarkan route baru di `app/Config/Routes.php` ke dalam route group dengan filter `auth` jika halaman tersebut memerlukan login.
5. **Enkripsi Kata Sandi**:
   - Selalu gunakan `password_hash($pass, PASSWORD_BCRYPT)` untuk menyimpan password dan `password_verify($pass, $hash)` untuk autentikasi.

---

## 2. Standar Database & Transaksi

1. **Database Migrations Wajib**:
   - Dilarang membuat tabel secara manual di phpMyAdmin.
   - Selalu buat migration via `app/Database/Migrations/` dengan tipe data kolom yang tepat, constraint unik, dan indeks foreign key.
2. **Transaksi Database Atomik (ACID)**:
   - Setiap operasi yang memodifikasi lebih dari satu baris/tabel atau memutasikan stok barang wajib dibungkus dalam blok transaksi:
     ```php
     $db = \Config\Database::connect();
     $db->transBegin();
     try {
         // operasi 1
         // operasi 2
         $db->transCommit();
     } catch (\Throwable $e) {
         $db->transRollback();
         throw $e;
     }
     ```
3. **Indeks & Kunci Asing (Foreign Keys)**:
   - Setiap foreign key wajib memiliki constraint `ON DELETE CASCADE` atau `ON DELETE RESTRICT` sesuai relasi bisnisnya.
