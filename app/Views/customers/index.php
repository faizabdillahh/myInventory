<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Header Halaman & Tombol Aksi Tambah -->
<div class="card rounded-2xl border border-slate-200/80 bg-white p-5 sm:p-6 shadow-xs mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="page-title text-xl font-extrabold tracking-tight text-slate-900 mb-1">Master Data Pelanggan & Departemen</h1>
            <p class="text-xs text-slate-500">
                Kelola data entitas tujuan barang keluar (klien bisnis, perorangan, atau divisi/departemen internal).
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="<?= base_url('customers/new') ?>" class="btn btn-primary inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-xs shadow-indigo-600/20 hover:bg-indigo-700 transition-all">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Pelanggan Baru</span>
            </a>
        </div>
    </div>
</div>

<!-- Panel Pencarian & Tabel Pelanggan -->
<div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
    <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form action="<?= base_url('customers') ?>" method="GET" class="flex items-center gap-2 max-w-sm w-full">
            <div class="search-wrapper relative w-full">
                <svg class="search-icon absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input 
                    type="text" 
                    name="q" 
                    class="form-control w-full pl-9 pr-8 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all truncate" 
                    placeholder="Cari pelanggan..." 
                    value="<?= esc($searchQuery ?? '') ?>"
                >
                <?php if (!empty($searchQuery)): ?>
                    <a href="<?= base_url('customers') ?>" 
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
                <a href="<?= base_url('customers') ?>" class="btn btn-secondary text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 font-semibold transition-all">Reset</a>
            <?php endif; ?>
        </form>

        <span class="text-xs text-slate-500">
            Total: <strong class="text-slate-800 font-bold"><?= count($customers) ?></strong> entitas tujuan
        </span>
    </div>

    <!-- Tabel Data Pelanggan (Desktop & Tablet) -->
    <div class="table-responsive desktop-table-view overflow-x-auto">
        <table class="table w-full text-left text-sm text-slate-700">
            <thead class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200/80">
                <tr>
                    <th class="py-3.5 px-4 text-center w-12">No</th>
                    <th class="py-3.5 px-4 w-32">Kode</th>
                    <th class="py-3.5 px-4">Nama Pelanggan / Departemen</th>
                    <th class="py-3.5 px-4 text-center w-32">Tipe</th>
                    <th class="py-3.5 px-4">Kontak Person & Saluran</th>
                    <th class="py-3.5 px-4 text-center w-36">Barang Diserap</th>
                    <th class="py-3.5 px-4 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-16 px-4 text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mb-1">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                </div>
                                <strong class="text-sm font-bold text-slate-800">
                                    <?= !empty($searchQuery) ? 'Pelanggan Tidak Ditemukan' : 'Belum Ada Data Pelanggan / Departemen' ?>
                                </strong>
                                <span class="text-xs text-slate-500 max-w-sm">
                                    <?= !empty($searchQuery) 
                                        ? 'Tidak ada data yang cocok dengan "' . esc($searchQuery) . '".' 
                                        : 'Silakan klik tombol "Tambah Pelanggan Baru" untuk menambahkan data pertama.' ?>
                                </span>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($customers as $c): ?>
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-xs text-slate-400"><?= $no++ ?></td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200/60 px-2 py-0.5 rounded">
                                    <?= esc($c['kode_pelanggan']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900 text-sm"><?= esc($c['nama_pelanggan']) ?></div>
                                <?php if (!empty($c['alamat'])): ?>
                                    <div class="text-xs text-slate-500 mt-0.5 line-clamp-1">
                                        <?= esc(mb_strimwidth($c['alamat'], 0, 50, '...')) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <?php if ($c['tipe'] === 'DEPARTEMEN'): ?>
                                    <span class="badge inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60">Departemen</span>
                                <?php elseif ($c['tipe'] === 'BISNIS'): ?>
                                    <span class="badge inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200/60">Bisnis (B2B)</span>
                                <?php else: ?>
                                    <span class="badge inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">Individu</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                <?php if (!empty($c['kontak_person'])): ?>
                                    <div class="font-semibold text-slate-800"><?= esc($c['kontak_person']) ?></div>
                                <?php endif; ?>
                                <div class="flex items-center gap-2 text-slate-500 mt-0.5 flex-wrap">
                                    <?php if (!empty($c['telepon'])): ?>
                                        <span>Telp: <?= esc($c['telepon']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($c['email'])): ?>
                                        <span>&bull; Email: <?= esc($c['email']) ?></span>
                                    <?php endif; ?>
                                    <?php if (empty($c['telepon']) && empty($c['email'])): ?>
                                        <span class="text-slate-400">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="badge inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold <?= ($c['total_transaksi'] > 0) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-slate-100 text-slate-600 border border-slate-200' ?>">
                                    <?= number_format($c['total_unit_keluar'], 0, ',', '.') ?> Unit
                                </span>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    <?= (int)$c['total_transaksi'] ?>x pengiriman
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="<?= base_url('customers/edit/' . $c['id']) ?>" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 transition-all shadow-2xs" title="Edit Data">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <?php if (has_role('admin')): ?>
                                        <button 
                                            type="button" 
                                            class="btn btn-danger btn-delete-customer flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-white text-rose-600 hover:bg-rose-50 hover:border-rose-300 transition-all shadow-2xs cursor-pointer" 
                                            data-id="<?= $c['id'] ?>" 
                                            data-name="<?= esc($c['nama_pelanggan']) ?>"
                                            data-transactions="<?= (int)$c['total_transaksi'] ?>"
                                            title="Hapus Pelanggan"
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
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
        <?php if (empty($customers)): ?>
            <div class="text-center py-12 px-4 text-slate-400 text-xs">
                Belum ada data pelanggan.
            </div>
        <?php else: ?>
            <div class="ios-grouped-list">
                <?php foreach ($customers as $c): ?>
                    <div class="ios-list-row">
                        <!-- Squircle Inisial Pelanggan Khas iOS -->
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-600 font-bold text-xs shadow-2xs">
                            <?= strtoupper(substr($c['nama_pelanggan'] ?? 'C', 0, 2)) ?>
                        </div>

                        <!-- Data Utama Pelanggan -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-900 truncate leading-snug">
                                    <?= esc($c['nama_pelanggan']) ?>
                                </h3>
                                <span class="ios-badge-success text-[10px] shrink-0">
                                    <?= number_format($c['total_unit_keluar'], 0, ',', '.') ?> Unit
                                </span>
                            </div>

                            <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-500">
                                <span class="font-mono text-[10px] font-bold text-indigo-700 bg-indigo-50/80 px-1.5 py-0.5 rounded">
                                    <?= esc($c['kode_pelanggan']) ?>
                                </span>
                                <span>&bull;</span>
                                <span class="truncate"><?= esc($c['tipe']) ?></span>
                            </div>

                            <?php if (!empty($c['kontak_person']) || !empty($c['telepon'])): ?>
                                <div class="text-[11px] text-slate-600 mt-0.5 truncate">
                                    <?= esc($c['kontak_person'] ?? '') ?> <?= !empty($c['telepon']) ? '(' . esc($c['telepon']) . ')' : '' ?>
                                </div>
                            <?php endif; ?>

                            <!-- Baris Aksi Mobile -->
                            <div class="flex items-center justify-end gap-1.5 mt-2 pt-1.5 border-t border-slate-100/60">
                                <a href="<?= base_url('customers/edit/' . $c['id']) ?>" class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-700 active:bg-slate-200 transition-colors" title="Edit" aria-label="Edit">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>

                                <?php if (has_role('admin')): ?>
                                    <button 
                                        type="button" 
                                        class="btn btn-danger btn-delete-customer flex h-7 w-7 items-center justify-center rounded-lg bg-rose-50 text-rose-600 active:bg-rose-100 transition-colors cursor-pointer" 
                                        data-id="<?= $c['id'] ?>" 
                                        data-name="<?= esc($c['nama_pelanggan']) ?>"
                                        data-transactions="<?= (int)$c['total_transaksi'] ?>"
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

<!-- Modal Konfirmasi Hapus Pelanggan -->
<div id="deleteCustomerModal" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4" style="display: none;">
    <div class="modal-content w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="modal-header flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
            <h3 class="modal-title text-base font-bold text-slate-900">Konfirmasi Penghapusan Pelanggan</h3>
            <button type="button" class="modal-close text-slate-400 hover:text-slate-600 text-lg cursor-pointer" id="btnCloseDeleteCustomer">&times;</button>
        </div>
        <form id="formDeleteCustomer" method="POST" action="">
            <?= csrf_field() ?>
            <div class="modal-body space-y-3">
                <p class="text-xs text-slate-600 leading-relaxed">
                    Apakah Anda yakin ingin menghapus pelanggan <strong id="deleteCustomerName" class="text-slate-900 font-bold"></strong>?
                </p>
                <div id="deleteCustomerWarning" class="alert alert-warning rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-800" style="display: none;">
                    Perhatian: Pelanggan ini memiliki riwayat transaksi barang keluar. Penghapusan akan ditolak sistem untuk menjaga integritas data audit trail.
                </div>
            </div>
            <div class="modal-actions pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100 mt-4">
                <button type="button" class="btn btn-secondary rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer" id="btnCancelDeleteCustomer">Batal</button>
                <button type="submit" class="btn btn-danger rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-rose-700 cursor-pointer">Hapus Pelanggan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
