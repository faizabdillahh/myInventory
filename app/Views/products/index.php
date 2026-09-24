<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- =========================================================================
 * BAGIAN 1: STATISTIK WIDGET KARTU (Ringkasan Inventaris)
 * ========================================================================= -->
<!-- =========================================================================
 * BAGIAN 1: STATISTIK WIDGET KARTU (Compact 2x2 iOS Metric Grid)
 * ========================================================================= -->
<div class="stats-grid grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4 mb-4 sm:mb-6">
    <!-- Kartu Total Ragam Produk -->
    <a href="<?= base_url('products') ?>" class="stat-card group block rounded-2xl border border-slate-200/80 bg-white p-3 sm:p-5 shadow-2xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xs hover:border-indigo-200">
        <div class="flex items-center gap-2.5 sm:gap-4">
            <div class="stat-icon flex h-9 w-9 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 transition-colors group-hover:bg-indigo-600 group-hover:text-white">
                <svg class="h-4 w-4 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="stat-val text-lg sm:text-2xl font-bold tracking-tight text-slate-900"><?= number_format((int)$stats['total_products']) ?></div>
                <div class="stat-label text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider truncate">Total Produk</div>
            </div>
        </div>
    </a>

    <!-- Kartu Total Stok Fisik Unit -->
    <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-3 sm:p-5 shadow-2xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xs">
        <div class="flex items-center gap-2.5 sm:gap-4">
            <div class="stat-icon flex h-9 w-9 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <svg class="h-4 w-4 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="stat-val text-lg sm:text-2xl font-bold tracking-tight text-slate-900"><?= number_format((int)$stats['total_stock']) ?></div>
                <div class="stat-label text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider truncate">Total Unit Fisik</div>
            </div>
        </div>
    </div>

    <!-- Kartu Peringatan Restock (Stok <= Minimum) -->
    <a href="<?= base_url('products?status=low_stock') ?>" class="stat-card group block rounded-2xl border <?= ($stats['low_stock'] > 0) ? 'border-amber-300 bg-amber-50/40' : 'border-slate-200/80 bg-white' ?> p-3 sm:p-5 shadow-2xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xs">
        <div class="flex items-center gap-2.5 sm:gap-4">
            <div class="stat-icon flex h-9 w-9 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-xl <?= ($stats['low_stock'] > 0) ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' ?> transition-colors group-hover:bg-amber-600 group-hover:text-white">
                <svg class="h-4 w-4 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="stat-val text-lg sm:text-2xl font-bold tracking-tight <?= ($stats['low_stock'] > 0) ? 'text-amber-800' : 'text-slate-900' ?>">
                    <?= number_format((int)$stats['low_stock']) ?>
                    <?php if (!empty($stats['out_of_stock'])): ?>
                        <span class="text-[10px] sm:text-xs font-semibold text-rose-600 ml-0.5">(<?= (int)$stats['out_of_stock'] ?>)</span>
                    <?php endif; ?>
                </div>
                <div class="stat-label text-[10px] sm:text-xs font-semibold <?= ($stats['low_stock'] > 0) ? 'text-amber-700' : 'text-slate-500' ?> uppercase tracking-wider truncate">Perlu Restock</div>
            </div>
        </div>
    </a>

    <!-- Kartu Estimasi Nilai Aset Inventaris -->
    <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-3 sm:p-5 shadow-2xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xs">
        <div class="flex items-center gap-2.5 sm:gap-4">
            <div class="stat-icon flex h-9 w-9 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="h-4 w-4 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="stat-val text-base sm:text-2xl font-bold tracking-tight text-slate-900 truncate" title="<?= format_rupiah($stats['total_asset']) ?>">
                    <?= format_rupiah($stats['total_asset']) ?>
                </div>
                <div class="stat-label text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider truncate">Total Nilai Aset</div>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
 * BAGIAN 2: DAFTAR DATA PRODUK (TABEL CRUD)
 * ========================================================================= -->
