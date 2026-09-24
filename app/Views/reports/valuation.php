<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs p-5 sm:p-6 mb-6">
    <!-- Header Modul & Sub-nav Tabs -->
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-5">
        <div>
            <h1 class="page-title text-xl font-extrabold tracking-tight text-slate-900 mb-1">Pusat Laporan & Analitik Inventaris</h1>
            <p class="text-xs text-slate-500">
                Monitoring finansial, valuasi nilai modal stok, rekapitulasi mutasi berkala, dan proyeksi belanja pengadaan.
            </p>
        </div>

        <div class="no-print flex items-center gap-2">
            <a href="<?= base_url('reports/export/valuation' . (!empty($selectedCategory) || !empty($selectedSupplier) ? '?' . http_build_query(['kategori' => $selectedCategory, 'supplier_id' => $selectedSupplier]) : '')) ?>" 
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
        <a href="<?= base_url('reports') ?>" class="report-tab active inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60 shadow-2xs">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            <span>Valuasi Aset Stok</span>
        </a>
        <a href="<?= base_url('reports/movements') ?>" class="report-tab inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
            <span>Rekap Mutasi Berkala</span>
        </a>
        <a href="<?= base_url('reports/restock') ?>" class="report-tab inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <span>Kebutuhan Restock Pengadaan</span>
        </a>
    </div>
</div>

