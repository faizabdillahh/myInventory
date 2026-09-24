# ADR-0003: Implementasi RBAC 2-Tingkat & Penutupan Registrasi Publik

* **Status**: `Accepted`
* **Tanggal Keputusan**: 2026-09-24
* **Pembuat Keputusan**: Lead Architect / AI Pair Programmer

---

## 1. Konteks & Latar Belakang Masalah
Awalnya, aplikasi memiliki halaman registrasi publik mandiri (`/register`) dan seluruh pengguna yang login memiliki wewenang yang setara (bisa menghapus produk, mengedit supplier, dan melihat seluruh laporan finansial).

Kondisi ini menimbulkan risiko bisnis dan komersial yang signifikan:
1. **Kerahasiaan Modal & Valuasi**: Operator gudang di lapangan tidak semestinya melihat harga beli modal, valuasi aset total perusahaan, dan margin keuntungan.
2. **Keamanan Aset Fisik**: Staf operasional tidak boleh memiliki izin menghapus master produk, kategori, pelanggan, atau supplier karena dapat menghilangkan jejak audit historis.
3. **Penyusupan Akun Liar**: Halaman registrasi publik memungkinkan orang luar mendaftar sendiri dan melihat inventaris internal perusahaan.

---

## 2. Faktor Penentu Keputusan (Decision Drivers)
* **Kesiapan Ganda (Dual-Goal)**: Aplikasi harus siap dipakai untuk operasional internal bisnis pemilik, sekaligus memiliki nilai komersial tinggi sebagai aset digital yang siap dijual/disewakan (*SaaS/White-label ready*).
* **Standar Industri Kompetitor**: Menyelaraskan alur kerja dengan software ERP/WMS terkemuka (Odoo, Mekari Jurnal, Zoho Inventory).
* **Kemudahan Operasional**: Tidak membuat hierarki perizinan yang terlalu rumit (*granular permissions hell*) pada tahap awal.

---

## 3. Alternatif yang Dipertimbangkan
* **Opsi A: Pertahankan Registrasi Publik & Default Role Staff**
  * *Alasan Ditolak*: Kurang aman untuk aplikasi inventaris internal; perusahaan tidak ingin ada form pendaftaran yang dapat diakses sembarang orang di internet.
* **Opsi B: Per-Permission Gate (Matriks Hak Akses Puluhan Centang)**
  * *Alasan Ditolak*: Terlalu rumit untuk pengguna UMKM dan menambah beban query permission tabel per request.
* **Opsi C: Hierarki 2-Tingkat Terstruktur (Administrator vs Staff) + Modul Manajemen Pengguna Terpusat [DIPILIH]**
  * Memisahkan peran dengan jelas: Administrator (Owner/Superadmin) memegang kendali penuh, sedangkan Staff Gudang fokus pada pencatatan fisik barang.

---

## 4. Keputusan Arsitektural (Decision Outcome)
1. **Penutupan Rute Registrasi Publik**: Rute `/register` publik dialihkan (*redirect*) ke `/login` dengan notifikasi bahwa akun hanya dapat dibuat oleh Administrator.
2. **Pembuatan Modul Manajemen Pengguna (`/users`)**:
   - Hanya dapat diakses oleh pengguna dengan peran `admin`.
   - Mengikuti pola *Layered Architecture* lengkap (`UserDTO`, `UserRepositoryInterface`, `UserService`, `Users` controller).
   - Menyediakan fitur pembuatan staf baru, penetapan peran, edit akun, reset password, serta proteksi keamanan *anti-self-lockout* (Admin tidak dapat menghapus akunnya sendiri yang sedang aktif login).
3. **Filter Otorisasi (`RoleFilter`)**:
   - Mengamankan rute administratif sensitif: `/users/*`, `/categories/*` (kecuali index view), seluruh aksi hapus master data (`/products/delete`, `/suppliers/delete`, `/customers/delete`), dan laporan finansial valuasi modal (`/reports` & `/reports/export/valuation`).
4. **Helper Autentikasi Global (`auth_helper.php`)**:
   - Fungsi `has_role()`, `is_admin()`, dan `is_staff()` dimuat otomatis di `BaseController` untuk menyembunyikan tombol hapus dan menu administratif pada antarmuka pengguna staf.

---

## 5. Konsekuensi & Trade-offs
* **Dampak Positif**:
  - Kerahasiaan modal bisnis terjamin 100%.
  - Mencegah kelalaian operator dalam menghapus data penting.
  - Repositori menjadi produk komersial bernilai jual tinggi karena pembeli dapat langsung membagi tugas staf mereka.
* **Kompromi (Trade-off)**:
  - Administrator harus mendaftarkan akun staf secara manual di awal.
  - *Mitigasi*: Disediakan akun *seed* bawaan (`admin / admin123` dan `staff / staff123`) serta antarmuka pembuatan staf yang intuitif dan cepat.
