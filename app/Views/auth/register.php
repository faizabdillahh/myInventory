<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - InventarisPro</title>
    <!-- Google Fonts Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS v4 (Kompilasi Statis Ter-minifikasi & Bebas CDN Eksternal) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="auth-page flex min-h-full items-center justify-center p-4 sm:p-6 bg-slate-50 text-slate-800 font-sans selection:bg-indigo-500 selection:text-white">

    <div class="auth-card w-full max-w-lg rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xl shadow-slate-200/50">
        <!-- Header Formulir Register -->
        <div class="auth-header text-center mb-6">
            <div class="brand-icon mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white shadow-lg shadow-indigo-500/30 mb-3.5">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7.5" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
            </div>
            <h1 class="auth-title text-xl font-extrabold tracking-tight text-slate-900">Pendaftaran Akun</h1>
            <p class="auth-subtitle text-xs text-slate-500 mt-1">Daftarkan akun staf baru untuk mengakses sistem inventaris.</p>
        </div>

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

        <!-- Formulir Pendaftaran Akun -->
        <form action="<?= base_url('register') ?>" method="POST" autocomplete="off" class="space-y-4">
            <!-- Proteksi CSRF Token -->
            <?= csrf_field() ?>

            <!-- Nama Lengkap -->
            <div class="form-group">
                <label for="nama_lengkap" class="form-label block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                <input 
                    type="text" 
                    id="nama_lengkap" 
                    name="nama_lengkap" 
                    class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 font-medium" 
                    placeholder="Contoh: Budi Santoso" 
                    value="<?= esc(old('nama_lengkap')) ?>" 
                    required 
                    autofocus
                >
            </div>

            <!-- Username & Email Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="form-group">
                    <label for="username" class="form-label block text-xs font-bold text-slate-700 mb-1.5">Username</label>
                    <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 font-medium" 
                    placeholder="Contoh: budi123" 
                    value="<?= esc(old('username')) ?>" 
                    required
                >
                </div>
                <div class="form-group">
                    <label for="email" class="form-label block text-xs font-bold text-slate-700 mb-1.5">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 font-medium" 
                        placeholder="nama@email.com" 
                        value="<?= esc(old('email')) ?>" 
                        required
                    >
                </div>
            </div>

            <!-- Password & Konfirmasi Password Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="form-group">
                    <label for="password" class="form-label block text-xs font-bold text-slate-700 mb-1.5">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 font-medium" 
                        placeholder="Minimal 6 karakter" 
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="password_confirm" class="form-label block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 font-medium" 
                        placeholder="Ulangi password" 
                        required
                    >
                </div>
            </div>

            <!-- Tombol Submit Registrasi -->
            <div class="form-group pt-2">
                <button type="submit" class="btn btn-primary w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/25 hover:bg-indigo-700 transition-all cursor-pointer">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Daftar Akun Sekarang</span>
                </button>
            </div>
        </form>

        <!-- Tautan Balik ke Login -->
        <div class="text-center mt-5 text-xs text-slate-500">
            Sudah memiliki akun? <a href="<?= base_url('login') ?>" class="font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">Masuk di sini</a>
        </div>
    </div>

    <!-- Skrip Interaktif -->
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
