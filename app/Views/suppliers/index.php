<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- =========================================================================
 * HEADER & DAFTAR MASTER PEMASOK (SUPPLIERS)
 * ========================================================================= -->
<div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
    <div class="card-header p-5 sm:p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h2 class="card-title text-lg font-bold text-slate-900 flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="3" width="15" height="13"></rect>
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                    </svg>
                </div>
                <span>Master Data Pemasok (Suppliers)</span>
            </h2>
            <p class="card-subtitle text-xs text-slate-500 mt-1">Kelola daftar vendor dan mitra penyedia pasokan barang inventaris Anda.</p>
        </div>

        <div class="card-header-actions flex flex-col sm:flex-row sm:items-center gap-2.5 sm:gap-3 w-full sm:w-auto">
            <!-- Formulir Pencarian Supplier -->
            <form action="<?= base_url('suppliers') ?>" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                <div class="search-wrapper relative flex-1 sm:w-64">
                    <svg class="search-icon absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input 
                        type="text" 
                        name="q" 
                        class="search-input w-full pl-9 pr-8 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all truncate" 
                        placeholder="Cari pemasok..." 
                        value="<?= esc($searchQuery) ?>"
                    >
                    <?php if (!empty($searchQuery)): ?>
                        <a href="<?= base_url('suppliers') ?>" 
                           class="absolute right-2.5 top-1/2 -translate-y-1/2 flex h-4 w-4 items-center justify-center rounded-full bg-slate-300 text-white hover:bg-slate-400 transition-colors" 
                           title="Hapus pencarian" aria-label="Hapus pencarian">
                            <svg class="h-2.5 w-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($searchQuery)): ?>
                    <a href="<?= base_url('suppliers') ?>" class="btn btn-secondary text-xs px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all font-semibold" title="Hapus pencarian">Reset</a>
                <?php endif; ?>
            </form>

            <!-- Tombol Tambah Supplier Baru -->
            <a href="<?= base_url('suppliers/new') ?>" class="btn btn-primary inline-flex items-center justify-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-xs shadow-indigo-600/20 hover:bg-indigo-700 transition-all w-full sm:w-auto">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Supplier</span>
            </a>
        </div>
    </div>

    <!-- Tabel Data Supplier Responsif (Desktop & Tablet) -->
    <div class="table-responsive desktop-table-view overflow-x-auto">
        <table class="table w-full text-left text-sm text-slate-700">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200/80">
                <tr>
                    <th class="py-3.5 px-4 text-center w-12">No</th>
                    <th class="py-3.5 px-4">Kode & Nama Supplier</th>
                    <th class="py-3.5 px-4">Kontak Person (PIC)</th>
                    <th class="py-3.5 px-4">Telepon / Email</th>
                    <th class="py-3.5 px-4">Alamat Kantor / Gudang</th>
                    <th class="py-3.5 px-4 text-center w-36">Produk Dipasok</th>
                    <th class="py-3.5 px-4 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($suppliers)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-16 px-4 text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mb-1">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="1" y="3" width="15" height="13"></rect>
                                        <polygon points="16 8 20 8 23 11 23 16 16 16 8"></polygon>
                                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                    </svg>
                                </div>
                                <strong class="text-sm font-bold text-slate-800">
                                    <?= !empty($searchQuery) ? 'Supplier Tidak Ditemukan' : 'Belum Ada Data Supplier' ?>
                                </strong>
                                <span class="text-xs text-slate-500 max-w-sm">
                                    <?= !empty($searchQuery) 
                                        ? 'Tidak ada supplier yang cocok dengan kata kunci "' . esc($searchQuery) . '".' 
                                        : 'Silakan klik tombol "Tambah Supplier" di atas untuk mendaftarkan mitra pertama.' ?>
                                </span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($suppliers as $sup): ?>
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-xs text-slate-400"><?= $no++ ?></td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200/60 px-2 py-0.5 rounded">
                                        <?= esc($sup['kode_supplier']) ?>
                                    </span>
                                    <strong class="text-slate-900"><?= esc($sup['nama_supplier']) ?></strong>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-xs font-medium text-slate-700 whitespace-nowrap">
                                <?= !empty($sup['kontak_person']) ? esc($sup['kontak_person']) : '<span class="text-slate-400">-</span>' ?>
                            </td>
                            <td class="py-3.5 px-4 text-xs whitespace-nowrap">
                                <div class="font-medium text-slate-800">
                                    <?= !empty($sup['telepon']) ? esc($sup['telepon']) : '<span class="text-slate-400">-</span>' ?>
                                </div>
                                <?php if (!empty($sup['email'])): ?>
                                    <div class="text-[11px] text-slate-500 mt-0.5">
                                        <?= esc($sup['email']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600 max-w-xs">
                                <?= !empty($sup['alamat']) ? esc(mb_strimwidth($sup['alamat'], 0, 50, '...')) : '<span class="text-slate-400">-</span>' ?>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="badge inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold <?= ((int)$sup['total_produk'] > 0) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-slate-100 text-slate-600 border border-slate-200' ?>">
                                    <?= (int)$sup['total_produk'] ?> Produk
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Tombol Edit -->
                                    <a href="<?= base_url('suppliers/edit/' . $sup['id']) ?>" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 transition-all shadow-2xs" title="Edit Supplier">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>

                                    <?php if (has_role('admin')): ?>
                                        <!-- Tombol Hapus (Memicu Modal Konfirmasi) - Khusus Administrator -->
                                        <button 
                                            type="button" 
                                            class="btn-trigger-delete-supplier flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-white text-rose-600 hover:bg-rose-50 hover:border-rose-300 transition-all shadow-2xs cursor-pointer" 
                                            data-id="<?= $sup['id'] ?>" 
                                            data-name="<?= esc($sup['nama_supplier']) ?>"
                                            title="Hapus Supplier"
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
        <?php if (empty($suppliers)): ?>
            <div class="text-center py-12 px-4 text-slate-400 text-xs">
                Belum ada data pemasok.
            </div>
        <?php else: ?>
            <div class="ios-grouped-list">
                <?php foreach ($suppliers as $sup): ?>
                    <div class="ios-list-row">
                        <!-- Squircle Inisial Vendor Khas iOS -->
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 font-bold text-xs shadow-2xs">
                            <?= strtoupper(substr($sup['nama_supplier'] ?? 'S', 0, 2)) ?>
                        </div>

                        <!-- Data Utama Supplier -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-900 truncate leading-snug">
                                    <?= esc($sup['nama_supplier']) ?>
                                </h3>
                                <span class="ios-badge-success text-[10px] shrink-0">
                                    <?= (int)$sup['total_produk'] ?> Produk
                                </span>
                            </div>

                            <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-500">
                                <span class="font-mono text-[10px] font-bold text-indigo-700 bg-indigo-50/80 px-1.5 py-0.5 rounded">
                                    <?= esc($sup['kode_supplier']) ?>
                                </span>
                                <?php if (!empty($sup['kontak_person'])): ?>
                                    <span>&bull;</span>
                                    <span class="truncate">PIC: <?= esc($sup['kontak_person']) ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($sup['telepon'])): ?>
                                <div class="text-[11px] text-slate-600 mt-0.5">
                                    Tel: <?= esc($sup['telepon']) ?>
                                </div>
                            <?php endif; ?>

                            <!-- Baris Aksi Mobile -->
                            <div class="flex items-center justify-end gap-1.5 mt-2 pt-1.5 border-t border-slate-100/60">
                                <a href="<?= base_url('suppliers/edit/' . $sup['id']) ?>" class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-700 active:bg-slate-200 transition-colors" title="Edit" aria-label="Edit">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>

                                <?php if (has_role('admin')): ?>
                                    <button 
                                        type="button" 
                                        class="btn-trigger-delete-supplier flex h-7 w-7 items-center justify-center rounded-lg bg-rose-50 text-rose-600 active:bg-rose-100 transition-colors cursor-pointer" 
                                        data-id="<?= $sup['id'] ?>" 
                                        data-name="<?= esc($sup['nama_supplier']) ?>"
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
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- =========================================================================
 * MODAL KONFIRMASI HAPUS DATA SUPPLIER
 * ========================================================================= -->
<div class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 transition-all opacity-0 invisible" id="deleteSupplierModal">
    <div class="modal-box w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-200 transition-all">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 mb-4">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>

        <h3 class="text-base font-bold text-slate-900 mb-1.5">
            Konfirmasi Hapus Supplier
        </h3>
        <p class="text-xs text-slate-500 leading-relaxed mb-6">
            Apakah Anda yakin ingin menghapus data supplier <strong id="deleteSupplierName" class="text-rose-600 font-bold">-</strong>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <form id="deleteSupplierForm" method="POST" action="" data-base-action="<?= base_url('suppliers/delete') ?>">
            <?= csrf_field() ?>
            <div class="modal-actions flex items-center justify-end gap-2.5">
                <button type="button" class="btn btn-secondary rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all cursor-pointer" id="btnCancelDeleteSupplier">Batal</button>
                <button type="submit" class="btn btn-danger rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-rose-700 transition-all cursor-pointer">Ya, Hapus Data</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
