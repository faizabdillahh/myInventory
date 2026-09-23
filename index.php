<?php
/**
 * ==============================================================================
 * FILE: index.php
 * DESKRIPSI: Dashboard Utama & Fitur READ Data Produk
 * ==============================================================================
 * Menerapkan Clean Code & Best Practices:
 * 1. Logika penarikan data dan kalkulasi statistik didelegasikan ke model Product (classes/Product.php).
 * 2. Pemisahan tanggung jawab (Separation of Concerns): File ini murni sebagai Controller/View.
 * 3. Sanitasi output menggunakan helper e() dan format_rupiah().
 * 4. Proteksi CSRF pada form modal konfirmasi penghapusan data.
 */

// 1. Pengecekan autentikasi session (Middleware)
require_once __DIR__ . '/includes/auth_check.php';

// 2. Memuat koneksi database dan model Product
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Product.php';

// Judul halaman
$page_title = "Dashboard Produk";

// Inisialisasi model Product
$productModel = new Product($pdo);

// Mengambil kata kunci pencarian dari parameter GET
$searchQuery = trim($_GET['q'] ?? '');

try {
    // 3. Mengambil statistik ringkasan inventaris untuk kartu widget atas
    $stats = $productModel->getSummaryStats();

    // 4. Mengambil daftar produk (beserta filter pencarian jika ada)
    $products = $productModel->all($searchQuery);
} catch (Exception $e) {
    $errorDatabase = "Gagal memuat data inventaris: " . $e->getMessage();
    $stats = ['total_products' => 0, 'total_stock' => 0, 'low_stock' => 0, 'total_asset' => 0];
    $products = [];
}

// Memuat template header HTML dan navigasi
require_once __DIR__ . '/includes/header.php';
?>

<!-- =========================================================================
 * BAGIAN 1: STATISTIK WIDGET KARTU (Ringkasan Inventaris)
 * ========================================================================= -->
<div class="stats-grid">
    <!-- Kartu Total Ragam Produk -->
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
        </div>
        <div>
            <div class="stat-val"><?= number_format((int)$stats['total_products']) ?></div>
            <div class="stat-label">Total Jenis Produk</div>
        </div>
    </div>

    <!-- Kartu Total Stok Fisik Unit -->
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>
        </div>
        <div>
            <div class="stat-val"><?= number_format((int)$stats['total_stock']) ?></div>
            <div class="stat-label">Total Stok Tersedia</div>
        </div>
    </div>

    <!-- Kartu Peringatan Stok Menipis / Habis -->
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>
        <div>
            <div class="stat-val"><?= number_format((int)$stats['low_stock']) ?></div>
            <div class="stat-label">Stok Menipis / Habis</div>
        </div>
    </div>

    <!-- Kartu Estimasi Nilai Aset Inventaris -->
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
        <div>
            <div class="stat-val"><?= format_rupiah($stats['total_asset']) ?></div>
            <div class="stat-label">Total Nilai Inventaris</div>
        </div>
    </div>
</div>