<div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
    <div class="card-header p-5 sm:p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h2 class="card-title text-lg font-bold text-slate-900 flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                </div>
                <span>Katalog Produk & Inventaris</span>
            </h2>
            <p class="card-subtitle text-xs text-slate-500 mt-1">Daftar lengkap produk dengan pemantauan batas stok minimum dinamis.</p>
        </div>

        <div class="card-header-actions flex flex-wrap items-center gap-3">
            <!-- Formulir Pencarian & Filter Kategori -->
            <form action="<?= base_url('products') ?>" method="GET" class="flex flex-wrap items-center gap-2">
                <?php if (!empty($selectedStatus)): ?>
                    <input type="hidden" name="status" value="<?= esc($selectedStatus) ?>">
                <?php endif; ?>

                <div class="search-wrapper relative min-w-[180px] sm:min-w-[240px] flex-1 sm:flex-initial">
                    <svg class="search-icon absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input 
                        type="text" 
                        name="q" 
                        class="search-input w-full pl-9 pr-8 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all truncate" 
                        placeholder="Cari produk..." 
                        value="<?= esc($searchQuery) ?>"
                    >
                    <?php if (!empty($searchQuery)): ?>
                        <a href="<?= base_url('products' . (!empty($selectedCategory) ? '?kategori=' . urlencode($selectedCategory) : '') . (!empty($selectedStatus) ? (!empty($selectedCategory) ? '&' : '?') . 'status=' . urlencode($selectedStatus) : '')) ?>" 
                           class="absolute right-2.5 top-1/2 -translate-y-1/2 flex h-4 w-4 items-center justify-center rounded-full bg-slate-300 text-white hover:bg-slate-400 transition-colors" 
                           title="Hapus pencarian" aria-label="Hapus pencarian">
                            <svg class="h-2.5 w-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>

                <select name="kategori" class="form-control text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <?php if (!empty($categories)): foreach ($categories as $cat): 
                        $cName = is_array($cat) ? $cat['nama_kategori'] : $cat->nama_kategori;
                    ?>
                        <option value="<?= esc($cName) ?>" <?= ($selectedCategory === $cName) ? 'selected' : '' ?>><?= esc($cName) ?></option>
                    <?php endforeach; endif; ?>
                </select>

                <!-- Tombol Reset Pencarian jika sedang filter -->
                <?php if (!empty($searchQuery) || !empty($selectedCategory) || !empty($selectedStatus)): ?>
                    <a href="<?= base_url('products') ?>" class="btn btn-secondary text-xs px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all font-semibold" title="Hapus filter">Reset</a>
                <?php endif; ?>
            </form>

            <!-- Tombol Tambah Produk Baru -->
            <a href="<?= base_url('products/new') ?>" class="btn btn-primary inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-xs shadow-indigo-600/20 hover:bg-indigo-700 hover:shadow-md transition-all">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Produk</span>
            </a>
        </div>
    </div>

    <!-- Quick Filter Status (Apple iOS Segmented Control) -->
    <div class="px-4 sm:px-6 py-2.5 bg-slate-50/50 border-b border-slate-100 overflow-x-auto">
        <div class="ios-segmented-control">
            <a href="<?= base_url('products' . (!empty($selectedCategory) ? '?kategori=' . urlencode($selectedCategory) : '')) ?>" 
               class="ios-segmented-item <?= empty($selectedStatus) ? 'active' : '' ?>">
                Semua Produk
            </a>
            <a href="<?= base_url('products?status=low_stock' . (!empty($selectedCategory) ? '&kategori=' . urlencode($selectedCategory) : '')) ?>" 
               class="ios-segmented-item <?= ($selectedStatus === 'low_stock') ? 'active' : '' ?>">
                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                <span>Perlu Restock (<?= (int)$stats['low_stock'] ?>)</span>
            </a>
            <a href="<?= base_url('products?status=out_of_stock' . (!empty($selectedCategory) ? '&kategori=' . urlencode($selectedCategory) : '')) ?>" 
               class="ios-segmented-item <?= ($selectedStatus === 'out_of_stock') ? 'active' : '' ?>">
                <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                <span>Stok Habis (<?= (int)$stats['out_of_stock'] ?>)</span>
            </a>
            <a href="<?= base_url('products?status=available' . (!empty($selectedCategory) ? '&kategori=' . urlencode($selectedCategory) : '')) ?>" 
               class="ios-segmented-item <?= ($selectedStatus === 'available') ? 'active' : '' ?>">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span>Tersedia Aman</span>
            </a>
        </div>
    </div>

    <!-- Tabel Data Responsif (Desktop & Tablet) -->
    <div class="table-responsive desktop-table-view overflow-x-auto">
        <table class="table w-full text-left text-sm text-slate-700">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200/80">
                <tr>
                    <th class="py-3.5 px-4 text-center w-12">No</th>
                    <th class="py-3.5 px-4">Kode SKU</th>
                    <th class="py-3.5 px-4">Nama & Kategori Produk</th>
                    <th class="py-3.5 px-4">Harga Satuan</th>
                    <th class="py-3.5 px-4">Kapasitas & Stok Fisik</th>
                    <th class="py-3.5 px-4">Penginput</th>
                    <th class="py-3.5 px-4 text-center w-36">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-16 px-4 text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mb-1">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <strong class="text-sm font-bold text-slate-800">
                                    <?= !empty($searchQuery) ? 'Produk Tidak Ditemukan' : 'Belum Ada Data Produk' ?>
                                </strong>
                                <span class="text-xs text-slate-500 max-w-sm">
                                    <?= !empty($searchQuery) 
                                        ? 'Tidak ada produk yang cocok dengan kata kunci "' . esc($searchQuery) . '".' 
                                        : 'Silakan klik tombol "Tambah Produk" di atas untuk menambahkan data pertama Anda.' ?>
                                </span>
                                <?php if (!empty($searchQuery) || !empty($selectedCategory) || !empty($selectedStatus)): ?>
                                    <a href="<?= base_url('products') ?>" class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-2xs">
                                        <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        <span>Bersihkan Filter Pencarian</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($products as $item): ?>
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <!-- Nomor Urut -->
                            <td class="py-3.5 px-4 text-center font-bold text-xs text-slate-400"><?= $no++ ?></td>

                            <!-- Kode Produk / SKU Interaktif (Copy & Barcode Preview) -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200/60 px-2.5 py-1 rounded-md">
                                        <?= esc($item['kode_produk']) ?>
                                    </span>
                                    <button type="button" class="btn-copy-sku flex h-6 w-6 items-center justify-center rounded text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors cursor-pointer" data-sku="<?= esc($item['kode_produk']) ?>" title="Salin SKU">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn-trigger-barcode flex h-6 w-6 items-center justify-center rounded text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors cursor-pointer" data-sku="<?= esc($item['kode_produk']) ?>" data-name="<?= esc($item['nama_produk']) ?>" title="Lihat & Cetak Barcode">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 5v14M8 5v14M12 5v14M17 5v14M21 5v14"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>

                            <!-- Nama Produk & Kategori -->
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900"><?= esc($item['nama_produk']) ?></div>
                                <div class="flex items-center gap-2 text-xs text-slate-500 mt-1 flex-wrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-semibold text-[11px]"><?= esc($item['kategori']) ?></span>
                                    <?php if (!empty($item['nama_supplier'])): ?>
                                        <span class="text-slate-300">&bull;</span>
                                        <span class="text-slate-600">Pemasok: <strong class="text-slate-800"><?= esc($item['nama_supplier']) ?></strong></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($item['deskripsi'])): ?>
                                    <div class="text-xs text-slate-400 mt-1 line-clamp-1">
                                        <?= esc(mb_strimwidth($item['deskripsi'], 0, 50, '...')) ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Harga Satuan -->
                            <td class="py-3.5 px-4 font-bold text-slate-900 whitespace-nowrap">
                                <?= format_rupiah($item['harga']) ?>
                            </td>

                            <!-- Stok Fisik & Micro Progress Bar (Katana Pattern) -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <?php 
                                    $currentStock = (int)$item['stok'];
                                    $minStock     = (int)($item['stok_minimum'] ?? 5);
                                    $ratio        = ($minStock > 0) ? min(100, (int)round(($currentStock / $minStock) * 100)) : 100;
                                ?>
                                <div class="flex flex-col gap-1 min-w-[130px]">
                                    <div class="flex items-center justify-between text-xs">
                                        <?php if ($currentStock <= 0): ?>
                                            <span class="inline-flex items-center gap-1 font-bold text-rose-700">
                                                <span class="pulse-dot bg-rose-500 shrink-0"></span> Stok Habis (0)
                                            </span>
                                        <?php elseif ($currentStock <= $minStock): ?>
                                            <span class="inline-flex items-center gap-1 font-bold text-amber-700">
                                                <span class="pulse-dot bg-amber-500 shrink-0"></span> Menipis (<?= $currentStock ?> / Min: <?= $minStock ?>)
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 font-bold text-emerald-700">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500 shrink-0"></span> Tersedia (<?= $currentStock ?>)
                                            </span>
                                        <?php endif; ?>
                                        <span class="text-[10px] text-slate-400 font-medium">Min: <?= $minStock ?></span>
                                    </div>
                                    <div class="stock-meter-track">
                                        <div class="stock-meter-fill <?= ($currentStock <= 0) ? 'danger' : (($currentStock <= $minStock) ? 'warning' : 'normal') ?>" style="width: <?= max(4, $ratio) ?>%;"></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Nama Pembuat Data -->
                            <td class="py-3.5 px-4 text-xs text-slate-500 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <?= esc($item['pembuat'] ?? 'Sistem') ?>
                                </span>
                            </td>

                            <!-- Kolom Aksi (Edit & Hapus) -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Tombol Mutasi Stok Cepat -->
                                    <button 
                                        type="button" 
                                        class="btn-trigger-movement flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-indigo-600 hover:bg-indigo-50 hover:border-indigo-300 transition-all shadow-2xs cursor-pointer" 
                                        data-id="<?= $item['id'] ?>" 
                                        data-sku="<?= esc($item['kode_produk']) ?>"
                                        data-name="<?= esc($item['nama_produk']) ?>"
                                        data-stock="<?= (int)$item['stok'] ?>"
                                        title="Catat Mutasi Stok Cepat"
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                            <polyline points="17 6 23 6 23 12"></polyline>
                                        </svg>
                                    </button>

                                    <!-- Tombol Edit -->
                                    <a href="<?= base_url('products/edit/' . $item['id']) ?>" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 transition-all shadow-2xs" title="Edit Produk">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>

                                    <?php if (has_role('admin')): ?>
                                        <!-- Tombol Hapus (Memicu Modal Konfirmasi) - Khusus Administrator -->
                                        <button 
                                            type="button" 
                                            class="btn-trigger-delete flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-white text-rose-600 hover:bg-rose-50 hover:border-rose-300 transition-all shadow-2xs cursor-pointer" 
                                            data-id="<?= $item['id'] ?>" 
                                            data-name="<?= esc($item['nama_produk']) ?>"
                                            title="Hapus Produk"
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Tampilan Responsif Khusus Mobile: Apple iOS Inset Grouped Table View -->
    <div class="mobile-grouped-view p-3 bg-slate-50/50">
        <?php if (empty($products)): ?>
            <div class="text-center py-12 px-4 text-slate-400 text-xs">
                Belum ada data produk atau tidak ditemukan.
            </div>
        <?php else: ?>
            <div class="ios-grouped-list">
                <?php foreach ($products as $item): 
                    $currentStock = (int)$item['stok'];
                    $minStock     = (int)($item['stok_minimum'] ?? 5);
                ?>
                    <div class="ios-list-row">
                        <!-- Squircle Kategori Khas iOS -->
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-indigo-600 font-bold text-xs shadow-2xs">
                            <?= strtoupper(substr($item['kategori'] ?? 'P', 0, 2)) ?>
                        </div>

                        <!-- Data Utama Produk (Nama, SKU, Kategori) -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-900 truncate leading-snug">
                                    <?= esc($item['nama_produk']) ?>
                                </h3>
                                <span class="text-xs font-bold text-slate-900 shrink-0">
                                    <?= format_rupiah($item['harga']) ?>
                                </span>
                            </div>

                            <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-500">
                                <span class="font-mono text-[10px] font-bold text-indigo-700 bg-indigo-50/80 px-1.5 py-0.5 rounded">
                                    <?= esc($item['kode_produk']) ?>
                                </span>
                                <span>&bull;</span>
                                <span class="truncate"><?= esc($item['kategori']) ?></span>
                            </div>

                            <!-- Baris Status Stok Apple HIG & Aksi Cepat -->
                            <div class="flex items-center justify-between gap-2 mt-2 pt-1.5 border-t border-slate-100/60">
                                <div>
                                    <?php if ($currentStock <= 0): ?>
                                        <span class="ios-badge-danger">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                            Stok Habis (0)
                                        </span>
                                    <?php elseif ($currentStock <= $minStock): ?>
                                        <span class="ios-badge-warning">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            Menipis (<?= $currentStock ?> / Min: <?= $minStock ?>)
                                        </span>
                                    <?php else: ?>
                                        <span class="ios-badge-success">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Tersedia (<?= $currentStock ?>)
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Quick Micro Action Buttons -->
                                <div class="flex items-center gap-1">
                                    <!-- Tombol Mutasi Cepat -->
                                    <button 
                                        type="button" 
                                        class="btn-trigger-movement flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-700 active:bg-indigo-100 transition-colors cursor-pointer"
                                        data-id="<?= $item['id'] ?>" 
                                        data-sku="<?= esc($item['kode_produk']) ?>"
                                        data-name="<?= esc($item['nama_produk']) ?>"
                                        data-stock="<?= (int)$item['stok'] ?>"
                                        title="Mutasi Stok"
                                        aria-label="Mutasi Stok"
                                    >
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                            <polyline points="17 6 23 6 23 12"></polyline>
                                        </svg>
                                    </button>

                                    <!-- Tombol Barcode Modal -->
                                    <button 
                                        type="button" 
                                        class="btn-trigger-barcode flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600 active:bg-slate-200 transition-colors cursor-pointer" 
                                        data-sku="<?= esc($item['kode_produk']) ?>" 
                                        data-name="<?= esc($item['nama_produk']) ?>" 
                                        title="Barcode"
                                        aria-label="Barcode"
                                    >
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 5v14M8 5v14M12 5v14M17 5v14M21 5v14"></path>
                                        </svg>
                                    </button>

                                    <!-- Tombol Edit -->
                                    <a href="<?= base_url('products/edit/' . $item['id']) ?>" class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-700 active:bg-slate-200 transition-colors" title="Edit" aria-label="Edit">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>

                                    <?php if (has_role('admin')): ?>
                                        <!-- Tombol Hapus (Admin) -->
                                        <button 
                                            type="button" 
                                            class="btn-trigger-delete flex h-7 w-7 items-center justify-center rounded-lg bg-rose-50 text-rose-600 active:bg-rose-100 transition-colors cursor-pointer" 
                                            data-id="<?= $item['id'] ?>" 
                                            data-name="<?= esc($item['nama_produk']) ?>"
                                            title="Hapus"
                                            aria-label="Hapus"
                                        >
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- =========================================================================
 * BAGIAN 3: MODAL KONFIRMASI HAPUS DATA (POPUP DIALOG AMAN)
 * ========================================================================= -->
