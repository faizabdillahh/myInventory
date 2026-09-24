# ADR-0002: Pessimistic Locking (`FOR UPDATE`) pada Mutasi Stok

* **Status**: `Accepted`
* **Tanggal Keputusan**: 2026-09-23
* **Pembuat Keputusan**: Lead Architect / AI Pair Programmer

---

## 1. Konteks & Latar Belakang Masalah
Dalam sistem manajemen inventaris, mutasi kuantitas stok barang adalah operasi paling krusial. Pada jam sibuk, dua atau lebih operator gudang / kasir dapat melakukan checkout barang atau pencatatan barang keluar pada detik yang sama (*concurrent requests*).

Tanpa mekanisme konkurensi:
1. Operator A membaca stok produk = 5.
2. Operator B membaca stok produk = 5.
3. Operator A mengurangi stok 3 &rarr; stok menjadi 2.
4. Operator B mengurangi stok 4 &rarr; stok menimpa menjadi 1 (seharusnya total keluar 7 dan transaksi ditolak karena stok fisik hanya 5).
5. Hal ini menimbulkan **Phantom Stock** atau **Stok Minus**, yang berujung pada selisih fisik barang di gudang.

---

## 2. Faktor Penentu Keputusan (Decision Drivers)
* **Kebenaran Data (Data Integrity)**: Stok fisik tidak boleh pernah bernilai minus atau tidak sesuai dengan histori transaksi.
* **ACID Compliance**: Mutasi stok produk dan pencatatan audit log mutasi wajib atomik (semua sukses atau semua batal).
* **Kompatibilitas MySQL InnoDB**: Memanfaatkan kapabilitas *row-level locking* bawaan basis data tanpa memerlukan dependensi eksternal berat seperti Redis distributed lock pada tahap awal.

---

## 3. Alternatif yang Dipertimbangkan
* **Opsi A: Optimistic Locking (Kolom `version` / timestamp)**
  * *Cara Kerja*: Menambahkan kolom `version` dan memeriksa apakah versi berubah saat update. Jika berubah, lemparkan exception dan minta pengguna mengulang form.
  * *Alasan Ditolak*: Pengalaman pengguna (*UX*) buruk di lingkungan gudang yang bergerak cepat karena operator harus sering menginput ulang form ketika terjadi benturan data.
* **Opsi B: Redis Distributed Locking (Redlock)**
  * *Alasan Ditolak*: Menambah dependensi infrastruktur server eksternal (butuh daemon Redis) yang membebani instalasi lokal Laragon/shared hosting.
* **Opsi C: Pessimistic Row Locking (`SELECT ... FOR UPDATE`) dalam Database Transaction [DIPILIH]**
  * Didukung secara native oleh MySQL InnoDB. Mengunci baris produk yang sedang bermutasi selama beberapa milidetik hingga transaksi selesai, lalu melepaskan antrean ke operator berikutnya secara transparan tanpa error.

---

## 4. Keputusan Arsitektural (Decision Outcome)
1. Pada `ProductRepositoryInterface`, ditambahkan method `findById(int $id, bool $forUpdate = false)`.
2. Jika `$forUpdate === true`, kueri dieksekusi dengan klausul `FOR UPDATE` di level SQL:
   ```sql
   SELECT * FROM `products` WHERE `id` = ? FOR UPDATE;
   ```
3. Seluruh method mutasi di `StockService` (`addStock`, `reduceStock`, `adjustStock`) wajib memanggil `$this->productRepo->findById($productId, true)` di dalam closure `$this->executeInTransaction(...)`.
4. Jika stok fisik tidak mencukupi saat mutasi `OUT`, exception segera dilemparkan dan transaksi di-rollback secara otomatis.

---

## 5. Konsekuensi & Trade-offs
* **Dampak Positif**:
  - Jaminan matematis bahwa *race condition* dan stok minus mustahil terjadi.
  - Audit trail `stock_movements` selalu sinkron persis dengan angka akhir di tabel `products`.
* **Kompromi (Trade-off)**:
  - Sedikit penurunan *throughput* konkurensi ekstrem jika ada ratusan request per detik pada satu produk yang sama.
  - *Mitigasi*: Menambahkan indeks performa pada kolom `products.id` dan membatasi durasi transaksi di bawah 50 milidetik.
