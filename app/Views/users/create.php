<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumbs') ?>
<nav class="breadcrumb-nav mb-4" aria-label="Breadcrumb">
    <a href="<?= base_url('users') ?>" class="inline-flex items-center gap-1 text-slate-500 hover:text-indigo-600">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        <span>Manajemen Pengguna</span>
    </a>
    <span class="separator">/</span>
    <span class="current">Tambah Pengguna Baru</span>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <!-- Breadcrumb / Tombol Kembali -->
    <div class="mb-5 flex items-center justify-between">
        <a href="<?= base_url('users') ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kembali ke Daftar Pengguna</span>
        </a>
    </div>

    <!-- Card Formulir Tambah Pengguna -->
    <div class="card rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </div>
                <span>Tambah Pengguna & Tentukan Peran</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">Buat akun staf operasional baru atau tambahkan rekan administrator sistem inventaris.</p>
        </div>

        <form action="<?= base_url('users') ?>" method="POST" class="p-6 space-y-5">
            <?= csrf_field() ?>

            <!-- Nama Lengkap -->
            <div>
                <label for="nama_lengkap" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama_lengkap" 
                    name="nama_lengkap" 
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-medium" 
                    placeholder="Contoh: Budi Santoso" 
                    value="<?= old('nama_lengkap') ?>" 
                    required 
                    autofocus
                >
            </div>

            <!-- Grid: Username & Email -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Username <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-medium lowercase" 
                        placeholder="budisantoso" 
                        value="<?= old('username') ?>" 
                        required
                    >
                    <span class="text-[10px] text-slate-400 mt-1 block">Digunakan untuk login ke sistem.</span>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-medium" 
                        placeholder="budi@perusahaan.com" 
                        value="<?= old('email') ?>" 
                        required
                    >
                </div>
            </div>

            <!-- Password Awal dengan Toggle Lihat/Sembunyi -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Password Akun <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full pl-3.5 pr-10 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-medium" 
                        placeholder="Minimal 6 karakter" 
                        autocomplete="new-password"
                        required
                    >
                    <button 
                        type="button" 
                        class="btn-toggle-password absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-700 transition-colors cursor-pointer" 
                        data-target="password"
                        title="Tampilkan kata sandi"
                        aria-label="Tampilkan kata sandi"
                    >
                        <svg class="icon-eye h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg class="icon-eye-off h-4 w-4 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Pilihan Peran (Role Selector) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Tingkat Wewenang (Role RBAC) <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Staff Gudang -->
                    <label class="relative flex cursor-pointer rounded-xl border border-slate-200 p-4 shadow-2xs hover:border-slate-300 has-checked:border-emerald-600 has-checked:bg-emerald-50/30 transition-all">
                        <input type="radio" name="role" value="staff" class="sr-only" <?= (old('role', 'staff') === 'staff') ? 'checked' : '' ?>>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                </span>
                                <span class="text-xs font-bold text-slate-900">Staff Gudang</span>
                            </div>
                            <span class="text-[11px] text-slate-500 mt-1.5 leading-snug">Operasional harian stok masuk & keluar. Terblokir dari valuasi laba/modal dan penghapusan data.</span>
                        </div>
                    </label>

                    <!-- Administrator -->
                    <label class="relative flex cursor-pointer rounded-xl border border-slate-200 p-4 shadow-2xs hover:border-slate-300 has-checked:border-indigo-600 has-checked:bg-indigo-50/30 transition-all">
                        <input type="radio" name="role" value="admin" class="sr-only" <?= (old('role') === 'admin') ? 'checked' : '' ?>>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    </svg>
                                </span>
                                <span class="text-xs font-bold text-slate-900">Administrator (Owner)</span>
                            </div>
                            <span class="text-[11px] text-slate-500 mt-1.5 leading-snug">Akses tanpa batas: Valuasi finansial, kelola pengguna sistem, hapus master data, dan backup basis data.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Tombol Aksi Simpan & Batal -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="<?= base_url('users') ?>" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 transition-colors">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white shadow-xs shadow-indigo-600/20 hover:bg-indigo-700 transition-all cursor-pointer">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    <span>Simpan Akun Pengguna</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
