<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs p-5 sm:p-6 mb-6">
    <!-- Header Modul & Sub-nav Tabs -->
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-5">
        <div>
            <h1 class="page-title text-xl font-extrabold tracking-tight text-slate-900 mb-1">Laporan Rekapitulasi Mutasi Stok</h1>
            <p class="text-xs text-slate-500">
                Pantau pergerakan arus logistik masuk, keluar, dan koreksi opname dalam rentang periode tanggal tertentu.
            </p>
        </div>

        <div class="no-print flex items-center gap-2">
            <a href="<?= base_url('reports/export/movements?' . http_build_query([
                'start_date'  => $filter->startDate,
                'end_date'    => $filter->endDate,
                'tipe'        => $filter->tipe,
                'kategori'    => $filter->kategori,
                'supplier_id' => $filter->supplierId,
            ])) ?>" 
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
        <a href="<?= base_url('reports/movements') ?>" class="report-tab active inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60 shadow-2xs">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
            <span>Rekap Mutasi Berkala</span>
        </a>
        <a href="<?= base_url('reports/restock') ?>" class="report-tab inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <span>Kebutuhan Restock Pengadaan</span>
        </a>
    </div>
</div>

<!-- Form Filter Rentang Tanggal & Parameter -->
<div class="card no-print rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs mb-6">
    <form action="<?= base_url('reports/movements') ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 items-end">
        <div>
            <label class="form-label block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" value="<?= esc($filter->startDate ?? '') ?>">
        </div>

        <div>
            <label class="form-label block text-xs font-bold text-slate-700 mb-1">Tanggal Akhir</label>
            <input type="date" name="end_date" class="form-control w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" value="<?= esc($filter->endDate ?? '') ?>">
        </div>

        <div>
            <label class="form-label block text-xs font-bold text-slate-700 mb-1">Jenis Mutasi</label>
            <select name="tipe" class="form-control w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer">
                <option value="">-- Semua Jenis --</option>
                <option value="IN" <?= ($filter->tipe === 'IN') ? 'selected' : '' ?>>Barang Masuk (IN)</option>
                <option value="OUT" <?= ($filter->tipe === 'OUT') ? 'selected' : '' ?>>Barang Keluar (OUT)</option>
                <option value="ADJUSTMENT" <?= ($filter->tipe === 'ADJUSTMENT') ? 'selected' : '' ?>>Koreksi Opname</option>
            </select>
        </div>

        <div>
            <label class="form-label block text-xs font-bold text-slate-700 mb-1">Kategori</label>
            <select name="kategori" class="form-control w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer">
                <option value="">-- Semua Kategori --</option>
                <?php foreach ($categories as $cat): 
                    $catName = is_array($cat) ? $cat['nama_kategori'] : $cat->nama_kategori;
                ?>
                    <option value="<?= esc($catName) ?>" <?= ($filter->kategori === $catName) ? 'selected' : '' ?>><?= esc($catName) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="btn btn-primary flex-1 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-indigo-700 transition-all cursor-pointer">
                Terapkan Filter
            </button>
            <a href="<?= base_url('reports/movements') ?>" class="btn btn-secondary rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- KPI Cards Ringkasan Mutasi Periode -->
<div class="stats-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Barang Masuk -->
    <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center gap-4">
            <div class="stat-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
            </div>
            <div class="stat-info min-w-0 flex-1">
                <span class="stat-label text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-0.5">Total Barang Masuk (IN)</span>
                <strong class="stat-value text-2xl font-extrabold tracking-tight text-emerald-600 block">
                    +<?= number_format($stats['total_in'], 0, ',', '.') ?> Unit
                </strong>
            </div>
        </div>
    </div>

    <!-- Total Barang Keluar -->
    <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center gap-4">
            <div class="stat-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="17 14 12 9 7 14"></polyline>
                    <line x1="12" y1="9" x2="12" y2="21"></line>
                </svg>
            </div>
            <div class="stat-info min-w-0 flex-1">
                <span class="stat-label text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-0.5">Total Barang Keluar (OUT)</span>
                <strong class="stat-value text-2xl font-extrabold tracking-tight text-rose-600 block">
                    -<?= number_format($stats['total_out'], 0, ',', '.') ?> Unit
                </strong>
            </div>
        </div>
    </div>

    <!-- Net Delta Movement -->
    <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center gap-4">
            <div class="stat-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
            </div>
            <div class="stat-info min-w-0 flex-1">
                <span class="stat-label text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-0.5">Perubahan Bersih (Net Delta)</span>
                <strong class="stat-value text-2xl font-extrabold tracking-tight block <?= $stats['net_delta'] >= 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                    <?= $stats['net_delta'] >= 0 ? '+' : '' ?><?= number_format($stats['net_delta'], 0, ',', '.') ?> Unit
                </strong>
            </div>
        </div>
    </div>

    <!-- Total Transaksi -->
    <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center gap-4">
            <div class="stat-icon flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div class="stat-info min-w-0 flex-1">
                <span class="stat-label text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-0.5">Frekuensi Transaksi</span>
                <strong class="stat-value text-2xl font-extrabold tracking-tight text-slate-900 block">
                    <?= (int)$stats['total_transaksi'] ?> Kali
                </strong>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Riwayat Mutasi Terfilter -->
