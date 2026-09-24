<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - InventarisPro</title>
    <!-- Google Fonts Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS v4 (Kompilasi Statis Ter-minifikasi & Bebas CDN Eksternal) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="auth-page flex min-h-full items-center justify-center p-4 sm:p-6 bg-slate-50 text-slate-800 font-sans selection:bg-indigo-500 selection:text-white">

    <div class="auth-card w-full max-w-md rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xl shadow-slate-200/50">
        <!-- Header Formulir Login -->
        <div class="auth-header text-center mb-6">
            <div class="brand-icon mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white shadow-lg shadow-indigo-500/30 mb-3.5">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
            <h1 class="auth-title text-xl font-extrabold tracking-tight text-slate-900">Masuk ke Sistem</h1>
            <p class="auth-subtitle text-xs text-slate-500 mt-1">Kelola inventaris dan produk Anda dengan mudah dan aman.</p>
        </div>

        <!-- Menampilkan Flash Message Success jika ada -->
        <?php if ($flash_success = session()->getFlashdata('success')): ?>
            <div class="alert alert-success mb-4 flex items-center gap-2.5 rounded-xl border border-emerald-200 bg-emerald-50/90 p-3.5 text-xs font-semibold text-emerald-800">
                <svg class="h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <div><?= esc($flash_success) ?></div>
            </div>
        <?php endif; ?>

        <!-- Menampilkan Flash Message Error jika ada -->
        <?php if ($flash_error = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger mb-4 flex items-center gap-2.5 rounded-xl border border-rose-200 bg-rose-50/90 p-3.5 text-xs font-semibold text-rose-800">
                <svg class="h-4 w-4 shrink-0 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div><?= esc($flash_error) ?></div>
            </div>
        <?php endif; ?>

        <!-- Menampilkan Error Validasi Field jika ada -->
        <?php if ($errors = session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger mb-4 rounded-xl border border-rose-200 bg-rose-50/90 p-3.5 text-xs text-rose-800">
                <ul class="list-disc pl-5 space-y-0.5">
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulir Login -->
        <form action="<?= base_url('login') ?>" method="POST" autocomplete="off" class="space-y-4">
            <!-- Proteksi CSRF Token -->
            <?= csrf_field() ?>

            <!-- Input Username atau Email -->
            <div class="form-group">
                <label for="identity" class="form-label block text-xs font-bold text-slate-700 mb-1.5">Username atau Email</label>
                <input 
                    type="text" 
                    id="identity" 
                    name="identity" 
                    class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 font-medium" 
                    placeholder="Contoh: admin atau nama@email.com" 
                    value="<?= esc(old('identity')) ?>" 
                    required 
                    autofocus
                >
            </div>

            <!-- Input Password dengan Toggle Lihat/Sembunyi -->
            <div class="form-group">
                <label for="password" class="form-label block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi (Password)</label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control w-full pl-3.5 pr-10 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 font-medium" 
                        placeholder="Masukkan password Anda" 
                        autocomplete="current-password"
                        required
                    >
                    <button 
                        type="button" 
                        class="btn-toggle-password absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-700 transition-colors cursor-pointer" 
                        data-target="password"
                        title="Tampilkan kata sandi"
                        aria-label="Tampilkan kata sandi"
                    >
                        <!-- Ikon Eye (Mata Terbuka) -->
                        <svg class="icon-eye h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <!-- Ikon Eye-Off (Mata Dicoret) -->
                        <svg class="icon-eye-off h-4 w-4 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Tombol Submit Login -->
            <div class="form-group pt-2">
                <button type="submit" class="btn btn-primary w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/25 hover:bg-indigo-700 transition-all cursor-pointer">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    <span>Masuk Sekarang</span>
                </button>
            </div>
        </form>

        <!-- Informasi Bantuan Akun Demo -->
        <div class="info-box mt-5 rounded-xl border border-slate-200/80 bg-slate-50/80 p-3.5 text-xs text-slate-600">
            <span class="font-bold text-slate-800 block mb-0.5">Akun Demo:</span>
            <div class="font-mono text-[11px] text-slate-500">
                User: <code class="font-bold text-indigo-700 bg-indigo-50 px-1 py-0.5 rounded">admin</code> &bull; Pass: <code class="font-bold text-indigo-700 bg-indigo-50 px-1 py-0.5 rounded">admin123</code>
            </div>
        </div>

        <!-- Tautan ke Halaman Pendaftaran (Register) -->
        <div class="text-center mt-5 text-xs text-slate-500">
            Belum memiliki akun? <a href="<?= base_url('register') ?>" class="font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">Daftar akun baru</a>
        </div>
    </div>

    <!-- Skrip Interaktif -->
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
