# ADR-0001: Penerapan Layered Architecture (Service-Repository-DTO)

* **Status**: `Accepted`
* **Tanggal Keputusan**: 2026-09-23
* **Pembuat Keputusan**: Lead Architect / AI Pair Programmer

---

## 1. Konteks & Latar Belakang Masalah
Pada awalnya, aplikasi CodeIgniter sering kali dibangun dengan pola konvensional *Fat Controller* di mana kueri database (Query Builder), validasi form, manipulasi data, dan kalkulasi bisnis ditulis bercampur aduk di dalam method Controller.

Hal ini menimbulkan masalah serius ketika proyek berkembang:
1. Kode sulit diuji secara otomatis (*untestable*).
2. Logika mutasi stok terikat langsung pada format HTTP Web, sehingga sulit dipakai ulang oleh REST API atau CLI Spark Command.
3. Potensi kebocoran transaksi database (lupa `transCommit` / `transRollback`).
4. Rentan terhadap salah ketik nama kolom array (*array key typo*).

---

## 2. Faktor Penentu Keputusan (Decision Drivers)
* **Maintainability & Skalabilitas**: Kemudahan menambah modul baru (Supplier, Customer, Warehouse) tanpa merusak kode lama.
* **Separation of Concerns (SoC)**: Controller hanya boleh mengurus HTTP Request/Response.
* **Type-Safety**: PHP 8.3 mendukung strictly-typed DTO dengan properti readonly.
* **Testability**: Lapisan service dan repository harus dapat diuji secara independen.

---

## 3. Alternatif yang Dipertimbangkan
* **Opsi A: Active Record Klasik CI4 (Model-View-Controller Murni)**
  * *Alasan Ditolak*: Logika transaksi dan kalkulasi stok menumpuk di controller; rawan inkonsistensi saat ada banyak endpoint.
* **Opsi B: Domain-Driven Design (DDD) Penuh dengan Event Sourcing**
  * *Alasan Ditolak*: Terlalu kompleks (*over-engineering*) untuk aplikasi skala menengah berbasis CI4 dan memperlambat kecepatan iterasi *vibe coding*.
* **Opsi C: Pragmatic Layered Architecture (Controller &rarr; Service &rarr; Repository &rarr; DTO) [DIPILIH]**
  * Menawarkan keseimbangan sempurna antara kebersihan kode (*clean code*), kemudahan dibaca AI, dan performa tinggi.

---

## 4. Keputusan Arsitektural (Decision Outcome)
Sistem membagi aplikasi menjadi 4 lapisan terisolasi:
1. **Presentation Layer (`app/Controllers/`)**:
   - Menerima request HTTP, validasi form dasar, membungkus data ke DTO, memanggil Service, dan me-render View / JSON.
   - **Aturan Baku**: Dilarang menulis kueri SQL/Builder langsung di Controller.
2. **Business Logic Layer (`app/Services/`)**:
   - Menampung 100% aturan domain bisnis (pengecekan stok minus, kalkulasi ambang minimum, transaksi atomik via `$this->executeInTransaction()`).
   - Independen dari HTTP format.
3. **Data Access Layer (`app/Repositories/`)**:
   - Terikat pada antarmuka *Contract* (`app/Repositories/Contracts/`).
   - Mengisolasi kueri basis data dan manipulasi Model.
4. **Data Transfer Layer (`app/DTOs/`)**:
   - Objek bertipe data pasti (*type-safe*) yang membawa data antar-lapisan.

---

## 5. Konsekuensi & Trade-offs
* **Dampak Positif**:
  - Controller menjadi sangat ramping (*lean*), rata-rata di bawah 150 baris kode.
  - Setiap modul baru dapat dibuat dengan pola yang seragam dan mudah dipelajari oleh model AI baru.
  - Pengujian integrasi HTTP nyata mencapai kelulusan 100%.
* **Kompromi (Trade-off)**:
  - Jumlah berkas bertambah (untuk 1 modul butuh Controller, Service, Repository, Interface, DTO, dan Model).
  - *Mitigasi*: Menetapkan workflow 7 langkah standar pada panduan `.agents/AGENTS.md` agar pembuatan berkas baru terotomatisasi secara konsisten.
