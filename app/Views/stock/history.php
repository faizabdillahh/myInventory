<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
/**
 * Utilitas pemetaan istilah teknis ke bahasa manusia yang ramah bagi pengguna
 */
$humanModuleName = function(?string $module): string {
    return match (strtoupper((string)$module)) {
        'STOCK'    => 'Mutasi Stok',
        'PRODUCT'  => 'Produk Barang',
        'SUPPLIER' => 'Data Pemasok',
        'CUSTOMER' => 'Data Pelanggan',
        'CATEGORY' => 'Kategori Barang',
        'USER'     => 'Pengguna Akun',
        'AUTH'     => 'Akses Sistem',
        default    => ucfirst(strtolower((string)$module)),
    };
};

$humanActionInfo = function(?string $action): array {
    return match (strtoupper((string)$action)) {
        'STOCK_IN'         => ['label' => 'Barang Masuk', 'badge' => 'ios-badge-success', 'dot' => 'bg-emerald-500'],
        'STOCK_OUT'        => ['label' => 'Barang Keluar', 'badge' => 'ios-badge-danger', 'dot' => 'bg-rose-500'],
        'STOCK_ADJUSTMENT' => ['label' => 'Penyesuaian Opname', 'badge' => 'ios-badge-warning', 'dot' => 'bg-amber-500'],
        'CREATE'           => ['label' => 'Tambah Data Baru', 'badge' => 'ios-badge-success', 'dot' => 'bg-emerald-500'],
        'UPDATE'           => ['label' => 'Pembaruan Data', 'badge' => 'ios-badge-warning', 'dot' => 'bg-amber-500'],
        'DELETE'           => ['label' => 'Penghapusan Data', 'badge' => 'ios-badge-danger', 'dot' => 'bg-rose-500'],
        'LOGIN'            => ['label' => 'Masuk Sistem', 'badge' => 'ios-badge-info', 'dot' => 'bg-indigo-500'],
        'LOGOUT'           => ['label' => 'Keluar Sistem', 'badge' => 'ios-badge-neutral', 'dot' => 'bg-slate-400'],
        default            => ['label' => (string)$action, 'badge' => 'ios-badge-neutral', 'dot' => 'bg-slate-400'],
    };
};

$humanFieldName = function(string $field): string {
    return match (strtolower(trim($field))) {
        'nama_produk'    => 'Nama Produk',
        'kode_produk'    => 'Kode Barang (SKU)',
        'harga'          => 'Harga Satuan',
        'stok'           => 'Jumlah Stok Fisik',
        'stok_minimum'   => 'Batas Aman Stok Minimum',
        'kategori'       => 'Kategori',
        'deskripsi'      => 'Keterangan Tambahan',
        'nama_supplier'  => 'Nama Pemasok',
        'kode_supplier'  => 'Kode Pemasok',
        'kontak_person'  => 'Penanggung Jawab (PIC)',
        'telepon'        => 'No. Telepon',
        'alamat'         => 'Alamat Kantor / Gudang',
        'nama_pelanggan' => 'Nama Pelanggan',
        'kode_pelanggan' => 'Kode Pelanggan',
        'nama_lengkap'   => 'Nama Lengkap Pengguna',
        'username'       => 'Nama Pengguna (Username)',
        'email'          => 'Alamat Email',
        'role'           => 'Peran / Hak Akses',
        'password'       => 'Kata Sandi',
        'supplier_id'    => 'Pemasok Terkait',
        'customer_id'    => 'Pelanggan Terkait',
        default          => ucwords(str_replace('_', ' ', $field)),
    };
};

$formatHumanValue = function(string $field, $val): string {
    if ($val === null || $val === '') return '(Kosong)';
    if ($field === 'harga' && is_numeric($val)) return format_rupiah((float)$val);
    if ($field === 'password') return '•••••••• (Diperbarui)';
    if ($field === 'role') return $val === 'admin' ? 'Administrator' : 'Staf Gudang';
    if (is_array($val)) return json_encode($val, JSON_UNESCAPED_UNICODE);
    return (string)$val;
};
?>

<!-- =========================================================================
 * BUKU RIWAYAT MUTASI STOK & CATATAN AKTIVITAS PENGGUNA LENGKAP
 * ========================================================================= -->