<div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
    <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h3 class="text-base font-bold text-slate-900">
            Daftar Catatan Mutasi Periode: <?= esc($filter->startDate ?? 'Semua') ?> s.d. <?= esc($filter->endDate ?? 'Hari ini') ?>
        </h3>
        <span class="text-xs text-slate-500">
            Ditemukan <?= count($movements) ?> baris transaksi
        </span>
    </div>

    <div class="table-responsive desktop-table-view overflow-x-auto">
        <table class="table w-full text-left text-sm text-slate-700">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200/80">
                <tr>
                    <th class="py-3.5 px-4 w-36">Waktu Transaksi</th>
                    <th class="py-3.5 px-4">Kode SKU</th>
                    <th class="py-3.5 px-4">Nama Produk</th>
                    <th class="py-3.5 px-4 text-center w-32">Jenis</th>
                    <th class="py-3.5 px-4 text-center w-20">Jumlah</th>
                    <th class="py-3.5 px-4 text-center w-32">Perubahan Stok</th>
                    <th class="py-3.5 px-4">Mitra (Vendor/Tujuan)</th>
                    <th class="py-3.5 px-4">Operator</th>
                    <th class="py-3.5 px-4">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($movements)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-12 px-4 text-slate-400">
                            Tidak ada data transaksi mutasi pada rentang periode yang dipilih.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($movements as $m): ?>
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="py-3.5 px-4 text-xs text-slate-500 whitespace-nowrap">
                                <?= date('d M Y, H:i', strtotime($m['created_at'])) ?>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200/60 px-2 py-0.5 rounded">
                                    <?= esc($m['kode_produk']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <strong class="text-slate-900 font-bold"><?= esc($m['nama_produk']) ?></strong>
                                <div class="text-xs text-slate-400"><?= esc($m['kategori'] ?? '') ?></div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <?php if ($m['tipe'] === 'IN'): ?>
                                    <span class="badge inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">Masuk (IN)</span>
                                <?php elseif ($m['tipe'] === 'OUT'): ?>
                                    <span class="badge inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/60">Keluar (OUT)</span>
                                <?php else: ?>
                                    <span class="badge inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60">Koreksi</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-xs whitespace-nowrap <?= ($m['tipe'] === 'IN') ? 'text-emerald-700' : (($m['tipe'] === 'OUT') ? 'text-rose-700' : 'text-slate-800') ?>">
                                <?= ($m['tipe'] === 'IN') ? '+' : (($m['tipe'] === 'OUT') ? '-' : '') ?><?= (int)$m['jumlah'] ?>
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs font-mono text-slate-500 whitespace-nowrap">
                                <span><?= (int)$m['stok_sebelum'] ?></span>
                                <span class="text-slate-300 mx-1.5">&rarr;</span>
                                <strong class="text-slate-900 font-bold"><?= (int)$m['stok_sesudah'] ?></strong>
                            </td>
                            <td class="py-3.5 px-4 text-xs whitespace-nowrap">
                                <?php if (!empty($m['nama_supplier'])): ?>
                                    <span class="text-indigo-600 font-medium">Pemasok: <?= esc($m['nama_supplier']) ?></span>
                                <?php elseif (!empty($m['nama_pelanggan'])): ?>
                                    <span class="text-rose-600 font-medium">Penerima: <?= esc($m['nama_pelanggan']) ?></span>
                                <?php else: ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-500 whitespace-nowrap">
                                <?= esc($m['operator'] ?? 'Sistem') ?>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-500 max-w-xs">
                                <?= !empty($m['keterangan']) ? esc($m['keterangan']) : '<span class="text-slate-400">-</span>' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Tampilan Responsif Khusus Mobile: Apple iOS Inset Grouped Table View -->
    <div class="mobile-grouped-view p-3 bg-slate-50/50">
        <?php if (empty($movements)): ?>
            <div class="text-center py-12 px-4 text-slate-400 text-xs">
                Tidak ada data transaksi mutasi pada rentang periode yang dipilih.
            </div>
        <?php else: ?>
            <div class="ios-grouped-list">
                <?php foreach ($movements as $m): ?>
                    <div class="ios-list-row">
                        <!-- Squircle Tipe Mutasi Khas iOS -->
                        <?php if ($m['tipe'] === 'IN'): ?>
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 shadow-2xs">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                            </div>
                        <?php elseif ($m['tipe'] === 'OUT'): ?>
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600 shadow-2xs">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="17 14 12 9 7 14"></polyline>
                                    <line x1="12" y1="9" x2="12" y2="21"></line>
                                </svg>
                            </div>
                        <?php else: ?>
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 shadow-2xs">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 16.5-3.5z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>

                        <!-- Data Utama Mutasi -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-900 truncate leading-snug">
                                    <?= esc($m['nama_produk']) ?>
                                </h3>
                                <span class="text-xs font-bold shrink-0 <?= ($m['tipe'] === 'IN') ? 'text-emerald-700' : (($m['tipe'] === 'OUT') ? 'text-rose-700' : 'text-slate-800') ?>">
                                    <?= ($m['tipe'] === 'IN') ? '+' : (($m['tipe'] === 'OUT') ? '-' : '') ?><?= (int)$m['jumlah'] ?> Unit
                                </span>
                            </div>

                            <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-500">
                                <span class="font-mono text-[10px] font-bold text-indigo-700 bg-indigo-50/80 px-1.5 py-0.5 rounded">
                                    <?= esc($m['kode_produk']) ?>
                                </span>
                                <span>&bull;</span>
                                <?php if ($m['tipe'] === 'IN'): ?>
                                    <span class="ios-badge-success text-[10px]">Masuk (IN)</span>
                                <?php elseif ($m['tipe'] === 'OUT'): ?>
                                    <span class="ios-badge-danger text-[10px]">Keluar (OUT)</span>
                                <?php else: ?>
                                    <span class="ios-badge-warning text-[10px]">Koreksi</span>
                                <?php endif; ?>
                            </div>

                            <!-- Mitra jika ada -->
                            <?php if (!empty($m['nama_supplier'])): ?>
                                <div class="text-[11px] text-indigo-600 mt-1 inline-flex items-center gap-1 font-medium bg-indigo-50/60 px-2 py-0.5 rounded">
                                    <span>Pemasok: <strong><?= esc($m['nama_supplier']) ?></strong></span>
                                </div>
                            <?php elseif (!empty($m['nama_pelanggan'])): ?>
                                <div class="text-[11px] text-rose-600 mt-1 inline-flex items-center gap-1 font-medium bg-rose-50/60 px-2 py-0.5 rounded">
                                    <span>Penerima: <strong><?= esc($m['nama_pelanggan']) ?></strong></span>
                                </div>
                            <?php endif; ?>

                            <!-- Catatan -->
                            <?php if (!empty($m['keterangan'])): ?>
                                <p class="text-[11px] text-slate-500 mt-1 line-clamp-1 italic">
                                    "<?= esc($m['keterangan']) ?>"
                                </p>
                            <?php endif; ?>

                            <!-- Baris Status Transisi Stok & Waktu -->
                            <div class="flex items-center justify-between gap-2 mt-2 pt-1.5 border-t border-slate-100/60 text-[10px] text-slate-400">
                                <span class="font-mono">
                                    Stok: <?= (int)$m['stok_sebelum'] ?> &rarr; <strong class="text-slate-700"><?= (int)$m['stok_sesudah'] ?></strong>
                                </span>
                                <span>
                                    <?= date('d M Y, H:i', strtotime($m['created_at'])) ?>
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
