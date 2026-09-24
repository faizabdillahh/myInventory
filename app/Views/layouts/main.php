<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= esc($page_title ?? 'Sistem Manajemen Inventaris') ?> - InventarisPro</title>
    
    <!-- Integrasi Font Modern Google: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v4 (Kompilasi Statis Ter-minifikasi & Bebas CDN Eksternal) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.min.css') ?>">

    <!-- Stylesheet Kustom Pendukung (Print & Utility Helpers) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="flex min-h-full flex-col font-sans text-slate-800 bg-slate-50 selection:bg-indigo-500 selection:text-white">

    <!-- =========================================================================
     * NAVBAR UTAMA (ENTERPRISE GLASSMORPHISM)
     * ========================================================================= -->
    <header class="sticky top-0 z-40 w-full ios-nav-blur transition-all no-print">
        <div class="mx-auto flex h-14 sm:h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            
            <!-- Brand Logo & Lokasi Gudang Switcher -->
            <div class="flex items-center gap-3 lg:gap-6 min-w-0">
                <a href="<?= base_url('products') ?>" class="group flex items-center gap-2.5 transition-transform hover:scale-[1.01] shrink-0">
                    <div class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white shadow-md shadow-indigo-500/25 transition-all group-hover:shadow-indigo-500/40">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm sm:text-base font-extrabold tracking-tight text-slate-900 group-hover:text-indigo-600 transition-colors leading-none sm:leading-tight">InventarisPro</span>
                        <span class="text-[9px] sm:text-[10px] font-semibold uppercase tracking-wider text-slate-400 mt-0.5">Enterprise Edition</span>
                    </div>
                </a>

                <!-- Location / Warehouse Switcher Chip (Katana Pattern) -->
                <?php if (session()->get('isLoggedIn')): ?>
                    <div class="hidden xl:flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200/80 text-[11px] font-medium text-slate-600">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span>Gudang Utama (Pusat)</span>
                    </div>
                <?php endif; ?>

                <!-- Desktop Navigation Menu (Hidden on Mobile) -->
                <?php if (session()->get('isLoggedIn')): ?>
                    <nav class="hidden md:flex items-center gap-1 pl-3 border-l border-slate-200">
                        <!-- Menu Produk -->
                        <a href="<?= base_url('products') ?>" 
                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-all <?= (str_contains(uri_string(), 'products') || uri_string() === '') ? 'bg-indigo-50 text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                <line x1="12" y1="17" x2="12" y2="21"></line>
                            </svg>
                            <span>Produk</span>
                        </a>

                        <!-- Menu Kategori -->
                        <a href="<?= base_url('categories') ?>" 
                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-all <?= str_contains(uri_string(), 'categories') ? 'bg-indigo-50 text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 9h16"></path>
                                <path d="M4 15h16"></path>
                                <rect x="2" y="3" width="20" height="18" rx="2"></rect>
                            </svg>
                            <span>Kategori</span>
                        </a>

                        <!-- Menu Pemasok -->
                        <a href="<?= base_url('suppliers') ?>" 
                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-all <?= str_contains(uri_string(), 'suppliers') ? 'bg-indigo-50 text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                            <span>Pemasok</span>
                        </a>

                        <!-- Menu Pelanggan -->
                        <a href="<?= base_url('customers') ?>" 
                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-all <?= str_contains(uri_string(), 'customers') ? 'bg-indigo-50 text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Pelanggan</span>
                        </a>

                        <!-- Menu Riwayat Stok -->
                        <a href="<?= base_url('stock/history') ?>" 
                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-all <?= str_contains(uri_string(), 'stock') ? 'bg-indigo-50 text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                <polyline points="17 6 23 6 23 12"></polyline>
                            </svg>
                            <span>Riwayat</span>
                        </a>

                        <!-- Menu Laporan -->
                        <?php $reportsUrl = has_role('admin') ? base_url('reports') : base_url('reports/movements'); ?>
                        <a href="<?= $reportsUrl ?>" 
                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-all <?= str_contains(uri_string(), 'reports') ? 'bg-indigo-50 text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                            <span>Laporan</span>
                        </a>

                        <!-- Menu Manajemen Pengguna (Khusus Administrator) -->
                        <?php if (has_role('admin')): ?>
                            <a href="<?= base_url('users') ?>" 
                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-all <?= str_contains(uri_string(), 'users') ? 'bg-indigo-50 text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?>">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span>Pengguna</span>
                            </a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
            </div>

            <!-- Global Omnisearch & Aksi Cepat & Profil Pengguna -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <?php if (session()->get('isLoggedIn')): ?>
                    <!-- Omnisearch Trigger Button (Desktop Only) -->
                    <button type="button" id="btnTriggerOmnisearch" class="hidden sm:inline-flex items-center gap-2 rounded-xl border border-slate-200/90 bg-slate-50/80 px-3 py-1.5 text-xs text-slate-400 hover:bg-white hover:border-indigo-300 hover:text-slate-600 transition-all cursor-pointer shadow-2xs">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <span>Cari data...</span>
                        <kbd class="rounded bg-white px-1.5 py-0.5 text-[10px] font-bold text-slate-500 border border-slate-200 shadow-xs">Ctrl+K</kbd>
                    </button>

                    <!-- Quick Action Dropdown (Desktop Only) -->
                    <div class="relative group hidden md:block">
                        <button type="button" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white shadow-xs shadow-indigo-600/25 hover:bg-indigo-700 transition-all cursor-pointer">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Aksi Cepat</span>
                            <svg class="h-3 w-3 text-indigo-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="absolute right-0 top-full mt-1.5 w-44 rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                            <a href="<?= base_url('stock/in') ?>" class="flex items-center gap-2 rounded-lg px-2.5 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                <span>+ Barang Masuk</span>
                            </a>
                            <a href="<?= base_url('stock/out') ?>" class="flex items-center gap-2 rounded-lg px-2.5 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                <span>- Barang Keluar</span>
                            </a>
                            <div class="my-1 border-t border-slate-100"></div>
                            <a href="<?= base_url('products/new') ?>" class="flex items-center gap-2 rounded-lg px-2.5 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                <span>+ Tambah Produk</span>
                            </a>
                        </div>
                    </div>

                    <!-- Profil Pengguna & Badge Peran (Desktop Only) -->
                    <div class="hidden sm:flex items-center gap-2 rounded-full border border-slate-200/80 bg-slate-50/80 px-2.5 py-1">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full <?= (has_role('admin')) ? 'bg-gradient-to-tr from-indigo-500 to-indigo-700' : 'bg-gradient-to-tr from-emerald-500 to-teal-600' ?> text-xs font-bold text-white shadow-xs">
                            <?= strtoupper(substr(session()->get('nama_lengkap') ?? 'U', 0, 1)) ?>
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="text-xs font-bold text-slate-800 leading-tight"><?= esc(session()->get('nama_lengkap') ?? 'Pengguna') ?></span>
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center px-1 rounded text-[8px] font-extrabold uppercase tracking-wider <?= (has_role('admin')) ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' ?>">
                                    <?= esc(current_user_role()) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Logout (Desktop Only) -->
                    <a href="<?= base_url('logout') ?>" 
                       class="btn-trigger-logout hidden sm:inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all cursor-pointer">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span class="hidden lg:inline">Logout</span>
                    </a>

                    <!-- Avatar Ringkas Khusus Mobile -->
                    <div class="sm:hidden flex items-center">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full <?= (has_role('admin')) ? 'bg-gradient-to-tr from-indigo-500 to-indigo-700' : 'bg-gradient-to-tr from-emerald-500 to-teal-600' ?> text-xs font-bold text-white shadow-xs">
                            <?= strtoupper(substr(session()->get('nama_lengkap') ?? 'U', 0, 1)) ?>
                        </div>
                    </div>

                    <!-- Hamburger Button untuk Layar Mobile -->
                    <button type="button" id="btnToggleMobileMenu" class="md:hidden flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 active:bg-slate-100 transition-colors shadow-2xs cursor-pointer" aria-label="Buka menu navigasi">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 transition-all">
                        Masuk ke Sistem
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- =========================================================================
     * MODERN MOBILE SLIDE-OVER DRAWER (APPLE HIG ROOT OVERLAY)
     * ========================================================================= -->
    <?php if (session()->get('isLoggedIn')): ?>
        <div id="mobileNavDrawer" class="ios-drawer-overlay md:hidden" aria-modal="true" role="dialog">
                <div id="mobileNavPanel" class="ios-drawer-panel p-5 justify-between">
                    
                    <!-- Drawer Header & Profile Card -->
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-xs">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-sm font-extrabold text-slate-900 block leading-tight">InventarisPro</span>
                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Menu Navigasi</span>
                                </div>
                            </div>
                            <button type="button" id="btnCloseMobileDrawer" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer" aria-label="Tutup menu">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>

                        <!-- User Profile Card in Drawer -->
                        <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-3 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full <?= (has_role('admin')) ? 'bg-gradient-to-tr from-indigo-500 to-indigo-700' : 'bg-gradient-to-tr from-emerald-500 to-teal-600' ?> text-sm font-bold text-white shadow-xs">
                                    <?= strtoupper(substr(session()->get('nama_lengkap') ?? 'U', 0, 1)) ?>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-slate-900 truncate"><?= esc(session()->get('nama_lengkap') ?? 'Pengguna') ?></div>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider <?= (has_role('admin')) ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' ?>">
                                            <?= esc(current_user_role()) ?>
                                        </span>
                                        <span class="text-[10px] text-slate-400">&bull;</span>
                                        <span class="text-[10px] text-emerald-600 font-medium flex items-center gap-1">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Online
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigasi Menu Terstruktur (Apple HIG Style) -->
                        <nav class="space-y-4">
                            <!-- Kategori: Master Data -->
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 block mb-1.5">Master Data</span>
                                <div class="space-y-0.5">
                                    <a href="<?= base_url('products') ?>" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all <?= (str_contains(uri_string(), 'products') || uri_string() === '') ? 'bg-indigo-50 text-indigo-700 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50' ?>">
                                        <svg class="h-4 w-4 <?= (str_contains(uri_string(), 'products') || uri_string() === '') ? 'text-indigo-600' : 'text-slate-400' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                                        <span>Katalog Produk</span>
                                    </a>
                                    <a href="<?= base_url('categories') ?>" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all <?= str_contains(uri_string(), 'categories') ? 'bg-indigo-50 text-indigo-700 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50' ?>">
                                        <svg class="h-4 w-4 <?= str_contains(uri_string(), 'categories') ? 'text-indigo-600' : 'text-slate-400' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9h16"></path><path d="M4 15h16"></path><rect x="2" y="3" width="20" height="18" rx="2"></rect></svg>
                                        <span>Kategori Barang</span>
                                    </a>
                                    <a href="<?= base_url('suppliers') ?>" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all <?= str_contains(uri_string(), 'suppliers') ? 'bg-indigo-50 text-indigo-700 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50' ?>">
                                        <svg class="h-4 w-4 <?= str_contains(uri_string(), 'suppliers') ? 'text-indigo-600' : 'text-slate-400' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                        <span>Pemasok (Supplier)</span>
                                    </a>
                                    <a href="<?= base_url('customers') ?>" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all <?= str_contains(uri_string(), 'customers') ? 'bg-indigo-50 text-indigo-700 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50' ?>">
                                        <svg class="h-4 w-4 <?= str_contains(uri_string(), 'customers') ? 'text-indigo-600' : 'text-slate-400' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        <span>Pelanggan & Klien</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Kategori: Logistik & Stok -->
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 block mb-1.5">Logistik & Transaksi</span>
                                <div class="space-y-0.5">
                                    <a href="<?= base_url('stock/in') ?>" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition-all <?= str_contains(uri_string(), 'stock/in') ? 'bg-emerald-50 text-emerald-700 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50' ?>">
                                        <div class="flex items-center gap-3">
                                            <svg class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                                <polyline points="17 6 23 6 23 12"></polyline>
                                            </svg>
                                            <span>Barang Masuk (In)</span>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-100/70 px-1.5 py-0.5 rounded">+ Tambah Stok</span>
                                    </a>
                                    <a href="<?= base_url('stock/out') ?>" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition-all <?= str_contains(uri_string(), 'stock/out') ? 'bg-rose-50 text-rose-700 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50' ?>">
                                        <div class="flex items-center gap-3">
                                            <svg class="h-4 w-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                                <polyline points="17 18 23 18 23 12"></polyline>
                                            </svg>
                                            <span>Barang Keluar (Out)</span>
                                        </div>
                                        <span class="text-[10px] font-bold text-rose-600 bg-rose-100/70 px-1.5 py-0.5 rounded">- Kurang Stok</span>
                                    </a>
                                    <a href="<?= base_url('stock/history') ?>" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all <?= (str_contains(uri_string(), 'stock') && !str_contains(uri_string(), 'stock/in') && !str_contains(uri_string(), 'stock/out')) ? 'bg-indigo-50 text-indigo-700 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50' ?>">
                                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>Riwayat Mutasi Stok</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Kategori: Analitik & Pengaturan -->
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 block mb-1.5">Laporan & Pengaturan</span>
                                <div class="space-y-0.5">
                                    <a href="<?= $reportsUrl ?>" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all <?= str_contains(uri_string(), 'reports') ? 'bg-indigo-50 text-indigo-700 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50' ?>">
                                        <svg class="h-4 w-4 <?= str_contains(uri_string(), 'reports') ? 'text-indigo-600' : 'text-slate-400' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                                        <span>Laporan & Analitik</span>
                                    </a>
                                    <?php if (has_role('admin')): ?>
                                        <a href="<?= base_url('users') ?>" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition-all <?= str_contains(uri_string(), 'users') ? 'bg-indigo-50 text-indigo-700 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50' ?>">
                                            <svg class="h-4 w-4 <?= str_contains(uri_string(), 'users') ? 'text-indigo-600' : 'text-slate-400' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                            <span>Manajemen Pengguna</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </nav>
                    </div>

                    <!-- Drawer Footer: Logout Akun -->
                    <div class="pt-4 border-t border-slate-100">
                        <a href="<?= base_url('logout') ?>" class="btn-trigger-logout flex items-center justify-center gap-2 rounded-xl bg-rose-50 border border-rose-200/80 py-2.5 px-3 text-xs font-bold text-rose-600 hover:bg-rose-100 active:scale-98 transition-all cursor-pointer">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Keluar dari Akun (Logout)</span>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <!-- =========================================================================
     * KONTEN UTAMA APLIKASI
     * ========================================================================= -->
    <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8 pb-24 md:pb-8">
        <div class="mx-auto max-w-7xl">
            <!-- Flash Alert Success -->
            <?php if ($flash_success = session()->getFlashdata('success')): ?>
                <div class="alert alert-dismissible mb-6 flex items-start justify-between gap-3 rounded-2xl border border-emerald-200/80 bg-emerald-50/90 p-4 text-emerald-900 shadow-xs backdrop-blur-xs transition-all" role="alert">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <div class="text-xs sm:text-sm font-semibold leading-relaxed"><?= esc($flash_success) ?></div>
                    </div>
                    <button type="button" class="alert-close text-emerald-700/60 hover:text-emerald-900 text-lg leading-none cursor-pointer p-0.5" aria-label="Tutup notifikasi">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Flash Alert Error -->
            <?php if ($flash_error = session()->getFlashdata('error')): ?>
                <div class="alert alert-dismissible mb-6 flex items-start justify-between gap-3 rounded-2xl border border-rose-200/80 bg-rose-50/90 p-4 text-rose-900 shadow-xs backdrop-blur-xs transition-all" role="alert">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-rose-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <div class="text-xs sm:text-sm font-semibold leading-relaxed"><?= esc($flash_error) ?></div>
                    </div>
                    <button type="button" class="alert-close text-rose-700/60 hover:text-rose-900 text-lg leading-none cursor-pointer p-0.5" aria-label="Tutup notifikasi">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Breadcrumbs Navigasi Hirarkis (Enterprise Wayfinding) -->
            <?= $this->renderSection('breadcrumbs') ?>

            <!-- Konten Halaman Aktif -->
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- Toast Notification Container untuk Feedback Cepat -->
    <div id="appToastContainer"></div>

    <!-- =========================================================================
     * MOBILE BOTTOM NAVIGATION DOCK (THUMB ZONE - ERGONOMIS PONSEL)
     * ========================================================================= -->
    <?php if (session()->get('isLoggedIn')): ?>
        <nav class="mobile-bottom-dock md:hidden no-print" aria-label="Navigasi Bawah Ponsel">
            <!-- Tab Produk -->
            <a href="<?= base_url('products') ?>" class="bottom-nav-item <?= (str_contains(uri_string(), 'products') || uri_string() === '') ? 'active' : '' ?>">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                <span>Produk</span>
            </a>

            <!-- Tab Mutasi Masuk -->
            <a href="<?= base_url('stock/in') ?>" class="bottom-nav-item <?= str_contains(uri_string(), 'stock/in') ? 'active' : '' ?>">
                <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
                <span>Stok Masuk</span>
            </a>

            <!-- Central Trigger Quick Search / Scan -->
            <button type="button" id="btnMobileQuickSearch" class="bottom-nav-center-btn" aria-label="Pencarian Cepat">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>

            <!-- Tab Mutasi Keluar -->
            <a href="<?= base_url('stock/out') ?>" class="bottom-nav-item <?= str_contains(uri_string(), 'stock/out') ? 'active' : '' ?>">
                <svg class="h-5 w-5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
                <span>Stok Keluar</span>
            </a>

            <!-- Tab Laporan -->
            <a href="<?= $reportsUrl ?>" class="bottom-nav-item <?= str_contains(uri_string(), 'reports') ? 'active' : '' ?>">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                <span>Laporan</span>
            </a>
        </nav>
    <?php endif; ?>

    <!-- =========================================================================
     * MODAL KONFIRMASI LOGOUT TERPADU
     * ========================================================================= -->
    <?php if (session()->get('isLoggedIn')): ?>
        <div class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 transition-all opacity-0 invisible" id="logoutConfirmModal" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle">
            <div class="modal-box w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl border border-slate-200 transition-all">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 mb-4">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1" id="logoutModalTitle">Konfirmasi Keluar Sistem</h3>
                <p class="text-xs text-slate-500 leading-relaxed mb-6">
                    Apakah Anda yakin ingin mengakhiri sesi kerja saat ini? Anda harus memasukkan kredensial akun kembali untuk mengakses inventaris.
                </p>
                <div class="flex items-center justify-end gap-2.5">
                    <button type="button" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all cursor-pointer" id="btnCancelLogout">Batal</button>
                    <a href="<?= base_url('logout') ?>" class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-rose-700 transition-all cursor-pointer">Ya, Keluar Sistem</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- =========================================================================
     * ENTERPRISE OPERATIONAL MICRO-FOOTER / SYSTEM STATUS BAR
     * ========================================================================= -->
    <footer class="border-t border-slate-200/80 bg-white py-3 text-xs text-slate-500 no-print">
        <div class="mx-auto flex max-w-7xl flex-col sm:flex-row items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">
            <!-- Kiri: Real-time DB Sync Status -->
            <div class="flex items-center gap-2">
                <span class="flex h-2 w-2 rounded-full bg-emerald-500 shrink-0"></span>
                <span class="font-medium text-slate-700">Cloud Sync: Aktif</span>
                <span class="text-slate-300">&bull;</span>
                <span class="text-slate-500">Database Connected</span>
            </div>
            <!-- Tengah: Keyboard Hint -->
            <div class="hidden md:flex items-center gap-2 text-slate-400">
                <span>Pintasan:</span>
                <kbd class="px-1.5 py-0.5 text-[10px] font-mono bg-slate-100 border border-slate-200 rounded text-slate-600 font-bold">Ctrl+K</kbd>
                <span>Cari</span>
                <span class="text-slate-300">&bull;</span>
                <kbd class="px-1.5 py-0.5 text-[10px] font-mono bg-slate-100 border border-slate-200 rounded text-slate-600 font-bold">Esc</kbd>
                <span>Tutup Modal</span>
            </div>
            <!-- Kanan: Versi Sistem -->
            <div class="flex items-center gap-2 text-slate-400 text-[11px]">
                <span class="font-semibold text-slate-700">InventarisPro</span>
                <span>&bull; Sistem Manajemen Inventaris Berstandar Enterprise</span>
                <span class="text-slate-300">&bull;</span>
                <span>CI <?= CodeIgniter\CodeIgniter::CI_VERSION ?> &bull; PHP <?= PHP_VERSION ?></span>
            </div>
        </div>
    </footer>

    <!-- File Javascript Interaksi Aplikasi -->
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
