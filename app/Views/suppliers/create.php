<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Tombol Kembali ke Master Supplier -->
<div class="mb-5">
    <a href="<?= base_url('suppliers') ?>" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 hover:text-slate-900 transition-all">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Kembali ke Daftar Supplier</span>
    </a>
</div>

<!-- Formulir Tambah Supplier Baru -->
<div class="card form-card mx-auto max-w-3xl rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
    <div class="card-header p-6 border-b border-slate-100">
        <h2 class="card-title text-lg font-bold text-slate-900 flex items-center gap-2.5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                </svg>
            </div>
            <span>Tambah Pemasok (Supplier) Baru</span>
        </h2>
        <p class="card-subtitle text-xs text-slate-500 mt-1">Daftarkan vendor atau mitra pemasok resmi untuk pengadaan inventaris gudang.</p>
    </div>

    <!-- Menampilkan Error Validasi Field -->
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

    <form action="<?= base_url('suppliers') ?>" method="POST" autocomplete="off" class="p-6 space-y-5">
        <?= csrf_field() ?>

        <div class="form-row grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <!-- Kode Supplier -->
            <div class="form-group">
                <label for="kode_supplier" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Kode Supplier <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="kode_supplier" 
                    name="kode_supplier" 
                    class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all uppercase placeholder-slate-400 font-mono font-medium" 
                    placeholder="Contoh: SPL-005" 
                    value="<?= esc(old('kode_supplier')) ?>" 
                    required 
                    autofocus
                >
                <small class="block text-[11px] text-slate-400 mt-1">Kode unik pemasok, otomatis dikonversi ke huruf kapital.</small>
            </div>

            <!-- Nama Perusahaan / Supplier -->
            <div class="form-group">
                <label for="nama_supplier" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Nama Supplier / Perusahaan <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama_supplier" 
                    name="nama_supplier" 
                    class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 font-medium" 
                    placeholder="Contoh: PT Sumber Rezeki Sentosa" 
                    value="<?= esc(old('nama_supplier')) ?>" 
                    required
                >
            </div>
        </div>

        <div class="form-row grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <!-- Kontak Person / PIC -->
            <div class="form-group">
                <label for="kontak_person" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Kontak Person (Nama PIC / Sales)
                </label>
                <input 
                    type="text" 
                    id="kontak_person" 
                    name="kontak_person" 
                    class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400" 
                    placeholder="Contoh: Budi Santoso" 
                    value="<?= esc(old('kontak_person')) ?>"
                >
            </div>

            <!-- Nomor Telepon -->
            <div class="form-group">
                <label for="telepon" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Nomor Telepon / WhatsApp
                </label>
                <input 
                    type="text" 
                    id="telepon" 
                    name="telepon" 
                    class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400" 
                    placeholder="Contoh: 021-5551234 / 08123456789" 
                    value="<?= esc(old('telepon')) ?>"
                >
            </div>
        </div>

        <!-- Alamat Email -->
        <div class="form-group">
            <label for="email" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                Alamat Email Resmi
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400" 
                placeholder="Contoh: sales@sumberrezeki.com" 
                value="<?= esc(old('email')) ?>"
            >
        </div>

        <!-- Alamat Lengkap -->
        <div class="form-group">
            <label for="alamat" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                Alamat Lengkap Kantor / Gudang Pemasok
            </label>
            <textarea 
                id="alamat" 
                name="alamat" 
                class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 resize-y" 
                rows="3" 
                placeholder="Tuliskan nama jalan, nomor gedung, kelurahan, kecamatan, dan kota..."
            ><?= esc(old('alamat')) ?></textarea>
        </div>

        <!-- Tombol Aksi Simpan atau Batal -->
        <div class="form-actions pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="<?= base_url('suppliers') ?>" class="btn btn-secondary rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="btn btn-primary inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-5 py-2 text-xs font-bold text-white shadow-xs shadow-indigo-600/20 hover:bg-indigo-700 transition-all cursor-pointer">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                <span>Simpan Supplier</span>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
