<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php $currentUserId = (int) session()->get('user_id'); ?>

<!-- STATISTIK RINGKASAN PENGGUNA -->
<div class="grid grid-cols-3 gap-3 sm:gap-4 mb-5 sm:mb-6">

    <div class="rounded-2xl border border-slate-200 bg-white p-3.5 sm:p-5 shadow-xs hover:shadow-sm hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-start gap-3">
            <div class="shrink-0 flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl text-white" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                <svg class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-none tracking-tight"><?= (int)($stats['total_users'] ?? 0) ?></p>
                <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wide mt-1">Total Pengguna</p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-indigo-100 bg-gradient-to-br from-indigo-50/60 to-white p-3.5 sm:p-5 shadow-xs hover:shadow-sm hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-start gap-3">
            <div class="shrink-0 flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl text-white" style="background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);">
                <svg class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xl sm:text-2xl font-extrabold text-indigo-700 leading-none tracking-tight"><?= (int)($stats['total_admin'] ?? 0) ?></p>
                <p class="text-[10px] sm:text-xs font-semibold text-indigo-500 uppercase tracking-wide mt-1">Administrator</p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50/60 to-white p-3.5 sm:p-5 shadow-xs hover:shadow-sm hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-start gap-3">
            <div class="shrink-0 flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl text-white" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                <svg class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xl sm:text-2xl font-extrabold text-emerald-700 leading-none tracking-tight"><?= (int)($stats['total_staff'] ?? 0) ?></p>
                <p class="text-[10px] sm:text-xs font-semibold text-emerald-600 uppercase tracking-wide mt-1">Staff Gudang</p>
            </div>
        </div>
    </div>

</div>

