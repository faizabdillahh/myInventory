<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs p-5 sm:p-6 mb-6">
    <!-- Header Modul & Sub-nav Tabs -->
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-5">
        <div>
            <h1 class="page-title text-xl font-extrabold tracking-tight text-slate-900 mb-1">Laporan Kebutuhan Restock Pengadaan</h1>
            <p class="text-xs text-slate-500">
                Daftar barang inventaris yang menyentuh batas minimum (*safety stock*), rekomendasi pemesanan ulang (*reorder*), dan proyeksi estimasi anggaran dana belanja vendor.
            </p>
        </div>

        <div class="no-print flex items-center gap-2">
            <a href="<?= base_url('reports/export/restock' . (!empty($selectedSupplier) ? '?supplier_id=' . $selectedSupplier : '')) ?>" 
               class="btn btn-secondary inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all shadow-2xs">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <span>Ekspor CSV</span>
            </a>
            <button onclick="window.print()" class="btn btn-primary inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-xs shadow-indigo-600/20 hover:bg-indigo-700 transition-all cursor-pointer">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    <!-- Tab Navigasi Laporan -->
    <div class="report-tabs no-print flex items-center gap-2 border-t border-slate-100 pt-4 overflow-x-auto text-xs">
        <?php if (has_role('admin')): ?>
            <a href="<?= base_url('reports') ?>" class="report-tab inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                <span>Valuasi Aset Stok</span>
            </a>
        <?php endif; ?>
        <a href="<?= base_url('reports/movements') ?>" class="report-tab inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
            <span>Rekap Mutasi Berkala</span>
        </a>
        <a href="<?= base_url('reports/restock') ?>" class="report-tab active inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60 shadow-2xs">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <span>Kebutuhan Restock Pengadaan</span>
        </a>
    </div>
</div>

<!-- KPI Cards Restock & Anggaran -->
<div class="stats-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <!-- Jumlah SKU Kritis -->
    <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center gap-4">
            <div class="stat-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div class="stat-info min-w-0 flex-1">
                <span class="stat-label text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-0.5">SKU Perlu Pengadaan</span>
                <strong class="stat-value text-2xl font-extrabold tracking-tight text-amber-700 block">
                    <?= (int)$totalSku ?> Produk
                </strong>
            </div>
        </div>
    </div>

    <!-- Total Kuantitas Reorder -->
    <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center gap-4">
            <div class="stat-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
            </div>
            <div class="stat-info min-w-0 flex-1">
                <span class="stat-label text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-0.5">Total Kebutuhan Unit (Saran)</span>
                <strong class="stat-value text-2xl font-extrabold tracking-tight text-indigo-600 block">
                    +<?= number_format($totalUnit, 0, ',', '.') ?> Unit
                </strong>
            </div>
        </div>
    </div>

    <!-- Estimasi Anggaran Pengadaan -->
    <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center gap-4">
            <div class="stat-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="stat-info min-w-0 flex-1">
                <span class="stat-label text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-0.5">Estimasi Kebutuhan Anggaran</span>
                <strong class="stat-value text-xl font-extrabold tracking-tight text-rose-600 truncate block" title="<?= format_rupiah($totalEstimasi) ?>">
                    <?= format_rupiah($totalEstimasi) ?>
                </strong>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Kebutuhan Pengadaan Restock -->