<div class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 transition-all opacity-0 invisible" id="deleteConfirmModal">
    <div class="modal-box w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-200 transition-all">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 mb-4">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>

        <h3 class="text-base font-bold text-slate-900 mb-1.5">
            Konfirmasi Hapus Produk
        </h3>
        <p class="text-xs text-slate-500 leading-relaxed mb-6">
            Apakah Anda yakin ingin menghapus produk <strong id="deleteItemName" class="text-rose-600 font-bold">-</strong>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <!-- Formulir Penghapusan Data dengan Proteksi CSRF -->
        <form id="deleteForm" method="POST" action="" data-base-action="<?= base_url('products/delete') ?>">
            <?= csrf_field() ?>
            <div class="modal-actions flex items-center justify-end gap-2.5">
                <button type="button" class="btn btn-secondary rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all cursor-pointer" id="btnCancelDelete">Batal</button>
                <button type="submit" class="btn btn-danger rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-rose-700 transition-all cursor-pointer">Ya, Hapus Data</button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
 * BAGIAN 4: MODAL TRANSAKSI MUTASI STOK CEPAT
 * ========================================================================= -->
<div class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 transition-all opacity-0 invisible" id="stockMovementModal">
    <div class="modal-box w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-200 transition-all">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 leading-tight">
                    Catat Mutasi Stok
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Produk: <strong id="movementProductName" class="text-slate-800 font-semibold">-</strong> (<span id="movementProductSku" class="font-mono text-indigo-600">-</span>) &bull; Stok: <strong id="movementProductStock" class="text-indigo-600">-</strong> unit
                </p>
            </div>
        </div>

        <form action="<?= base_url('stock/movement') ?>" method="POST" id="stockMovementForm" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="product_id" id="movementProductId" value="">

            <div class="form-group">
                <label class="form-label block text-xs font-bold text-slate-700 mb-1.5">Jenis Mutasi <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-700 cursor-pointer hover:bg-indigo-50/50 hover:border-indigo-200 transition-all">
                        <input type="radio" name="tipe" value="IN" class="text-indigo-600 focus:ring-indigo-500" checked>
                        <span>Masuk (+)</span>
                    </label>
                    <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-700 cursor-pointer hover:bg-rose-50/50 hover:border-rose-200 transition-all">
                        <input type="radio" name="tipe" value="OUT" class="text-rose-600 focus:ring-rose-500">
                        <span>Keluar (-)</span>
                    </label>
                    <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-700 cursor-pointer hover:bg-amber-50/50 hover:border-amber-200 transition-all">
                        <input type="radio" name="tipe" value="ADJUSTMENT" class="text-amber-600 focus:ring-amber-500">
                        <span>Koreksi</span>
                    </label>
                </div>
            </div>

            <div class="form-group" id="movementSupplierGroup">
                <label for="movementSupplierId" class="form-label block text-xs font-bold text-slate-700 mb-1.5">Pemasok / Asal Barang</label>
                <select name="supplier_id" id="movementSupplierId" class="form-control w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                    <option value="">-- Tanpa / Supplier Tidak Ditentukan --</option>
                    <?php if (!empty($suppliers)): foreach ($suppliers as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= esc($s['nama_supplier']) ?> (<?= esc($s['kode_supplier']) ?>)</option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div class="form-group hidden" id="movementCustomerGroup">
                <label for="movementCustomerId" class="form-label block text-xs font-bold text-slate-700 mb-1.5">Tujuan / Pelanggan Penerima</label>
                <select name="customer_id" id="movementCustomerId" class="form-control w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                    <option value="">-- Tanpa / Pelanggan Tidak Ditentukan --</option>
                    <?php if (!empty($customers)): foreach ($customers as $c): ?>
                        <option value="<?= $c['id'] ?>">
                            <?= esc($c['nama_pelanggan']) ?> (<?= esc($c['kode_pelanggan']) ?> - <?= esc($c['tipe']) ?>)
                        </option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="movementJumlah" class="form-label block text-xs font-bold text-slate-700 mb-1.5" id="movementJumlahLabel">Jumlah Unit <span class="text-rose-500">*</span></label>
                <input 
                    type="number" 
                    id="movementJumlah" 
                    name="jumlah" 
                    class="form-control w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" 
                    min="1" 
                    placeholder="Contoh: 10" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="movementKeterangan" class="form-label block text-xs font-bold text-slate-700 mb-1.5">Catatan / Referensi Dokumen</label>
                <input 
                    type="text" 
                    id="movementKeterangan" 
                    name="keterangan" 
                    class="form-control w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" 
                    placeholder="Contoh: Surat Jalan No. SJ-019 / Penjualan Toko"
                >
            </div>

            <div class="modal-actions pt-2 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <button type="button" class="btn btn-secondary rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all cursor-pointer" id="btnCancelMovement">Batal</button>
                <button type="submit" class="btn btn-primary rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-indigo-700 transition-all cursor-pointer">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
 * BAGIAN 5: MODAL BARCODE PREVIEW & CETAK LABEL THERMAL
 * ========================================================================= -->
<div class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 transition-all opacity-0 invisible" id="barcodePreviewModal" role="dialog" aria-modal="true" aria-labelledby="barcodeModalTitle">
    <div class="modal-box w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl border border-slate-200 transition-all text-center">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-1.5" id="barcodeModalTitle">
                <svg class="h-4 w-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 5v14M8 5v14M12 5v14M17 5v14M21 5v14"></path>
                </svg>
                <span>Label Barcode SKU</span>
            </h3>
            <button type="button" class="text-slate-400 hover:text-slate-600 text-lg leading-none cursor-pointer p-1" id="btnCloseBarcodeModal">&times;</button>
        </div>
        
        <div class="barcode-svg-container my-3 p-4 bg-slate-50/70 border border-dashed border-slate-200 rounded-xl flex flex-col items-center justify-center">
            <!-- Target Render SVG Barcode -->
            <div id="barcodeSvgTarget" class="w-full flex justify-center py-2"></div>
            <div id="barcodeSkuLabel" class="font-mono text-base font-extrabold tracking-widest text-slate-900 mt-2"></div>
            <div id="barcodeNameLabel" class="text-xs text-slate-500 font-semibold mt-0.5 line-clamp-1 max-w-[240px]"></div>
        </div>

        <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
            <button type="button" class="flex-1 rounded-xl border border-slate-200 bg-white py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all cursor-pointer" id="btnCancelBarcode">Tutup</button>
            <button type="button" class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl bg-indigo-600 py-2 text-xs font-bold text-white shadow-xs hover:bg-indigo-700 transition-all cursor-pointer" id="btnPrintBarcodeLabel">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Cetak Label</span>
            </button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
