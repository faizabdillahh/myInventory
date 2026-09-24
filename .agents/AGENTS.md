# AI AGENT INSTRUCTION & REPOSITORY BLUEPRINT (VIBE CODING GUIDE)

> **Untuk Semua Agen AI / Model LLM (Antigravity, Cursor, Claude Code, Copilot, dll.)**:
> Dokumen ini adalah **Instruksi Utama (Single Source of Truth)** untuk memahami struktur proyek, aturan baku, dan standar kualitas saat melakukan *vibe coding* atau penambahan fitur pada repositori **Web Inventory (InventarisPro)**.

---

## 1. Profil Proyek & Teknologi
- **Nama Aplikasi**: Web Inventory (InventarisPro)
- **Framework**: CodeIgniter 4 (v4.7+)
- **Bahasa**: PHP 8.3 (Strictly typed, type hints, modern syntax)
- **Database**: MySQL 8.x / MariaDB (Collation `utf8mb4_unicode_ci`, database `db_InventarisPro`)
- **Web Server Lokal**: Laragon (Apache 2.4, Document Root `public/` via rewrite `.htaccess`)
- **Frontend Stack**: Compiled Tailwind CSS v4 & Native Vanilla CSS (`public/assets/css/style.css`, Google Fonts *Plus Jakarta Sans*), Vanilla JS (`public/assets/js/app.js`).

---

## 2. Struktur Direktori Proyek & Progressive Documentation

```
Web Inventory/
├── AGENTS.md                          # Rangkuman instruksi cepat di root
├── .agents/                           # Konfigurasi & panduan resmi untuk AI Agent
│   ├── AGENTS.md                      # File panduan utama ini
│   └── rules/                         # Aturan modular
│       ├── clean_code.md              # Aturan Clean Code & SOLID
│       ├── architecture.md            # Aturan Layered Architecture (Service-Repository)
│       └── security_and_database.md   # Aturan Transaksi, Keamanan & Migrasi DB
├── docs/                              # DOKUMENTASI SISTEM (Baca sesuai kebutuhan)
│   ├── ARCHITECTURE.md                # Diagram aliran data, layer, & filter otorisasi
│   ├── SOP_ENGINEERING_STANDARDS.md   # Standar penamaan, kueri & struktur kode
│   ├── ROADMAP.md                     # Visi fitur mendatang & prioritas backlog
│   └── adr/                           # ARCHITECTURE DECISION RECORDS (ADR)
│       ├── README.md                  # Indeks ADR dan panduan format
│       ├── 0001-adopt-layered-architecture.md
│       ├── 0002-pessimistic-locking-for-stock-concurrency.md
│       ├── 0003-rbac-and-centralized-user-management.md
│       └── 0004-enterprise-folder-structure-and-api-partitioning.md
├── app/
│   ├── Config/                        # Konfigurasi CI4 (Routes.php, Filters.php, Database.php)
│   ├── Controllers/                   # PRESENTATION LAYER
│   │   ├── BaseController.php         # Base Web Controller
│   │   ├── Web/                       # Web UI Controllers (HTML View responses)
│   │   └── Api/                       # RESTful API Controllers (JSON responses)
│   │       ├── BaseApiController.php  # Base API Controller (ResponseTrait)
│   │       └── V1/                    # API Endpoints Version 1 (Mobile/Scanner)
│   ├── Services/                      # BUSINESS LOGIC LAYER (100% aturan domain & transaksi)
│   ├── Repositories/                  # DATA ACCESS LAYER (Kueri DB & Pessimistic Locking)
│   │   └── Contracts/                 # Interface kontrak repository
│   ├── DTOs/                          # Data Transfer Objects (Type-safe input)
│   ├── Exceptions/                    # Custom Domain & Business Exceptions
│   ├── Models/                        # CI4 Active Record Models
│   ├── Filters/                       # Route Filters (AuthFilter, RoleFilter, GuestFilter)
│   ├── Helpers/                       # Global Helpers (format_helper, auth_helper)
│   ├── Database/                      # Migrations & Seeds
│   └── Views/                         # View Templates
│       └── components/                # Reusable View UI Components
├── public/                            # Document Root publik
├── tests/
│   └── test_flow.php                  # VERIFICATION HOOK: 60 skenario uji integrasi HTTP otomatis
└── spark                              # CLI Runner CodeIgniter 4
```

