# ADR-0004: Penataan Folder Modular & Partisi Delivery Mechanism (Web vs API)

* **Status**: `Accepted`
* **Tanggal Keputusan**: 2026-09-24
* **Pembuat Keputusan**: System Architect & Vibe Coding Pair Programmer

---

## 1. Konteks & Latar Belakang Masalah
Sebelumnya, seluruh controller aplikasi ditaruh secara *flat* di dalam direktori `app/Controllers/`. Seiring pertumbuhan aplikasi menuju platform SaaS komersial dengan roadmap penambahan **Mobile Barcode Scanner (Fase 3)** dan integrasi eksternal (POS/Marketplace), struktur *flat* ini memicu tiga *bottleneck* arsitektural:

1. **Polusi Tipe Respons**: Satu file controller rentan mencampur aduk method `return view()` (HTML) dengan `return $this->response->setJSON()` (REST API).
2. **Bentrokan Filter Keamanan**: Web UI memerlukan proteksi CSRF token dan session-cookie auth, sedangkan API membutuhkan payload JSON tanpa CSRF dan autentikasi token.
3. **Penyampaian Error Generik**: Service layer melempar `\Exception` mentah sehingga API tidak dapat mengembalikan kode status HTTP semantik (seperti 404 Not Found vs 422 Unprocessable Entity).

---

## 2. Faktor Penentu Keputusan (Decision Drivers)
* **Delivery Mechanism Partitioning**: Pemisahan jelas antara antarmuka Web Browser dan REST API Mobile/Hardware.
* **Semantic Error Handling**: Penanganan error berbasis Custom Domain Exceptions.
* **DRY Presentation**: Komponen UI yang sering berulang diabstraksikan ke sub-folder khusus.
* **100% Backward Compatibility**: Refaktor tidak boleh merusak 60 skenario uji integrasi otomatis yang telah ada.

---

## 3. Alternatif yang Dipertimbangkan
* **Opsi A: Membiarkan Controller Flat dan Menambah Prefix Nama File (`ApiProduct.php`)**
  * *Alasan Ditolak*: Folder menjadi sesak dan tidak modular saat file controller bertambah banyak.
* **Opsi B: HMVC Penuh (Module per Feature terpisah di direktori terluar)**
  * *Alasan Ditolak*: Terlalu kompleks untuk framework CI4 standar tanpa third-party module loader.
* **Opsi C: Modular Delivery Mechanism Partitioning (`app/Controllers/Web/` & `app/Controllers/Api/V1/`) [DIPILIH]**
  * Terbukti menjadi standar industri SaaS PHP (Laravel, CI4, Symfony), sangat bersih, dan PSR-4 compliant.

---

## 4. Keputusan Arsitektural (Decision Outcome)
1. **Refaktor Controller Web**:
   - Seluruh controller antarmuka browser dipindahkan ke namespace `App\Controllers\Web\` (`app/Controllers/Web/`).
   - File di root `app/Controllers/` dipertahankan sebagai *thin backward-compatibility proxy* yang mewarisi controller `Web\*`.
2. **Pembangunan REST API V1**:
   - Dibuat `app/Controllers/Api/BaseApiController.php` menggunakan `CodeIgniter\API\ResponseTrait`.
   - Dibuat namespace `App\Controllers\Api\V1\` berisi `ProductApiController` (katalog & lookup barcode instan) dan `StockApiController` (scanning mutasi stok).
3. **Domain Exceptions Layer (`app/Exceptions/`)**:
   - Dibuat exception terstandarisasi: `AppException`, `InsufficientStockException` (HTTP 422), `ResourceNotFoundException` (HTTP 404), dan `UnauthorizedActionException` (HTTP 403).
4. **Reusable View Components (`app/Views/components/`)**:
   - Abstraksi elemen UI modular: `stock_badge.php` dan `modal_confirm.php`.

---

## 5. Konsekuensi & Dampak (Consequences)
* **Positif**:
  - Arsitektur kini siap 100% mendukung pengembangan frontend Mobile Barcode Scanner (Fase 3).
  - Controller Web dan API terisolasi secara disiplin tanpa tumpang tindih filter keamanan.
  - Penanganan error pada API menghasilkan payload JSON terstandarisasi (`{ success, message, data, errors }`).
* **Kepatuhan Pengujian**:
  - Seluruh 60 skenario uji otomatis pada `tests/test_flow.php` tetap lulus 100% tanpa regresi.
