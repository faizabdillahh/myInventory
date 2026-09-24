# AI AGENT DIRECTIVE: WEB INVENTORY

### Dokumentasi Terkait (Progressive Disclosure):
- **Panduan Lengkap Agen AI**: [`.agents/AGENTS.md`](.agents/AGENTS.md)
- **Peta Arsitektur & Aliran Data**: [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- **Standar Rekayasa & Konvensi Kode**: [`docs/SOP_ENGINEERING_STANDARDS.md`](docs/SOP_ENGINEERING_STANDARDS.md)
- **Rekaman Keputusan Arsitektural**: [`docs/adr/`](docs/adr/README.md)
- **Roadmap & Backlog Produk**: [`docs/ROADMAP.md`](docs/ROADMAP.md)

### Ringkasan Cepat:
- **Framework**: CodeIgniter 4 (PHP 8.3) di Laragon.
- **Arsitektur**: Layered Architecture (Controller &rarr; Service &rarr; Repository &rarr; DTO).
- **Aturan Vibe Coding**:
  1. Jangan sentuh git (dilarang menjalankan `git commit`, `git push`, dsb.).
  2. Controller harus ramping (*lean*), jangan buat *fat controller*.
  3. Logika bisnis di `app/Services/`.
  4. Akses data/kueri di `app/Repositories/`.
  5. Validasi & tipe input di `app/DTOs/`.
  6. Setiap output HTML wajib menggunakan `esc()`.
  7. Setiap mutasi stok wajib dalam database transaction + Pessimistic Locking (`FOR UPDATE`).
  8. Registrasi publik ditutup; akun hanya dibuat via Modul Pengguna (`/users`).
  9. Jalankan `php tests/test_flow.php` untuk memvalidasi perubahan (wajib lulus 100%).
