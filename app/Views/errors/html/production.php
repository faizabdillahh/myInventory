<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex">
    <title>Kendala Sistem - InventarisPro</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <?php if (function_exists('base_url')): ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.min.css') ?>">
    <?php endif; ?>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="flex min-h-full flex-col items-center justify-center p-4 bg-slate-50 text-slate-800">

    <div class="w-full max-w-lg text-center bg-white rounded-3xl p-8 sm:p-10 shadow-xl border border-slate-200/80">
        
        <!-- Logo / Brand Header -->
        <div class="flex items-center justify-center gap-2.5 mb-6">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white shadow-md shadow-indigo-500/25">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
            <span class="text-base font-extrabold tracking-tight text-slate-900">InventarisPro</span>
        </div>

        <!-- 500 Badge Illustration -->
        <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 shadow-xs">
            <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60 mb-3">
            Status Sistem: Gangguan Internal (500)
        </span>

        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 mb-2">Terjadi Kendala pada Sistem</h1>

        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-8 max-w-sm mx-auto">
            Sistem mendeteksi kendala teknis tak terduga saat memproses permintaan Anda. Laporan diagnostik telah dicatat secara otomatis ke log audit untuk ditindaklanjuti.
        </p>

        <!-- Tombol Aksi Navigasi -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <button onclick="window.location.reload()" type="button" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all cursor-pointer shadow-2xs">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 4 23 10 17 10"></polyline>
                    <polyline points="1 20 1 14 7 14"></polyline>
                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                </svg>
                <span>Muat Ulang Halaman</span>
            </button>
            
            <a href="<?= function_exists('base_url') ? base_url('products') : '/' ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 transition-all cursor-pointer">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span>Kembali ke Beranda Utama</span>
            </a>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 text-[11px] text-slate-400">
            &copy; <?= date('Y') ?> InventarisPro &bull; Sistem Manajemen Inventaris Enterprise
        </div>

    </div>

</body>
</html>
