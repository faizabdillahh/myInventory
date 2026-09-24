<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumbs') ?>
<nav class="breadcrumb-nav mb-4" aria-label="Breadcrumb">
    <a href="<?= base_url('products') ?>" class="inline-flex items-center gap-1 text-slate-500 hover:text-indigo-600">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
        <span>Katalog Produk</span>
    </a>
    <span class="separator">/</span>
    <span class="current">Tambah Produk Baru</span>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Tombol Kembali ke Dashboard -->
<div class="mb-5">
    <a href="<?= base_url('products') ?>" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 hover:text-slate-900 transition-all">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Kembali ke Daftar Produk</span>
    </a>
</div>

<!-- Kartu Kontainer Formulir Tambah Produk -->
<div class="card form-card mx-auto max-w-3xl rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
    <div class="card-header p-6 border-b border-slate-100">
        <h2 class="card-title text-lg font-bold text-slate-900 flex items-center gap-2.5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </div>
            <span>Tambah Produk Baru</span>
        </h2>
        <p class="card-subtitle text-xs text-slate-500 mt-1">Lengkapi rincian formulir di bawah ini untuk mencatatkan stok barang baru.</p>
    </div>

    <!-- Menampilkan Error Validasi Field jika ada -->
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

    <!-- Formulir Tambah Data Produk -->
    <form action="<?= base_url('products') ?>" method="POST" autocomplete="off" class="p-6 space-y-5">
        <!-- Proteksi CSRF Token -->
        <?= csrf_field() ?>

        <div class="form-row grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <!-- Kolom Kode Produk / SKU -->
            <div class="form-group">
                <label for="kode_produk" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Kode SKU / Produk <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="kode_produk" 
                    name="kode_produk" 
                    class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all uppercase placeholder-slate-400 font-mono font-medium" 
                    placeholder="Contoh: PRD-006" 
                    value="<?= esc(old('kode_produk')) ?>" 
                    required 
                    autofocus
                >
                <small class="block text-[11px] text-slate-400 mt-1">Harus unik, otomatis dikonversi ke huruf kapital.</small>
            </div>

            <!-- Kolom Kategori Produk -->
            <div class="form-group">
                <label for="kategori" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Kategori <span class="text-rose-500">*</span>
                </label>
                <select id="kategori" name="kategori" class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php
                    $selectedKategori = old('kategori');
                    foreach ($categories as $cat):
                        $catName = is_array($cat) ? $cat['nama_kategori'] : $cat->nama_kategori;
                    ?>
                        <option value="<?= esc($catName) ?>" <?= ($selectedKategori === $catName) ? 'selected' : '' ?>><?= esc($catName) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Kolom Nama Produk -->
        <div class="form-group">
            <label for="nama_produk" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                Nama Produk / Barang <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                id="nama_produk" 
                name="nama_produk" 
                class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400" 
                placeholder="Contoh: Monitor LG UltraGear 24 Inch" 
                value="<?= esc(old('nama_produk')) ?>" 
                required
            >
        </div>

        <div class="form-row grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <!-- Kolom Harga Satuan -->
            <div class="form-group">
                <label for="inputHarga" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Harga Satuan (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                    <input 
                        type="number" 
                        step="0.01" 
                        min="0" 
                        id="inputHarga" 
                        name="harga" 
                        class="form-control w-full pl-10 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 font-medium" 
                        placeholder="0" 
                        value="<?= esc(old('harga')) ?>" 
                        required
                    >
                </div>
                <div class="currency-helper-preview text-[11px] font-bold text-indigo-600 mt-1 hidden" id="inputHargaPreview"></div>
            </div>

            <!-- Kolom Jumlah Stok Awal -->
            <div class="form-group">
                <label for="stok" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Jumlah Stok Awal <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="number" 
                    min="0" 
                    id="stok" 
                    name="stok" 
                    class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 font-medium" 
                    placeholder="0" 
                    value="<?= esc(old('stok', 0)) ?>" 
                    required
                >
            </div>
        </div>

        <div class="form-row grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <!-- Kolom Batas Stok Minimum -->
            <div class="form-group">
                <label for="stok_minimum" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Batas Stok Minimum (Safety Stock) <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="number" 
                    min="0" 
                    id="stok_minimum" 
                    name="stok_minimum" 
                    class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 font-medium" 
                    placeholder="5" 
                    value="<?= esc(old('stok_minimum', 5)) ?>" 
                    required
                >
                <small class="block text-[11px] text-slate-400 mt-1">Ambang batas peringatan restock jika stok fisik &le; batas ini.</small>
            </div>

            <!-- Kolom Pemasok Utama (Supplier) -->
            <div class="form-group">
                <label for="supplier_id" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Pemasok Utama (Supplier)
                </label>
                <select id="supplier_id" name="supplier_id" class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer">
                    <option value="">-- Pilih Pemasok Utama (Opsional) --</option>
                    <?php if (!empty($suppliers)): foreach ($suppliers as $sup): ?>
                        <option value="<?= $sup['id'] ?>" <?= (old('supplier_id') == $sup['id']) ? 'selected' : '' ?>>
                            <?= esc($sup['nama_supplier']) ?> (<?= esc($sup['kode_supplier']) ?>)
                        </option>
                    <?php endforeach; endif; ?>
                </select>
                <small class="block text-[11px] text-slate-400 mt-1">Vendor utama untuk kebutuhan pemesanan ulang (*reorder*).</small>
            </div>
        </div>

        <!-- Kolom Deskripsi Produk -->
        <div class="form-group">
            <label for="deskripsi" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                Deskripsi / Spesifikasi Tambahan
            </label>
            <textarea 
                id="deskripsi" 
                name="deskripsi" 
                class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 resize-y" 
                rows="4" 
                placeholder="Tuliskan keterangan lengkap atau rincian spesifikasi barang di sini..."
            ><?= esc(old('deskripsi')) ?></textarea>
        </div>

        <!-- Tombol Aksi Simpan atau Batal -->
        <div class="form-actions pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="<?= base_url('products') ?>" class="btn btn-secondary rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="btn btn-primary inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-5 py-2 text-xs font-bold text-white shadow-xs shadow-indigo-600/20 hover:bg-indigo-700 transition-all cursor-pointer">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                <span>Simpan Produk</span>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
