<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Tombol Kembali ke Daftar Kategori -->
<div class="mb-5">
    <a href="<?= base_url('categories') ?>" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 hover:text-slate-900 transition-all">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Kembali ke Daftar Kategori</span>
    </a>
</div>

<!-- Kartu Formulir Tambah Kategori -->
<div class="card form-card mx-auto max-w-2xl rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
    <div class="card-header p-6 border-b border-slate-100">
        <h2 class="card-title text-lg font-bold text-slate-900 flex items-center gap-2.5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </div>
            <span>Tambah Kategori Baru</span>
        </h2>
        <p class="card-subtitle text-xs text-slate-500 mt-1">Buat kelompok kategori baru untuk klasifikasi barang inventaris.</p>
    </div>

    <!-- Menampilkan Error Validasi jika ada -->
    <?php if ($errors = session()->getFlashdata('errors')): ?>
        <div class="p-6 pb-0">
            <div class="alert alert-danger rounded-xl border border-rose-200 bg-rose-50/90 p-4 text-xs font-medium text-rose-800">
                <ul class="list-disc pl-5 space-y-1">
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- Formulir Tambah Kategori -->
    <form action="<?= base_url('categories') ?>" method="POST" autocomplete="off" class="p-6 space-y-5">
        <?= csrf_field() ?>

        <!-- Nama Kategori -->
        <div class="form-group">
            <label for="nama_kategori" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                Nama Kategori <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                id="nama_kategori" 
                name="nama_kategori" 
                class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 font-medium" 
                placeholder="Contoh: Alat Jaringan & Server" 
                value="<?= esc(old('nama_kategori')) ?>" 
                required 
                autofocus
            >
            <small class="block text-[11px] text-slate-400 mt-1">Nama kategori harus unik dalam sistem.</small>
        </div>

        <!-- Deskripsi Kategori -->
        <div class="form-group">
            <label for="deskripsi" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                Keterangan / Deskripsi Kategori
            </label>
            <textarea 
                id="deskripsi" 
                name="deskripsi" 
                class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 resize-y" 
                rows="3" 
                placeholder="Jelaskan jenis atau cakupan barang dalam kategori ini..."
            ><?= esc(old('deskripsi')) ?></textarea>
        </div>

        <!-- Tombol Aksi Simpan atau Batal -->
        <div class="form-actions pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="<?= base_url('categories') ?>" class="btn btn-secondary rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="btn btn-primary inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-5 py-2 text-xs font-bold text-white shadow-xs shadow-indigo-600/20 hover:bg-indigo-700 transition-all cursor-pointer">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                <span>Simpan Kategori</span>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
