# Product Roadmap: Web Inventory (InventarisPro)

Dokumen ini mendokumentasikan visi strategis, peta jalan pengembangan (*product roadmap*), serta backlog fitur untuk membawa **InventarisPro** menjadi aplikasi manajemen inventaris terbaik di kelasnya—siap digunakan untuk operasional internal bisnis maupun dikomersialkan sebagai produk SaaS / White-label.

---

## Ringkasan Tahapan Pengembangan (Strategic Timeline)

```
[SELESAI] FASE 1: Core Inventory & Concurrency Hardening
   ├── Layered Architecture (Service-Repository-DTO)
   ├── Pessimistic Locking (FOR UPDATE) pada Mutasi Stok
   ├── Modul Master Produk, Kategori, Supplier, & Pelanggan
   ├── Laporan Valuasi Aset, Rekap Mutasi, & Rekomendasi Restock CSV
   └── Test Suite Integrasi Mandiri (60/60 Test Lulus 100%)
      │
      ▼
[SELESAI] FASE 2: Granular Governance & User Provisioning
   ├── Role-Based Access Control (RBAC: Administrator vs Staff)
   ├── Filter Otorisasi Rute (RoleFilter & HTTP 403 / Redirect Protection)
   ├── Modul Manajemen Pengguna Terpusat (/users)
   ├── Penutupan Registrasi Terbuka Publik (Anti-Uninvited Access)
   └── UI Kontekstual & Dynamic Role Badges
      │
      ▼
[NEXT] FASE 3: Physical Warehouse Operations & Mobile Enablement
   ├── Integrasi Barcode & QR Code Generator (Print Label Produk)
   ├── Scanner Kamera Web Langsung via Browser (html5-qrcode)
   └── Ekspor Dokumen Resmi PDF (Surat Jalan & Bukti Barang Masuk)
      │
      ▼
[FUTURE] FASE 4: Multi-Warehouse & Internal Logistics
   ├── Multi-Gudang Fisik (Gudang Utama, Cabang, Display Toko, Transit)
   ├── Manajemen Bin / Rak Penyimpanan (Aisle - Rack - Bin)
   └── Mutasi Transfer Stok Antar Gudang dengan Pelacakan Status In-Transit
      │
      ▼
[FUTURE] FASE 5: Formal Document Flow (PO & DO Lifecycle)
   ├── Modul Purchase Order (PO): Draft &rarr; Disetujui &rarr; Penerimaan Barang (GRN)
   ├── Modul Sales Order (SO): Pesanan &rarr; Picking &rarr; Delivery Order (DO)
   └── Two-Step Verification untuk Kontrol Keuangan
      │
      ▼
[FUTURE] FASE 6: Smart Inventory & Omnichannel Ecosystem
   ├── RESTful Open API (JWT / Bearer Token) untuk Kasir & E-Commerce
   ├── AI Stock Depletion Forecasting (Estimasi Tanggal Kehabisan Stok)
   └── Notifikasi Otomatis WhatsApp / Email saat Stok Kritis
```

---

## Detail Modul & Spesifikasi Fitur Mendatang

### 1. Fase 3: Operasional Fisik Gudang & Mobile Scanner (Prioritas Terdekat)
* **Tujuan**: Mempercepat operasional fisik di gudang agar operator tidak perlu mengetik SKU manual di keyboard.
* **Fitur Utama**:
  1. **Barcode & QR Generator**: Generate barcode SVG/PNG untuk setiap SKU dan tombol cetak label stiker (kompatibel printer thermal 58mm/80mm).
  2. **Camera Scanner**: Integrasi library JavaScript ringan (`html5-qrcode`) di form pencatatan mutasi stok. Operator cukup mengarahkan kamera HP/laptop ke stiker produk.
  3. **Cetak PDF Bukti Mutasi**: Menggunakan pustaka PDF (Dompdf) untuk menghasilkan Surat Bukti Terima Barang (*Goods Receipt Note*) dan Surat Jalan Pengeluaran Barang.

### 2. Fase 4: Multi-Warehouse & Transfer Antar Cabang
* **Tujuan**: Mendukung bisnis yang berkembang memiliki lebih dari 1 lokasi fisik.
* **Fitur Utama**:
  1. Tabel `warehouses` dan relasi `warehouse_products` (kuantitas per gudang).
  2. Alur transfer internal: Operator Gudang A membuat permohonan transfer &rarr; status `in-transit` &rarr; Operator Gudang B mengonfirmasi penerimaan fisik.

### 3. Fase 5: Siklus Dokumen Pengadaan & Pengeluaran (PO & DO)
* **Tujuan**: Mencegah pembelian atau pengeluaran barang tanpa otorisasi pimpinan.
* **Fitur Utama**:
  1. Pemisahan dokumen *Purchase Order (PO)* dari mutasi fisik. Stok baru bertambah ketika faktur fisik tiba dan diinspeksi.
  2. Riwayat harga beli historis per supplier untuk analisa tren kenaikan harga vendor.

### 4. Fase 6: Smart Inventory & API Eksternal
* **Tujuan**: Integrasi ekosistem modern dan otomatisasi peringatan.
* **Fitur Utama**:
  1. Endpoint RESTful API untuk integrasi kasir POS Android dan toko online (Tokopedia, Shopee, WooCommerce).
  2. Prediksi stok cerdas berbasis laju pengeluaran 30 hari terakhir (*Moving Average / Burn Rate*).