<div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
    <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h3 class="text-base font-bold text-slate-900">
            Daftar Produk di Bawah Ambang Stok Minimum
        </h3>

        <!-- Filter Form -->
        <form action="<?= base_url('reports/restock') ?>" method="GET" class="no-print flex items-center gap-2">
            <select name="supplier_id" class="form-control text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer" onchange="this.form.submit()">
                <option value="">-- Semua Mitra Pemasok --</option>
                <?php foreach ($suppliers as $sup): ?>
                    <option value="<?= $sup['id'] ?>" <?= ($selectedSupplier == $sup['id']) ? 'selected' : '' ?>>
                        <?= esc($sup['nama_supplier']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($selectedSupplier)): ?>
                <a href="<?= base_url('reports/restock') ?>" class="btn btn-secondary text-xs px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition-all">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-responsive desktop-table-view overflow-x-auto">
        <table class="table w-full text-left text-sm text-slate-700">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200/80">
                <tr>
                    <th class="py-3.5 px-4 text-center w-12">No</th>
                    <th class="py-3.5 px-4">Kode SKU</th>
                    <th class="py-3.5 px-4">Nama Produk</th>
                    <th class="py-3.5 px-4">Pemasok Utama & Kontak</th>
                    <th class="py-3.5 px-4 text-center w-28">Stok Fisik</th>
                    <th class="py-3.5 px-4 text-center w-28">Batas Min</th>
                    <th class="py-3.5 px-4 text-center w-32">Saran Order</th>
                    <th class="py-3.5 px-4 text-right">Harga Satuan</th>
                    <th class="py-3.5 px-4 text-right">Estimasi Biaya</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-16 px-4 text-emerald-700">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 mb-1">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <strong class="text-base font-bold text-slate-800">Semua Stok Fisik Aman Terkendali!</strong>
                                <span class="text-xs text-slate-500 max-w-sm">Tidak ada produk yang berada di bawah atau menyentuh ambang batas stok minimum.</span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($items as $row): ?>
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-xs text-slate-400"><?= $no++ ?></td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200/60 px-2 py-0.5 rounded">
                                    <?= esc($row['kode_produk']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <strong class="text-slate-900 font-bold"><?= esc($row['nama_produk']) ?></strong>
                                <div class="text-xs text-slate-400"><?= esc($row['kategori']) ?></div>
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                <?php if (!empty($row['nama_supplier'])): ?>
                                    <div class="font-bold text-slate-800"><?= esc($row['nama_supplier']) ?></div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        <?= !empty($row['telepon']) ? esc($row['telepon']) : (!empty($row['email']) ? esc($row['email']) : '-') ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-400">Belum Ditentukan</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <strong class="text-sm font-bold <?= $row['stok'] <= 0 ? 'text-rose-600' : 'text-amber-700' ?>">
                                    <?= (int)$row['stok'] ?>
                                </strong>
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs text-slate-500 font-medium whitespace-nowrap">
                                <?= (int)$row['stok_minimum'] ?>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="badge inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60">
                                    +<?= (int)$row['saran_reorder'] ?> Unit
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right text-xs text-slate-700 font-medium whitespace-nowrap">
                                <?= format_rupiah($row['harga']) ?>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-rose-600 text-xs whitespace-nowrap">
                                <?= format_rupiah($row['estimasi_biaya']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Tampilan Responsif Khusus Mobile: Apple iOS Inset Grouped Table View -->
    <div class="mobile-grouped-view p-3 bg-slate-50/50">
        <?php if (empty($items)): ?>
            <div class="text-center py-12 px-4 text-slate-400 text-xs">
                Semua stok fisik aman terkendali.
            </div>
        <?php else: ?>
            <div class="ios-grouped-list">
                <?php foreach ($items as $row): 
                    $currentStok = (int)$row['stok'];
                    $minStok     = (int)$row['stok_minimum'];
                ?>
                    <div class="ios-list-row">
                        <!-- Squircle Status Restock Khas iOS -->
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl <?= ($currentStok <= 0) ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600' ?> shadow-2xs">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </div>

                        <!-- Data Utama Produk Restock -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-900 truncate leading-snug">
                                    <?= esc($row['nama_produk']) ?>
                                </h3>
                                <span class="text-xs font-bold text-rose-600 shrink-0">
                                    <?= format_rupiah($row['estimasi_biaya']) ?>
                                </span>
                            </div>

                            <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-500">
                                <span class="font-mono text-[10px] font-bold text-indigo-700 bg-indigo-50/80 px-1.5 py-0.5 rounded">
                                    <?= esc($row['kode_produk']) ?>
                                </span>
                                <span>&bull;</span>
                                <span class="truncate"><?= esc($row['kategori']) ?></span>
                            </div>

                            <?php if (!empty($row['nama_supplier'])): ?>
                                <div class="text-[11px] text-slate-600 mt-1 flex items-center gap-1">
                                    <span>Vendor:</span>
                                    <strong class="text-slate-800"><?= esc($row['nama_supplier']) ?></strong>
                                </div>
                            <?php endif; ?>

                            <!-- Baris Status Stok & Saran Order -->
                            <div class="flex items-center justify-between gap-2 mt-2 pt-1.5 border-t border-slate-100/60 text-[10px]">
                                <div>
                                    <?php if ($currentStok <= 0): ?>
                                        <span class="ios-badge-danger">Stok Habis (0 / Min: <?= $minStok ?>)</span>
                                    <?php else: ?>
                                        <span class="ios-badge-warning">Menipis (<?= $currentStok ?> / Min: <?= $minStok ?>)</span>
                                    <?php endif; ?>
                                </div>

                                <span class="badge inline-flex items-center px-2 py-0.5 rounded-full font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60">
                                    Saran: +<?= (int)$row['saran_reorder'] ?> Unit
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