<!-- TABEL MANAJEMEN PENGGUNA -->
<div class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">

    <!-- Card Header -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 px-4 py-3.5 sm:px-5 sm:py-4 border-b border-slate-100">
        <div class="flex-1 min-w-0">
            <h1 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">Manajemen Pengguna & Hak Akses</h1>
            <p class="text-xs text-slate-400 mt-0.5 hidden sm:block">Kelola akun staf, administrator, dan wewenang akses sistem inventaris.</p>
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <form action="<?= base_url('users') ?>" method="GET" class="flex items-center gap-2 flex-1 sm:flex-none">
                <div class="ios-search-bar flex-1 sm:w-56">
                    <svg class="ios-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="q" class="ios-search-input" placeholder="Cari pengguna..." value="<?= esc($searchQuery) ?>" autocomplete="off">
                    <?php if (!empty($searchQuery)): ?>
                        <a href="<?= base_url('users') ?>" class="ios-search-clear" title="Hapus" aria-label="Hapus pencarian">
                            <svg class="h-2.5 w-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
                <?php if (!empty($searchQuery)): ?>
                    <a href="<?= base_url('users') ?>" class="shrink-0 inline-flex items-center h-[38px] px-3 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 bg-white hover:bg-slate-50 transition-colors">Reset</a>
                <?php endif; ?>
            </form>

            <a href="<?= base_url('users/new') ?>"
               class="shrink-0 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 font-bold text-white hover:bg-indigo-700 active:scale-95 transition-all whitespace-nowrap"
               style="height: 38px; font-size: 13px; box-shadow: 0 2px 8px rgba(79, 70, 229, 0.35);">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Pengguna</span>
            </a>
        </div>
    </div>

    <!-- TAMPILAN DESKTOP: Tabel -->
    <div class="desktop-table-view overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-700">
            <thead class="bg-slate-50/80 border-b border-slate-200/80">
                <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    <th class="py-3 px-4 text-center w-10">#</th>
                    <th class="py-3 px-4">Nama &amp; Username</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4 text-center w-36">Peran</th>
                    <th class="py-3 px-4 w-40">Terdaftar</th>
                    <th class="py-3 px-4 text-center w-36">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-700"><?= !empty($searchQuery) ? 'Pengguna tidak ditemukan' : 'Belum ada data pengguna' ?></p>
                                <p class="text-xs text-slate-400 max-w-xs">
                                    <?= !empty($searchQuery) ? 'Tidak ada akun yang cocok dengan "' . esc($searchQuery) . '".' : 'Klik "Tambah Pengguna" untuk mendaftarkan akun pertama.' ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($users as $u): ?>
                        <tr class="hover:bg-slate-50/60 transition-colors" data-id="<?= $u['id'] ?>" data-username="<?= esc($u['username']) ?>">
                            <td class="py-3.5 px-4 text-center text-xs font-bold text-slate-300"><?= $no++ ?></td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-bold text-white shadow-xs <?= ($u['role'] === 'admin') ? 'bg-gradient-to-br from-indigo-500 to-indigo-700' : 'bg-gradient-to-br from-emerald-500 to-teal-600' ?>">
                                        <?= strtoupper(substr($u['nama_lengkap'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-900 leading-snug text-sm">
                                            <?= esc($u['nama_lengkap']) ?>
                                            <?php if ($u['id'] === $currentUserId): ?>
                                                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60">Anda</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-xs text-slate-400 font-medium">@<?= esc($u['username']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-500"><?= esc($u['email']) ?></td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700 border border-indigo-200/60">
                                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                        Administrator
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-200/60">
                                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                        Staff Gudang
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-400 whitespace-nowrap"><?= esc(date('d M Y, H:i', strtotime($u['created_at'] ?? 'now'))) ?></td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="<?= base_url('users/edit/' . $u['id']) ?>"
                                       class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition-all"
                                       title="Edit Pengguna">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <?php if ($u['id'] !== $currentUserId): ?>
                                        <form action="<?= base_url('users/delete/' . $u['id']) ?>" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus akun <?= esc($u['username']) ?>? Tindakan ini tidak dapat dibatalkan.');">
                                            <?= csrf_field() ?>
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-rose-600 shadow-2xs hover:bg-rose-50 hover:border-rose-300 transition-all cursor-pointer"
                                                    title="Hapus Pengguna">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1.5 text-[11px] font-medium text-slate-300 select-none" title="Tidak dapat menghapus akun sendiri">Terkunci</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- TAMPILAN MOBILE: iOS Inset Grouped List -->
    <div class="mobile-grouped-view p-3 bg-slate-50/50">
        <?php if (empty($users)): ?>
            <p class="text-center py-10 text-xs text-slate-400"><?= !empty($searchQuery) ? 'Pengguna tidak ditemukan.' : 'Belum ada data pengguna.' ?></p>
        <?php else: ?>
            <div class="ios-grouped-list">
                <?php foreach ($users as $u): ?>
                    <div class="ios-list-row" data-id="<?= $u['id'] ?>" data-username="<?= esc($u['username']) ?>">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-xs font-bold text-white shadow-xs <?= ($u['role'] === 'admin') ? 'bg-gradient-to-br from-indigo-500 to-indigo-700' : 'bg-gradient-to-br from-emerald-500 to-teal-600' ?>">
                            <?= strtoupper(substr($u['nama_lengkap'], 0, 1)) ?>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-semibold text-slate-900 truncate leading-snug">
                                    <?= esc($u['nama_lengkap']) ?>
                                    <?php if ($u['id'] === $currentUserId): ?>
                                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60">Anda</span>
                                    <?php endif; ?>
                                </p>
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="shrink-0 inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-bold text-indigo-700 border border-indigo-200/50">
                                        <svg class="h-2.5 w-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                        Admin
                                    </span>
                                <?php else: ?>
                                    <span class="shrink-0 inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200/50">
                                        <svg class="h-2.5 w-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                        Staff
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5 truncate">@<?= esc($u['username']) ?> &bull; <?= esc($u['email']) ?></p>
                            <div class="flex items-center justify-between gap-2 mt-2 pt-1.5 border-t border-slate-100">
                                <span class="text-[10px] text-slate-400">Sejak <?= esc(date('d M Y', strtotime($u['created_at'] ?? 'now'))) ?></span>
                                <div class="flex items-center gap-1.5">
                                    <a href="<?= base_url('users/edit/' . $u['id']) ?>"
                                       class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-indigo-100 hover:text-indigo-700 active:scale-90 transition-all"
                                       title="Edit" aria-label="Edit">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <?php if ($u['id'] !== $currentUserId): ?>
                                        <form action="<?= base_url('users/delete/' . $u['id']) ?>" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus akun <?= esc($u['username']) ?>?');">
                                            <?= csrf_field() ?>
                                            <button type="submit"
                                                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 active:scale-90 transition-all cursor-pointer"
                                                    title="Hapus" aria-label="Hapus">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 text-slate-300 cursor-not-allowed" title="Akun sendiri">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                            </svg>
                                        </span>
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

<?= $this->endSection() ?>