---

## 3. Workflow Vibe Coding (Wajib Diikuti Agen AI)

Ketika diminta menambahkan fitur atau modul baru (misal: "Modul Gudang", "Fitur Barcode"), ikuti alur 7 langkah ini secara disiplin:

1. **Database Migration**: Buat migrasi di `app/Database/Migrations/`, lalu jalankan `php spark migrate`.
2. **Model**: Buat Model di `app/Models/` dengan `$allowedFields`, validation rules (sertakan `id => permit_empty|integer`), dan timestamps.
3. **DTO (Data Transfer Object)**: Buat DTO di `app/DTOs/` untuk mengunci tipe data input dari form.
4. **Repository**: Buat Interface di `app/Repositories/Contracts/` dan implementasinya di `app/Repositories/`.
5. **Service Layer**: Tulis logika bisnis, validasi aturan domain, transaksi database via `$this->executeInTransaction()` di `app/Services/`.
6. **Controller**: Buat Controller ramping di `app/Controllers/`. Controller hanya memanggil Service dan me-render View / JSON.
7. **Views & Routes**: Buat View mewarisi `layouts/main`, daftarkan rute di `app/Config/Routes.php` lengkap dengan filter keamanan (`auth` atau `role:admin`).
8. **ADR (Jika Mengubah Arsitektur)**: Jika membuat keputusan arsitektural besar, tambahkan berkas rekaman baru di `docs/adr/`.
9. **Verifikasi Test Suite**: Jalankan `php tests/test_flow.php` untuk memastikan seluruh fitur tetap lulus 100%.

---

## 4. Perintah CLI Esensial (Command Reference)

Gunakan PHP Laragon (`D:\APLIKASI\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe`):

```bash
# Menjalankan seluruh test integrasi otomatis (Wajib Lulus 100%)
php tests/test_flow.php

# Menjalankan migrasi database baru
php spark migrate

# Memeriksa status migrasi
php spark migrate:status

# Menjalankan seeder data pengguna bawaan
php spark db:seed UserSeeder

# Menampilkan daftar seluruh rute aktif
php spark routes

# Membersihkan cache sistem
php spark cache:clear
```

---

## 5. Larangan Mutlak Bagi Agen AI (Strict Guardrails)

1. **JANGAN PERNAH MENYENTUH GIT**: Dilarang mengeksekusi perintah git apapun (`git commit`, `git status`, dll.).
2. **JANGAN MENULIS FAT CONTROLLER**: Jangan pernah meletakkan kueri query builder atau kalkulasi bisnis langsung di controller. Pindahkan ke Service & Repository.
3. **JANGAN MENGABAIKAN TRANSAKSI PADA MUTASI STOK**: Setiap mutasi kuantitas stok wajib dibungkus dalam transaksi database atomik dan menggunakan *pessimistic locking* (`FOR UPDATE`).
4. **JANGAN MEMBUKA REGISTRASI PUBLIK**: Rute `/register` publik harus selalu dialihkan ke `/login`. Akun baru hanya boleh dibuat oleh Administrator melalui `/users`.
5. **JANGAN OUTPUT VARIABEL KE HTML TANPA `esc()`**: Selalu gunakan `<?= esc($var) ?>` untuk mencegah XSS.
6. **JANGAN LUPA TOKEN CSRF**: Setiap formulir POST wajib menyertakan `<?= csrf_field() ?>`.
7. **JANGAN MENGAKHIRI PEKERJAAN TANPA RUNNING TESTS**: Selalu validasi bahwa `php tests/test_flow.php` menghasilkan status *ALL PASS*.