<!-- =========================================================================
 * BAGIAN 2: DAFTAR DATA PRODUK (TABEL CRUD)
 * ========================================================================= -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                Katalog Produk & Inventaris
            </h2>
            <p class="card-subtitle">Daftar lengkap produk yang terdaftar dalam sistem inventaris.</p>
        </div>

        <div class="card-header-actions">
            <!-- Formulir Pencarian Kata Kunci -->
            <form action="index.php" method="GET" class="search-wrapper">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input 
                    type="text" 
                    name="q" 
                    class="search-input" 
                    placeholder="Cari kode, nama, atau kategori..." 
                    value="<?= e($searchQuery) ?>"
                >
            </form>

            <!-- Tombol Reset Pencarian jika sedang filter -->
            <?php if (!empty($searchQuery)): ?>
                <a href="index.php" class="btn btn-secondary btn-sm" title="Hapus filter pencarian">Reset</a>
            <?php endif; ?>

            <!-- Tombol Tambah Produk Baru -->
            <a href="create.php" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Produk
            </a>
        </div>
    </div>

    <!-- Menampilkan pesan error jika query bermasalah -->
    <?php if (isset($errorDatabase)): ?>
        <div class="alert alert-danger"><?= e($errorDatabase) ?></div>
    <?php endif; ?>

    <!-- Tabel Data Responsif -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">No</th>
                    <th>Kode SKU</th>
                    <th>Nama & Kategori Produk</th>
                    <th>Harga Satuan</th>
                    <th>Stok Gudang</th>
                    <th>Penginput</th>
                    <th style="text-align: center; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <!-- Tampilan jika tidak ada produk / pencarian nihil -->
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 48px 20px; color: var(--text-muted);">
                            <div style="margin-bottom: 12px; color: var(--text-subtle);">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                            </div>
                            <strong style="display: block; font-size: 14px; font-weight: 600; color: var(--text-main); margin-bottom: 4px;">
                                <?= !empty($searchQuery) ? 'Produk Tidak Ditemukan' : 'Belum Ada Data Produk' ?>
                            </strong>
                            <span style="font-size: 13px;">
                                <?= !empty($searchQuery) 
                                    ? 'Tidak ada produk yang cocok dengan kata kunci "' . e($searchQuery) . '".' 
                                    : 'Silakan klik tombol "Tambah Produk" di atas untuk menambahkan data pertama Anda.' ?>
                            </span>
                        </td>
                    </tr>
                <?php else: ?>
                    <!-- Looping data produk -->
                    <?php $no = 1; foreach ($products as $item): ?>
                        <tr>
                            <!-- Nomor Urut -->
                            <td style="text-align: center; font-weight: 600; color: var(--text-muted);"><?= $no++ ?></td>

                            <!-- Kode Produk / SKU -->
                            <td>
                                <span style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 12px; font-weight: 600; color: var(--accent); background: var(--surface-subtle); border: 1px solid var(--border); padding: 2px 6px; border-radius: var(--radius-sm);">
                                    <?= e($item['kode_produk']) ?>
                                </span>
                            </td>

                            <!-- Nama Produk & Kategori -->
                            <td>
                                <div style="font-weight: 600; color: var(--text-main);"><?= e($item['nama_produk']) ?></div>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                                    Kategori: <span class="badge badge-primary"><?= e($item['kategori']) ?></span>
                                </div>
                                <?php if (!empty($item['deskripsi'])): ?>
                                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                                        <?= e(mb_strimwidth($item['deskripsi'], 0, 50, '...')) ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Harga Satuan -->
                            <td style="font-weight: 600; color: var(--text-main);">
                                <?= format_rupiah($item['harga']) ?>
                            </td>

                            <!-- Stok Fisik & Badge Indikator Status -->
                            <td>
                                <?php if ($item['stok'] <= 0): ?>
                                    <span class="badge badge-danger">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                        Stok Habis (0)
                                    </span>
                                <?php elseif ($item['stok'] <= 5): ?>
                                    <span class="badge badge-warning">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                        Menipis (<?= (int)$item['stok'] ?>)
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-success">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Tersedia (<?= (int)$item['stok'] ?>)
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Nama Pembuat Data -->
                            <td>
                                <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 12px; color: var(--text-muted);">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <?= e($item['pembuat'] ?? 'Sistem') ?>
                                </span>
                            </td>

                            <!-- Kolom Aksi (Edit & Hapus) -->
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 8px;">
                                    <!-- Tombol Edit -->
                                    <a href="edit.php?id=<?= $item['id'] ?>" class="btn btn-secondary btn-icon" title="Edit Produk">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>

                                    <!-- Tombol Hapus (Memicu Modal Konfirmasi) -->
                                    <button 
                                        type="button" 
                                        class="btn btn-danger btn-icon btn-trigger-delete" 
                                        data-id="<?= $item['id'] ?>" 
                                        data-name="<?= e($item['nama_produk']) ?>"
                                        title="Hapus Produk"
                                    >
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- =========================================================================
 * BAGIAN 3: MODAL KONFIRMASI HAPUS DATA (POPUP DIALOG AMAN)
 * ========================================================================= -->
<div class="modal-overlay" id="deleteConfirmModal">
    <div class="modal-box">
        <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--danger-light); color: var(--danger); display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>

        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">
            Konfirmasi Hapus Produk
        </h3>
        <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5;">
            Apakah Anda yakin ingin menghapus produk <strong id="deleteItemName" style="color: var(--danger);">-</strong>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <!-- Formulir Penghapusan Data dengan Proteksi CSRF -->
        <form id="deleteForm" method="POST" action="">
            <?= csrf_field() ?>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" id="btnCancelDelete">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Hapus Data</button>
            </div>
        </form>
    </div>
</div>

<?php
// Memuat template footer HTML
require_once __DIR__ . '/includes/footer.php';
?>
