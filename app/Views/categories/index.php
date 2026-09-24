<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- =========================================================================
 * HEADER & DAFTAR KATEGORI PRODUK
 * ========================================================================= -->
<div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
    <div class="card-header p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="card-title text-lg font-bold text-slate-900 flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 9h16"></path>
                        <path d="M4 15h16"></path>
                        <rect x="2" y="3" width="20" height="18" rx="2"></rect>
                    </svg>
                </div>
                <span>Master Kategori Produk</span>
            </h2>
            <p class="card-subtitle text-xs text-slate-500 mt-1">Kelola klasifikasi dan pengelompokan produk inventaris Anda.</p>
        </div>

        <?php if (has_role('admin')): ?>
            <div class="card-header-actions flex items-center gap-3">
                <a href="<?= base_url('categories/new') ?>" class="btn btn-primary inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-xs shadow-indigo-600/20 hover:bg-indigo-700 transition-all">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Tambah Kategori</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tabel Data Kategori Responsif (Desktop & Tablet) -->
    <div class="table-responsive desktop-table-view overflow-x-auto">
        <table class="table w-full text-left text-sm text-slate-700">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200/80">
                <tr>
                    <th class="py-3.5 px-4 text-center w-12">No</th>
                    <th class="py-3.5 px-4">Nama Kategori</th>
                    <th class="py-3.5 px-4">Deskripsi Kategori</th>
                    <th class="py-3.5 px-4 text-center w-36">Jumlah Produk</th>
                    <th class="py-3.5 px-4">Waktu Dibuat</th>
                    <th class="py-3.5 px-4 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-16 px-4 text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mb-1">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 9h16"></path>
                                        <path d="M4 15h16"></path>
                                        <rect x="2" y="3" width="20" height="18" rx="2"></rect>
                                    </svg>
                                </div>
                                <strong class="text-sm font-bold text-slate-800">Belum Ada Data Kategori</strong>
                                <span class="text-xs text-slate-500 max-w-sm">
                                    Silakan klik tombol "Tambah Kategori" di atas untuk menambahkan klasifikasi pertama.
                                </span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($categories as $cat): ?>
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-xs text-slate-400"><?= $no++ ?></td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 font-bold text-xs border border-indigo-200/60">
                                    <?= esc($cat['nama_kategori']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600">
                                <?= !empty($cat['deskripsi']) ? esc($cat['deskripsi']) : '<span class="text-slate-400 italic">- Tidak ada deskripsi -</span>' ?>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="badge inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold <?= ((int)$cat['total_produk'] > 0) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60' ?>">
                                    <?= (int)$cat['total_produk'] ?> Produk
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-500 whitespace-nowrap">
                                <?= !empty($cat['created_at']) ? date('d M Y, H:i', strtotime($cat['created_at'])) : '-' ?>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <?php if (has_role('admin')): ?>
                                    <div class="inline-flex items-center gap-1.5">
                                        <!-- Tombol Edit -->
                                        <a href="<?= base_url('categories/edit/' . $cat['id']) ?>" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 transition-all shadow-2xs" title="Edit Kategori">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>

                                        <!-- Tombol Hapus (Memicu Modal Konfirmasi) -->
                                        <button 
                                            type="button" 
                                            class="btn-trigger-delete flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-white text-rose-600 hover:bg-rose-50 hover:border-rose-300 transition-all shadow-2xs cursor-pointer" 
                                            data-id="<?= $cat['id'] ?>" 
                                            data-name="<?= esc($cat['nama_kategori']) ?>"
                                            title="Hapus Kategori"
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-slate-400">
                                        Hanya Lihat
                                    </span>
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
        <?php if (empty($categories)): ?>
            <div class="text-center py-12 px-4 text-slate-400 text-xs">
                Belum ada data kategori.
            </div>
        <?php else: ?>
            <div class="ios-grouped-list">
                <?php foreach ($categories as $cat): ?>
                    <div class="ios-list-row">
                        <!-- Squircle Inisial Kategori Khas iOS -->
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700 font-bold text-xs shadow-2xs">
                            <?= strtoupper(substr($cat['nama_kategori'] ?? 'K', 0, 2)) ?>
                        </div>

                        <!-- Data Utama Kategori -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-900 truncate leading-snug">
                                    <?= esc($cat['nama_kategori']) ?>
                                </h3>
                                <span class="ios-badge-success text-[10px] shrink-0">
                                    <?= (int)$cat['total_produk'] ?> Produk
                                </span>
                            </div>

                            <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                <?= !empty($cat['deskripsi']) ? esc($cat['deskripsi']) : '<span class="italic text-slate-400">Tanpa deskripsi</span>' ?>
                            </p>

                            <!-- Baris Aksi Mobile -->
                            <?php if (has_role('admin')): ?>
                                <div class="flex items-center justify-end gap-1.5 mt-2 pt-1.5 border-t border-slate-100/60">
                                    <a href="<?= base_url('categories/edit/' . $cat['id']) ?>" class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-700 active:bg-slate-200 transition-colors" title="Edit" aria-label="Edit">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <button 
                                        type="button" 
                                        class="btn-trigger-delete flex h-7 w-7 items-center justify-center rounded-lg bg-rose-50 text-rose-600 active:bg-rose-100 transition-colors cursor-pointer" 
                                        data-id="<?= $cat['id'] ?>" 
                                        data-name="<?= esc($cat['nama_kategori']) ?>"
                                        title="Hapus"
                                        aria-label="Hapus"
                                    >
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- =========================================================================
 * MODAL KONFIRMASI HAPUS KATEGORI
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
            Konfirmasi Hapus Kategori
        </h3>
        <p class="text-xs text-slate-500 leading-relaxed mb-6">
            Apakah Anda yakin ingin menghapus kategori <strong id="deleteItemName" class="text-rose-600 font-bold">-</strong>? Kategori yang masih memiliki produk aktif tidak dapat dihapus.
        </p>

        <!-- Formulir Hapus Kategori dengan Proteksi CSRF -->
        <form id="deleteForm" method="POST" action="" data-base-action="<?= base_url('categories/delete') ?>">
            <?= csrf_field() ?>
            <div class="modal-actions flex items-center justify-end gap-2.5">
                <button type="button" class="btn btn-secondary rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all cursor-pointer" id="btnCancelDelete">Batal</button>
                <button type="submit" class="btn btn-danger rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-rose-700 transition-all cursor-pointer">Ya, Hapus Kategori</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
