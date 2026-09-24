# AI Rule: Layered Architecture (Service-Repository Pattern)

> Aturan ini wajib dipatuhi oleh Agen AI agar arsitektur aplikasi tetap scalable dan terstruktur.

---

## 1. Pemisahan Tanggung Jawab Antar Lapisan

Setiap fitur baru harus mengikuti pemisahan 4 lapis:

### A. Controllers (`app/Controllers/`)
- **HANYA bertugas**:
  1. Menerima input HTTP via `$this->request`.
  2. Memeriksa validasi form dasar.
  3. Memasukkan data ke dalam DTO.
  4. Memanggil method pada Service Layer.
  5. Mengembalikan respon (`view()`, `redirect()`, atau `response()->setJSON()`).
- **DILARANG**:
  - Menulis query database (`$db->table(...)`, `$this->model->where(...)`).
  - Menulis kalkulasi bisnis atau manipulasi database bertransaksi.

### B. Services (`app/Services/`)
- **Bertugas**:
  1. Membungkus aturan bisnis (*business rules*).
  2. Mengatur transaksi database (`$this->transaction(...)`).
  3. Memanggil satu atau beberapa Repository.
  4. Mencatat log audit aktivitas inventaris.
  5. Melempar exception jika terjadi pelanggaran aturan bisnis (misal: "Stok fisik tidak mencukupi untuk dikeluarkan").

### C. Repositories (`app/Repositories/`)
- **Bertugas**:
  1. Menampung seluruh operasi database (SELECT, INSERT, UPDATE, DELETE).
  2. Mengimplementasikan Interface dari `app/Repositories/Contracts/`.
  3. Mengembalikan data dalam bentuk array terstruktur atau Model Entity.

### D. DTOs (`app/DTOs/`)
- **Bertugas**:
  1. Mengunci bentuk data input formulir atau payload API menjadi objek bertipe data pasti (*type-safe*).
  2. Memiliki method `fromArray()` atau `fromRequest()` untuk instansiasi cepat.