<!-- KPI Cards Finansial Valuasi -->
<div class="stats-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Valuasi Aset -->
    <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center gap-4">
            <div class="stat-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="stat-info min-w-0 flex-1">
                <span class="stat-label text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-0.5">Total Valuasi Aset Fisik</span>
                <strong class="stat-value text-xl font-extrabold tracking-tight text-indigo-600 truncate block" title="<?= format_rupiah($summary['total_valuasi']) ?>">
                    <?= format_rupiah($summary['total_valuasi']) ?>
                </strong>
            </div>
        </div>
    </div>

    <!-- Total Kuantitas Fisik -->
    <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center gap-4">
            <div class="stat-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
            <div class="stat-info min-w-0 flex-1">
                <span class="stat-label text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-0.5">Total Fisik Barang</span>
                <strong class="stat-value text-2xl font-extrabold tracking-tight text-emerald-600 block">
                    <?= number_format($summary['total_unit'], 0, ',', '.') ?> Unit
                </strong>
            </div>
        </div>
    </div>

    <!-- Ragam SKU -->
    <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center gap-4">
            <div class="stat-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
            </div>
            <div class="stat-info min-w-0 flex-1">
                <span class="stat-label text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-0.5">Ragam SKU Terdata</span>
                <strong class="stat-value text-2xl font-extrabold tracking-tight text-slate-900 block">
                    <?= (int)$summary['total_sku'] ?> Item
                </strong>
            </div>
        </div>
    </div>

    <!-- Valuasi Tertinggi -->
    <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center gap-4">
            <div class="stat-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
            </div>
            <div class="stat-info min-w-0 flex-1">
                <span class="stat-label text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-0.5">Nilai Item Tertinggi</span>
                <strong class="stat-value text-lg font-extrabold tracking-tight text-amber-700 truncate block" title="<?= format_rupiah($summary['valuasi_tertinggi']) ?>">
                    <?= format_rupiah($summary['valuasi_tertinggi']) ?>
                </strong>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan Komposisi Aset (Kategori & Vendor) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Komposisi per Kategori -->
    <div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <svg class="h-4 w-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                <span>Valuasi per Kategori Produk</span>
            </h3>
        </div>
        <div class="table-responsive overflow-x-auto">
            <table class="table w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4 text-center">Unit</th>
                        <th class="py-3 px-4 text-right">Total Valuasi</th>
                        <th class="py-3 px-4 text-center w-20">Porsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($byCategory)): ?>
                        <tr><td colspan="4" class="text-center py-8 text-slate-400">Belum ada data</td></tr>
                    <?php else: ?>
                        <?php foreach ($byCategory as $cat): ?>
                            <tr class="hover:bg-slate-50/75 transition-colors">
                                <td class="py-3 px-4 font-bold text-slate-900"><?= esc($cat['kategori']) ?></td>
                                <td class="py-3 px-4 text-center font-medium"><?= number_format($cat['total_unit'], 0, ',', '.') ?></td>
                                <td class="py-3 px-4 text-right font-bold text-slate-800"><?= format_rupiah($cat['total_valuasi']) ?></td>
                                <td class="py-3 px-4 text-center">
                                    <span class="badge inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60"><?= $cat['persentase'] ?>%</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Komposisi per Pemasok -->
    <div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <svg class="h-4 w-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                <span>Valuasi per Mitra Pemasok</span>
            </h3>
        </div>
        <div class="table-responsive overflow-x-auto">
            <table class="table w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50/80 text-[11px] font-bold uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Pemasok</th>
                        <th class="py-3 px-4 text-center">SKU</th>
                        <th class="py-3 px-4 text-right">Total Valuasi</th>
                        <th class="py-3 px-4 text-center w-20">Porsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($bySupplier)): ?>
                        <tr><td colspan="4" class="text-center py-8 text-slate-400">Belum ada data</td></tr>
                    <?php else: ?>
                        <?php foreach ($bySupplier as $sup): ?>
                            <tr class="hover:bg-slate-50/75 transition-colors">
                                <td class="py-3 px-4">
                                    <strong class="text-slate-900 block"><?= esc($sup['nama_supplier']) ?></strong>
                                    <?php if (!empty($sup['kode_supplier'])): ?>
                                        <span class="text-[10px] text-slate-400 font-mono"><?= esc($sup['kode_supplier']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-center font-medium"><?= (int)$sup['total_sku'] ?></td>
                                <td class="py-3 px-4 text-right font-bold text-slate-800"><?= format_rupiah($sup['total_valuasi']) ?></td>
                                <td class="py-3 px-4 text-center">
                                    <span class="badge inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200"><?= $sup['persentase'] ?>%</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tabel Rincian Valuasi per Item -->
<div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
    <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h3 class="text-base font-bold text-slate-900">
            Rincian Nilai Aset per Item Inventaris
        </h3>

        <!-- Filter Form -->
        <form action="<?= base_url('reports') ?>" method="GET" class="no-print flex flex-wrap items-center gap-2">
            <select name="kategori" class="form-control text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer" onchange="this.form.submit()">
                <option value="">-- Semua Kategori --</option>
                <?php foreach ($categories as $cat): 
                    $catName = is_array($cat) ? $cat['nama_kategori'] : $cat->nama_kategori;
                ?>
                    <option value="<?= esc($catName) ?>" <?= ($selectedCategory === $catName) ? 'selected' : '' ?>><?= esc($catName) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="supplier_id" class="form-control text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer" onchange="this.form.submit()">
                <option value="">-- Semua Pemasok --</option>
                <?php foreach ($suppliers as $sup): ?>
                    <option value="<?= $sup['id'] ?>" <?= ($selectedSupplier == $sup['id']) ? 'selected' : '' ?>>
                        <?= esc($sup['nama_supplier']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if (!empty($selectedCategory) || !empty($selectedSupplier)): ?>
                <a href="<?= base_url('reports') ?>" class="btn btn-secondary text-xs px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition-all">Reset</a>
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
                    <th class="py-3.5 px-4">Kategori</th>
                    <th class="py-3.5 px-4">Pemasok Utama</th>
                    <th class="py-3.5 px-4 text-right">Harga Satuan</th>
                    <th class="py-3.5 px-4 text-center">Stok Fisik</th>
                    <th class="py-3.5 px-4 text-right">Total Nilai Aset</th>
                    <th class="py-3.5 px-4 text-center w-24">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($details)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-12 px-4 text-slate-400">
                            Tidak ada data produk yang sesuai kriteria filter.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($details as $item): ?>
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-xs text-slate-400"><?= $no++ ?></td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200/60 px-2 py-0.5 rounded">
                                    <?= esc($item['kode_produk']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <strong class="text-slate-900 font-bold"><?= esc($item['nama_produk']) ?></strong>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-semibold text-xs"><?= esc($item['kategori']) ?></span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600 whitespace-nowrap">
                                <?= !empty($item['nama_supplier']) ? esc($item['nama_supplier']) : '<span class="text-slate-400">-</span>' ?>
                            </td>
                            <td class="py-3.5 px-4 text-right font-medium text-slate-700 whitespace-nowrap">
                                <?= format_rupiah($item['harga']) ?>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-900 whitespace-nowrap">
                                <?= (int)$item['stok'] ?>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-indigo-600 whitespace-nowrap">
                                <?= format_rupiah($item['nilai_aset']) ?>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <?php if ($item['stok'] <= 0): ?>
                                    <span class="badge inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/60">Habis</span>
                                <?php elseif ($item['stok'] <= ($item['stok_minimum'] ?? 5)): ?>
                                    <span class="badge inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60">Restock</span>
                                <?php else: ?>
                                    <span class="badge inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">Aman</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Tampilan Responsif Khusus Mobile: Apple iOS Inset Grouped Table View -->
    <div class="mobile-grouped-view p-3 bg-slate-50/50">
        <?php if (empty($details)): ?>
            <div class="text-center py-12 px-4 text-slate-400 text-xs">
                Tidak ada data produk yang sesuai kriteria filter.
            </div>
        <?php else: ?>
            <div class="ios-grouped-list">
                <?php foreach ($details as $item): 
                    $currStock = (int)$item['stok'];
                    $minStock  = (int)($item['stok_minimum'] ?? 5);
                ?>
                    <div class="ios-list-row">
                        <!-- Squircle Inisial Kategori Khas iOS -->
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700 font-bold text-xs shadow-2xs">
                            <?= strtoupper(substr($item['kategori'] ?? 'P', 0, 2)) ?>
                        </div>

                        <!-- Data Utama Valuasi Produk -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-900 truncate leading-snug">
                                    <?= esc($item['nama_produk']) ?>
                                </h3>
                                <span class="text-xs font-bold text-indigo-700 shrink-0">
                                    <?= format_rupiah($item['nilai_aset']) ?>
                                </span>
                            </div>

                            <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-500">
                                <span class="font-mono text-[10px] font-bold text-indigo-700 bg-indigo-50/80 px-1.5 py-0.5 rounded">
                                    <?= esc($item['kode_produk']) ?>
                                </span>
                                <span>&bull;</span>
                                <span class="truncate"><?= esc($item['kategori']) ?></span>
                            </div>

                            <?php if (!empty($item['nama_supplier'])): ?>
                                <div class="text-[11px] text-slate-500 mt-1 truncate">
                                    Pemasok: <strong class="text-slate-700"><?= esc($item['nama_supplier']) ?></strong>
                                </div>
                            <?php endif; ?>

                            <!-- Baris Status Stok & Nilai -->
                            <div class="flex items-center justify-between gap-2 mt-2 pt-1.5 border-t border-slate-100/60 text-[10px]">
                                <div>
                                    <?php if ($currStock <= 0): ?>
                                        <span class="ios-badge-danger">Habis (0)</span>
                                    <?php elseif ($currStock <= $minStock): ?>
                                        <span class="ios-badge-warning">Restock (<?= $currStock ?>)</span>
                                    <?php else: ?>
                                        <span class="ios-badge-success">Aman (<?= $currStock ?>)</span>
                                    <?php endif; ?>
                                </div>

                                <span class="text-slate-400 font-medium">
                                    @ <?= format_rupiah($item['harga']) ?>
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