<div class="space-y-4 sm:space-y-6">

    <!-- Page Header (Bersih Tanpa Logo) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="page-title text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Riwayat Mutasi Stok & Aktivitas
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Catatan lengkap penerimaan barang, pengeluaran logistik, dan pembaruan data inventaris secara transparan.
            </p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-2xs">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Pencatatan Otomatis Aktif</span>
            </span>
        </div>
    </div>

    <!-- =========================================================================
     * STATISTIK WIDGET KARTU (Ringkasan Aktivitas Ramah Pengguna)
     * ========================================================================= -->
    <div class="stats-grid grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
        <!-- Kartu 1: Total Catatan -->
        <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-3 sm:p-5 shadow-2xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xs">
            <div class="flex items-center gap-2.5 sm:gap-4">
                <div class="stat-icon flex h-9 w-9 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="h-4 w-4 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="stat-val text-lg sm:text-2xl font-bold tracking-tight text-slate-900"><?= number_format((int)($summaryStats['total_logs'] ?? 0)) ?></div>
                    <div class="stat-label text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider truncate">Semua Catatan</div>
                </div>
            </div>
        </div>

        <!-- Kartu 2: Mutasi Keluar Masuk -->
        <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-3 sm:p-5 shadow-2xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xs">
            <div class="flex items-center gap-2.5 sm:gap-4">
                <div class="stat-icon flex h-9 w-9 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-4 w-4 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="stat-val text-lg sm:text-2xl font-bold tracking-tight text-emerald-600"><?= number_format((int)($summaryStats['total_mutasi'] ?? 0)) ?></div>
                    <div class="stat-label text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider truncate">Keluar Masuk Barang</div>
                </div>
            </div>
        </div>

        <!-- Kartu 3: Perubahan Data -->
        <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-3 sm:p-5 shadow-2xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xs">
            <div class="flex items-center gap-2.5 sm:gap-4">
                <div class="stat-icon flex h-9 w-9 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-4 w-4 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="stat-val text-lg sm:text-2xl font-bold tracking-tight text-amber-600"><?= number_format((int)($summaryStats['total_master'] ?? 0)) ?></div>
                    <div class="stat-label text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider truncate">Pembaruan Data</div>
                </div>
            </div>
        </div>

        <!-- Kartu 4: Aktivitas Pengguna -->
        <div class="stat-card rounded-2xl border border-slate-200/80 bg-white p-3 sm:p-5 shadow-2xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xs">
            <div class="flex items-center gap-2.5 sm:gap-4">
                <div class="stat-icon flex h-9 w-9 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                    <svg class="h-4 w-4 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="stat-val text-lg sm:text-2xl font-bold tracking-tight text-purple-600"><?= number_format((int)($summaryStats['total_user_ops'] ?? 0)) ?></div>
                    <div class="stat-label text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider truncate">Aktivitas Pengguna</div>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================================
     * CONTAINER UTAMA: KARTU TABEL & FILTER PENCARIAN
     * ========================================================================= -->
    <div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        
        <!-- Header Kartu & Toolbar Penyaringan -->
        <div class="card-header p-4 sm:p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="card-title text-base sm:text-lg font-bold text-slate-900">
                    Daftar Riwayat Aktivitas & Mutasi
                </h2>
                <p class="card-subtitle text-xs text-slate-500 mt-0.5">
                    Informasi kronologis mutasi stok barang dan jejak aktivitas petugas.
                </p>
            </div>

            <!-- Formulir Filter & Pencarian Cepat -->
            <form action="<?= base_url('stock/history') ?>" method="GET" class="flex flex-wrap items-center gap-2">
                <input type="hidden" name="tab" id="inputTabQuery" value="<?= esc($selectedTab) ?>">

                <!-- Apple UISearchBar Component -->
                <div class="ios-search-bar w-full sm:w-auto sm:min-w-[210px] flex-1 sm:flex-initial">
                    <svg class="ios-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input 
                        type="text" 
                        name="q" 
                        class="ios-search-input truncate" 
                        placeholder="Cari riwayat..." 
                        value="<?= esc($searchQuery) ?>"
                    >
                    <?php if (!empty($searchQuery)): ?>
                        <a href="<?= base_url('stock/history' . (!empty($selectedTab) ? '?tab=' . urlencode($selectedTab) : '')) ?>" 
                           class="ios-search-clear" 
                           title="Hapus pencarian">
                            <svg class="h-2.5 w-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Dropdown Kategori Data -->
                <select name="module" class="h-[38px] text-xs px-3 rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer" onchange="this.form.submit()">
                    <option value="">Semua Data</option>
                    <option value="STOCK" <?= ($selectedModule === 'STOCK') ? 'selected' : '' ?>>Mutasi Barang (Stok)</option>
                    <option value="PRODUCT" <?= ($selectedModule === 'PRODUCT') ? 'selected' : '' ?>>Katalog Produk</option>
                    <option value="SUPPLIER" <?= ($selectedModule === 'SUPPLIER') ? 'selected' : '' ?>>Pemasok (Vendor)</option>
                    <option value="CUSTOMER" <?= ($selectedModule === 'CUSTOMER') ? 'selected' : '' ?>>Pelanggan (Klien)</option>
                    <option value="CATEGORY" <?= ($selectedModule === 'CATEGORY') ? 'selected' : '' ?>>Kategori Produk</option>
                    <option value="USER" <?= ($selectedModule === 'USER') ? 'selected' : '' ?>>Pengguna & Akun</option>
                    <option value="AUTH" <?= ($selectedModule === 'AUTH') ? 'selected' : '' ?>>Sesi Masuk / Keluar</option>
                </select>

                <!-- Dropdown Jenis Aktivitas -->
                <select name="action" class="h-[38px] text-xs px-3 rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer" onchange="this.form.submit()">
                    <option value="">Semua Aktivitas</option>
                    <option value="STOCK_IN" <?= ($selectedAction === 'STOCK_IN') ? 'selected' : '' ?>>Penerimaan Barang (Masuk)</option>
                    <option value="STOCK_OUT" <?= ($selectedAction === 'STOCK_OUT') ? 'selected' : '' ?>>Pengeluaran Barang (Keluar)</option>
                    <option value="STOCK_ADJUSTMENT" <?= ($selectedAction === 'STOCK_ADJUSTMENT') ? 'selected' : '' ?>>Penyesuaian Fisik (Opname)</option>
                    <option value="CREATE" <?= ($selectedAction === 'CREATE') ? 'selected' : '' ?>>Penambahan Data Baru</option>
                    <option value="UPDATE" <?= ($selectedAction === 'UPDATE') ? 'selected' : '' ?>>Pembaruan / Edit Data</option>
                    <option value="DELETE" <?= ($selectedAction === 'DELETE') ? 'selected' : '' ?>>Penghapusan Data</option>
                    <option value="LOGIN" <?= ($selectedAction === 'LOGIN') ? 'selected' : '' ?>>Masuk ke Sistem</option>
                    <option value="LOGOUT" <?= ($selectedAction === 'LOGOUT') ? 'selected' : '' ?>>Keluar dari Sistem</option>
                </select>

                <?php if (!empty($searchQuery) || !empty($selectedModule) || !empty($selectedAction)): ?>
                    <a href="<?= base_url('stock/history') ?>" class="h-[38px] inline-flex items-center text-xs px-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all font-semibold" title="Reset filter">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Quick Switcher Tab: Apple iOS Segmented Control -->
        <div class="px-4 sm:px-6 py-2.5 bg-slate-50/50 border-b border-slate-100 overflow-x-auto">
            <div class="ios-segmented-control">
                <button type="button" id="tabBtnActivity" onclick="switchAuditTab('activity')" class="ios-segmented-item <?= ($selectedTab !== 'stock') ? 'active' : '' ?>">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span>Jejak Aktivitas & Perubahan Data</span>
                    <span class="inline-flex items-center justify-center rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-bold px-1.5 py-0.5 ml-0.5"><?= count($activityLogs) ?></span>
                </button>
                <button type="button" id="tabBtnStock" onclick="switchAuditTab('stock')" class="ios-segmented-item <?= ($selectedTab === 'stock') ? 'active' : '' ?>">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                    <span>Riwayat Mutasi Stok Fisik</span>
                    <span class="inline-flex items-center justify-center rounded-full bg-slate-200/80 text-slate-700 text-[10px] font-bold px-1.5 py-0.5 ml-0.5"><?= count($movements) ?></span>
                </button>
            </div>
        </div>

        <!-- =========================================================================
         * PANEL 1: JEJAK AKTIVITAS & PERUBAHAN DATA (BAHASA MANUSIA)
         * ========================================================================= -->
        <div id="panelActivity" class="<?= ($selectedTab === 'stock') ? 'hidden' : '' ?>">
            
            <!-- Desktop Clean Table -->
            <div class="table-responsive desktop-table-view overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200/80">
                        <tr>
                            <th class="py-3.5 px-4 w-32">Waktu</th>
                            <th class="py-3.5 px-4 w-44">Jenis Aktivitas</th>
                            <th class="py-3.5 px-4 w-48">Barang / Data Terkait</th>
                            <th class="py-3.5 px-4">Keterangan Aktivitas</th>
                            <th class="py-3.5 px-4 w-44">Dilakukan Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($activityLogs)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-16 px-4 text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mb-1">
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                        </div>
                                        <strong class="text-sm font-bold text-slate-800">Belum Ada Aktivitas Tercatat</strong>
                                        <span class="text-xs text-slate-500 max-w-sm">Setiap penerimaan barang, pengeluaran, pembaruan master data, atau login akan langsung tampil rapi di sini.</span>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($activityLogs as $log): 
                                $actInfo = $humanActionInfo($log['action']);
                                $modName = $humanModuleName($log['module']);
                            ?>
                                <tr class="hover:bg-slate-50/75 transition-colors">
                                    
                                    <!-- Waktu -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-800 leading-tight"><?= date('d M Y', strtotime($log['created_at'])) ?></div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5"><?= date('H:i:s', strtotime($log['created_at'])) ?> WIB</div>
                                    </td>

                                    <!-- Jenis Aktivitas & Bagian -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1 items-start">
                                            <span class="<?= $actInfo['badge'] ?> text-[10px]">
                                                <span class="h-1.5 w-1.5 rounded-full <?= $actInfo['dot'] ?>"></span>
                                                <span><?= esc($actInfo['label']) ?></span>
                                            </span>
                                            <span class="text-[9px] font-semibold text-slate-400">
                                                Bagian: <?= esc($modName) ?>
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Barang / Data Terkait -->
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-900 leading-snug line-clamp-1 max-w-[200px]" title="<?= esc($log['item_name'] ?? '') ?>">
                                            <?= esc($log['item_name'] ?? 'Data ' . $modName) ?>
                                        </div>
                                        <?php if (!empty($log['record_id']) && empty($log['item_name'])): ?>
                                            <span class="text-[10px] text-slate-400">Nomor urut: #<?= esc($log['record_id']) ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Keterangan & Rincian Perubahan (Bahasa Manusia) -->
                                    <td class="py-3.5 px-4">
                                        <div class="text-xs text-slate-700 leading-relaxed font-normal">
                                            <?= esc($log['description']) ?>
                                        </div>

                                        <!-- Collapsible Field Diff Ramah Pengguna -->
                                        <?php if (!empty($log['details'])): ?>
                                            <?php $diffDetails = json_decode($log['details'], true); ?>
                                            <?php if (is_array($diffDetails) && !empty($diffDetails)): ?>
                                                <details class="mt-1.5 group">
                                                    <summary class="inline-flex items-center gap-1 text-[11px] font-semibold text-indigo-600 hover:text-indigo-800 transition-colors cursor-pointer select-none">
                                                        <svg class="h-3 w-3 group-open:rotate-90 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                                        <span>Lihat rincian data yang diubah</span>
                                                    </summary>
                                                    <div class="mt-1.5 p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-700 space-y-1">
                                                        <?php foreach ($diffDetails as $field => $val): ?>
                                                            <?php 
                                                                $friendlyField = $humanFieldName((string)$field);
                                                            ?>
                                                            <?php if (is_array($val) && isset($val['sebelum']) && isset($val['sesudah'])): ?>
                                                                <div class="flex items-center gap-2 py-0.5 flex-wrap">
                                                                    <span class="font-semibold text-slate-800 min-w-[120px]"><?= esc($friendlyField) ?>:</span>
                                                                    <span class="text-rose-600 line-through text-[11px]"><?= esc($formatHumanValue((string)$field, $val['sebelum'])) ?></span>
                                                                    <span class="text-slate-400">&rarr;</span>
                                                                    <span class="text-emerald-700 font-bold"><?= esc($formatHumanValue((string)$field, $val['sesudah'])) ?></span>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="py-0.5">
                                                                    <span class="font-semibold text-slate-800"><?= esc($friendlyField) ?>:</span>
                                                                    <span class="text-slate-600 ml-1"><?= esc($formatHumanValue((string)$field, $val)) ?></span>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </details>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Dilakukan Oleh -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full <?= ($log['user_role'] === 'admin') ? 'bg-indigo-600' : 'bg-emerald-600' ?> text-white text-[10px] font-bold shadow-2xs">
                                                <?= strtoupper(substr($log['user_name'], 0, 1)) ?>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-semibold text-slate-900 truncate leading-none"><?= esc($log['user_name']) ?></div>
                                                <div class="flex items-center gap-1 mt-1">
                                                    <span class="text-[9px] font-bold uppercase tracking-wider <?= ($log['user_role'] === 'admin') ? 'text-indigo-600' : 'text-emerald-600' ?>">
                                                        <?= ($log['user_role'] === 'admin') ? 'Administrator' : 'Staf Gudang' ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Streamlined View: Apple iOS Inset Grouped Table View -->
            <div class="mobile-grouped-view p-3 bg-slate-50/50">
                <?php if (empty($activityLogs)): ?>
                    <div class="text-center py-12 px-4 text-slate-400 text-xs">
                        Belum ada aktivitas terekam.
                    </div>
                <?php else: ?>
                    <div class="ios-grouped-list">
                        <?php foreach ($activityLogs as $log): 
                            $act = $log['action'] ?? '';
                            $actInfo = $humanActionInfo($act);
                            $modName = $humanModuleName($log['module'] ?? '');
                            
                            // Squircle Icon
                            $iconBg = 'bg-slate-100 text-slate-600';
                            if (in_array($act, ['CREATE', 'STOCK_IN'], true)) {
                                $iconBg = 'bg-emerald-50 text-emerald-600';
                            } elseif (in_array($act, ['DELETE', 'STOCK_OUT'], true)) {
                                $iconBg = 'bg-rose-50 text-rose-600';
                            } elseif (in_array($act, ['UPDATE', 'STOCK_ADJUSTMENT'], true)) {
                                $iconBg = 'bg-amber-50 text-amber-600';
                            } elseif (($log['module'] ?? '') === 'USER' || ($log['module'] ?? '') === 'AUTH') {
                                $iconBg = 'bg-purple-50 text-purple-600';
                            } else {
                                $iconBg = 'bg-indigo-50 text-indigo-600';
                            }
                        ?>
                            <div class="ios-list-row items-start py-3">
                                <!-- Squircle Icon Khas iOS -->
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl <?= $iconBg ?> font-bold text-xs shadow-2xs mt-0.5">
                                    <?php if ($act === 'STOCK_IN'): ?>
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    <?php elseif ($act === 'STOCK_OUT'): ?>
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="17 14 12 9 7 14"></polyline><line x1="12" y1="9" x2="12" y2="21"></line></svg>
                                    <?php elseif ($act === 'CREATE'): ?>
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    <?php elseif ($act === 'DELETE'): ?>
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    <?php elseif ($act === 'UPDATE'): ?>
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    <?php elseif ($act === 'LOGIN' || $act === 'LOGOUT'): ?>
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                                    <?php else: ?>
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                                    <?php endif; ?>
                                </div>

                                <!-- Konten Detail Aktivitas -->
                                <div class="min-w-0 flex-1">
                                    <!-- Baris 1: Judul Barang/Entitas & Jam -->
                                    <div class="flex items-center justify-between gap-2">
                                        <h3 class="text-xs sm:text-sm font-semibold text-slate-900 truncate leading-snug">
                                            <?= esc($log['item_name'] ?? 'Data ' . $modName) ?>
                                        </h3>
                                        <span class="text-[10px] font-mono text-slate-400 shrink-0">
                                            <?= date('H:i', strtotime($log['created_at'])) ?> WIB
                                        </span>
                                    </div>

                                    <!-- Baris 2: Bagian & Badge Aktivitas -->
                                    <div class="flex items-center gap-1.5 mt-0.5 text-[11px] flex-wrap">
                                        <span class="font-semibold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded text-[10px]">
                                            <?= esc($modName) ?>
                                        </span>
                                        <span>&bull;</span>
                                        <span class="<?= $actInfo['badge'] ?> text-[10px]">
                                            <?= esc($actInfo['label']) ?>
                                        </span>
                                    </div>

                                    <!-- Baris 3: Deskripsi Aktivitas -->
                                    <p class="text-[11px] text-slate-600 mt-1 leading-relaxed">
                                        <?= esc($log['description']) ?>
                                    </p>

                                    <!-- Collapsible Diff Ramah Pengguna -->
                                    <?php if (!empty($log['details'])): ?>
                                        <?php $diffDetailsMob = json_decode($log['details'], true); ?>
                                        <?php if (is_array($diffDetailsMob) && !empty($diffDetailsMob)): ?>
                                            <details class="mt-1.5 group">
                                                <summary class="inline-flex items-center gap-1 text-[10px] font-semibold text-indigo-600 cursor-pointer select-none">
                                                    <svg class="h-2.5 w-2.5 group-open:rotate-90 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                                    <span>Lihat rincian perubahan</span>
                                                </summary>
                                                <div class="mt-1 p-2 rounded-lg bg-slate-50 border border-slate-200/70 text-[11px] text-slate-700 space-y-1">
                                                    <?php foreach ($diffDetailsMob as $field => $val): ?>
                                                        <?php $friendlyField = $humanFieldName((string)$field); ?>
                                                        <?php if (is_array($val) && isset($val['sebelum']) && isset($val['sesudah'])): ?>
                                                            <div>
                                                                <span class="font-semibold text-slate-800"><?= esc($friendlyField) ?>:</span>
                                                                <span class="text-rose-600 line-through text-[10px] ml-1"><?= esc($formatHumanValue((string)$field, $val['sebelum'])) ?></span>
                                                                <span class="text-slate-400 mx-0.5">&rarr;</span>
                                                                <span class="text-emerald-700 font-bold"><?= esc($formatHumanValue((string)$field, $val['sesudah'])) ?></span>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </details>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <!-- Baris 4: Footer Petugas & Tanggal -->
                                    <div class="flex items-center justify-between text-[10px] text-slate-400 mt-2 pt-1.5 border-t border-slate-100/60">
                                        <span class="font-medium text-slate-700">
                                            <?= esc($log['user_name']) ?> <span class="text-[9px] font-bold text-slate-400">(<?= ($log['user_role'] === 'admin') ? 'Admin' : 'Staf' ?>)</span>
                                        </span>
                                        <span>
                                            <?= date('d M Y', strtotime($log['created_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- =========================================================================
         * PANEL 2: BUKU MUTASI STOK FISIK (RAMAH OPERASIONAL)
         * ========================================================================= -->
        <div id="panelStock" class="<?= ($selectedTab !== 'stock') ? 'hidden' : '' ?>">
            
            <!-- Desktop Stock Ledger Table -->
            <div class="table-responsive desktop-table-view overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200/80">
                        <tr>
                            <th class="py-3.5 px-4 w-32">Tanggal & Waktu</th>
                            <th class="py-3.5 px-4 w-28">Kode SKU</th>
                            <th class="py-3.5 px-4">Nama Produk</th>
                            <th class="py-3.5 px-4 text-center w-28">Status Mutasi</th>
                            <th class="py-3.5 px-4 text-center w-24">Jumlah</th>
                            <th class="py-3.5 px-4 text-center w-32">Perubahan Stok</th>
                            <th class="py-3.5 px-4 w-36">Petugas</th>
                            <th class="py-3.5 px-4">Catatan / Dokumen Acuan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($movements)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-16 px-4 text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mb-1">
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline></svg>
                                        </div>
                                        <strong class="text-sm font-bold text-slate-800">Belum Ada Transaksi Keluar Masuk</strong>
                                        <span class="text-xs text-slate-500 max-w-sm">Setiap penerimaan barang masuk, pengeluaran ke klien, atau koreksi opname fisik akan tercatat di sini.</span>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($movements as $m): ?>
                                <tr class="hover:bg-slate-50/75 transition-colors">
                                    
                                    <!-- Waktu -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-800 leading-tight"><?= date('d M Y', strtotime($m['created_at'])) ?></div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5"><?= date('H:i', strtotime($m['created_at'])) ?> WIB</div>
                                    </td>

                                    <!-- SKU -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200/60 px-2 py-0.5 rounded-md">
                                            <?= esc($m['kode_produk'] ?? '-') ?>
                                        </span>
                                    </td>

                                    <!-- Produk & Info Relasi -->
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-900 leading-snug"><?= esc($m['nama_produk'] ?? 'Produk Dihapus') ?></div>
                                        <div class="text-[10px] text-slate-400"><?= esc($m['kategori'] ?? '') ?></div>
                                        <?php if (!empty($m['nama_supplier'])): ?>
                                            <div class="text-[10px] text-indigo-600 mt-0.5 inline-flex items-center gap-1 font-semibold bg-indigo-50/70 px-1.5 py-0.2 rounded">
                                                <span>Pemasok: <strong><?= esc($m['nama_supplier']) ?></strong></span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($m['nama_pelanggan'])): ?>
                                            <div class="text-[10px] text-rose-600 mt-0.5 inline-flex items-center gap-1 font-semibold bg-rose-50/70 px-1.5 py-0.2 rounded">
                                                <span>Penerima: <strong><?= esc($m['nama_pelanggan']) ?></strong></span>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Status Mutasi -->
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <?php if ($m['tipe'] === 'IN'): ?>
                                            <span class="ios-badge-success text-[10px]">
                                                <svg class="h-2.5 w-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                                <span>Masuk (IN)</span>
                                            </span>
                                        <?php elseif ($m['tipe'] === 'OUT'): ?>
                                            <span class="ios-badge-danger text-[10px]">
                                                <svg class="h-2.5 w-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="17 14 12 9 7 14"></polyline><line x1="12" y1="9" x2="12" y2="21"></line></svg>
                                                <span>Keluar (OUT)</span>
                                            </span>
                                        <?php else: ?>
                                            <span class="ios-badge-warning text-[10px]">
                                                <span>Penyesuaian</span>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Jumlah -->
                                    <td class="py-3.5 px-4 text-center font-bold text-xs whitespace-nowrap <?= ($m['tipe'] === 'IN') ? 'text-emerald-700' : (($m['tipe'] === 'OUT') ? 'text-rose-700' : 'text-slate-800') ?>">
                                        <?= ($m['tipe'] === 'IN') ? '+' : (($m['tipe'] === 'OUT') ? '-' : '') ?><?= (int)$m['jumlah'] ?> Unit
                                    </td>

                                    <!-- Perubahan Stok -->
                                    <td class="py-3.5 px-4 text-center text-xs font-mono whitespace-nowrap">
                                        <span class="text-slate-500"><?= (int)$m['stok_sebelum'] ?></span>
                                        <span class="text-slate-300 mx-1">&rarr;</span>
                                        <strong class="text-slate-900"><?= (int)$m['stok_sesudah'] ?></strong>
                                    </td>

                                    <!-- Petugas -->
                                    <td class="py-3.5 px-4 text-xs text-slate-600 whitespace-nowrap">
                                        <?= esc($m['operator'] ?? 'Sistem') ?>
                                    </td>

                                    <!-- Catatan / Dokumen -->
                                    <td class="py-3.5 px-4 text-xs text-slate-500">
                                        <?= !empty($m['keterangan']) ? esc($m['keterangan']) : '<span class="text-slate-300">-</span>' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Stock Ledger: Apple iOS Inset Grouped Table View -->
            <div class="mobile-grouped-view p-3 bg-slate-50/50">
                <?php if (empty($movements)): ?>
                    <div class="text-center py-12 px-4 text-slate-400 text-xs">
                        Belum ada mutasi fisik.
                    </div>
                <?php else: ?>
                    <div class="ios-grouped-list">
                        <?php foreach ($movements as $m): ?>
                            <div class="ios-list-row items-start py-3">
                                <!-- Squircle Tipe Mutasi -->
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl <?= ($m['tipe'] === 'IN') ? 'bg-emerald-50 text-emerald-600' : (($m['tipe'] === 'OUT') ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600') ?> font-bold text-xs shadow-2xs mt-0.5">
                                    <?php if ($m['tipe'] === 'IN'): ?>
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    <?php elseif ($m['tipe'] === 'OUT'): ?>
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="17 14 12 9 7 14"></polyline><line x1="12" y1="9" x2="12" y2="21"></line></svg>
                                    <?php else: ?>
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 16.5-3.5z"></path></svg>
                                    <?php endif; ?>
                                </div>

                                <!-- Konten Detail Mutasi -->
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <h3 class="text-xs sm:text-sm font-semibold text-slate-900 truncate leading-snug">
                                            <?= esc($m['nama_produk'] ?? 'Produk Dihapus') ?>
                                        </h3>
                                        <span class="text-xs font-bold shrink-0 <?= ($m['tipe'] === 'IN') ? 'text-emerald-700' : (($m['tipe'] === 'OUT') ? 'text-rose-700' : 'text-slate-800') ?>">
                                            <?= ($m['tipe'] === 'IN') ? '+' : (($m['tipe'] === 'OUT') ? '-' : '') ?><?= (int)$m['jumlah'] ?> Unit
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-500">
                                        <span class="font-mono text-[10px] font-bold text-indigo-700 bg-indigo-50/80 px-1.5 py-0.5 rounded">
                                            <?= esc($m['kode_produk'] ?? '-') ?>
                                        </span>
                                        <span>&bull;</span>
                                        <?php if ($m['tipe'] === 'IN'): ?>
                                            <span class="ios-badge-success text-[10px]">Barang Masuk</span>
                                        <?php elseif ($m['tipe'] === 'OUT'): ?>
                                            <span class="ios-badge-danger text-[10px]">Barang Keluar</span>
                                        <?php else: ?>
                                            <span class="ios-badge-warning text-[10px]">Penyesuaian Opname</span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($m['nama_supplier'])): ?>
                                        <div class="text-[11px] text-indigo-600 mt-1 inline-flex items-center gap-1 font-medium bg-indigo-50/60 px-2 py-0.5 rounded">
                                            <span>Pemasok: <strong><?= esc($m['nama_supplier']) ?></strong></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($m['nama_pelanggan'])): ?>
                                        <div class="text-[11px] text-rose-600 mt-1 inline-flex items-center gap-1 font-medium bg-rose-50/60 px-2 py-0.5 rounded">
                                            <span>Penerima: <strong><?= esc($m['nama_pelanggan']) ?></strong></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($m['keterangan'])): ?>
                                        <p class="text-[11px] text-slate-500 mt-1 italic">
                                            "<?= esc($m['keterangan']) ?>"
                                        </p>
                                    <?php endif; ?>

                                    <div class="flex items-center justify-between gap-2 mt-2 pt-1.5 border-t border-slate-100/60 text-[10px] text-slate-400">
                                        <span class="font-mono">
                                            Stok: <?= (int)$m['stok_sebelum'] ?> &rarr; <strong class="text-slate-700"><?= (int)$m['stok_sesudah'] ?></strong>
                                        </span>
                                        <span>
                                            <?= date('d/m/Y H:i', strtotime($m['created_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- =========================================================================
 * CLIENT-SIDE INTERACTIVE TAB SWITCHER (APPLE SEGMENTED CONTROLLER)
 * ========================================================================= -->
<script>
function switchAuditTab(tab) {
    const btnActivity = document.getElementById('tabBtnActivity');
    const btnStock = document.getElementById('tabBtnStock');
    const panelActivity = document.getElementById('panelActivity');
    const panelStock = document.getElementById('panelStock');
    const inputTab = document.getElementById('inputTabQuery');

    if (tab === 'stock') {
        if (inputTab) inputTab.value = 'stock';
        btnStock.classList.add('active');
        btnActivity.classList.remove('active');

        panelStock.classList.remove('hidden');
        panelActivity.classList.add('hidden');

        const url = new URL(window.location);
        url.searchParams.set('tab', 'stock');
        window.history.replaceState({}, '', url);
    } else {
        if (inputTab) inputTab.value = 'activity';
        btnActivity.classList.add('active');
        btnStock.classList.remove('active');

        panelActivity.classList.remove('hidden');
        panelStock.classList.add('hidden');

        const url = new URL(window.location);
        url.searchParams.delete('tab');
        window.history.replaceState({}, '', url);
    }
}
</script>

<?= $this->endSection() ?>
