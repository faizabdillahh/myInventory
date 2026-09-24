<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mx-auto max-w-3xl">
    <!-- Breadcrumb & Back Navigation -->
    <div class="mb-5 flex items-center justify-between gap-4">
        <a href="<?= base_url('products') ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kembali ke Katalog Produk</span>
        </a>
        <a href="<?= base_url('stock/history') ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                <polyline points="17 6 23 6 23 12"></polyline>
            </svg>
            <span>Lihat Buku Riwayat Mutasi</span>
        </a>
    </div>

    <!-- Main Form Card (Apple HIG Styled Card) -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 sm:p-7 shadow-xs">
        <!-- Form Header -->
        <div class="flex items-center gap-3.5 pb-5 border-b border-slate-100 mb-6">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl <?= ($defaultTipe === 'IN') ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' ?>">
                <?php if ($defaultTipe === 'IN'): ?>
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                <?php else: ?>
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                        <polyline points="17 18 23 18 23 12"></polyline>
                    </svg>
                <?php endif; ?>
            </div>
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 leading-tight">
                    <?= ($defaultTipe === 'IN') ? 'Pencatatan Barang Masuk (+)' : 'Pencatatan Barang Keluar (-)' ?>
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    <?= ($defaultTipe === 'IN') 
                        ? 'Catat penambahan stok fisik dari pemasok atau pengadaan gudang ke inventaris.' 
                        : 'Catat pengeluaran stok fisik untuk pengiriman klien, penjualan, atau penggunaan internal.' ?>
                </p>
            </div>
        </div>

        <!-- Form Submission -->
        <form action="<?= base_url('stock/movement') ?>" method="POST" class="space-y-5">
            <?= csrf_field() ?>

            <!-- Field 1: Pilih Produk -->
            <div>
                <label for="product_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Pilih Produk Inventaris <span class="text-rose-500">*</span>
                </label>
                <select name="product_id" id="product_id" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer" required>
                    <option value="">-- Pilih Barang / Komoditas --</option>
                    <?php if (!empty($products)): foreach ($products as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= ($selectedProductId === (int)$p['id']) ? 'selected' : '' ?>>
                            [<?= esc($p['kode_produk']) ?>] <?= esc($p['nama_produk']) ?> &mdash; Stok Saat Ini: <?= (int)$p['stok'] ?> Unit (<?= esc($p['kategori']) ?>)
                        </option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <!-- Field 2: Jenis Mutasi (iOS Segmented Radios) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Tipe Mutasi Transaksi <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-3 gap-2.5">
                    <label class="flex items-center justify-center gap-2 p-3 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-700 cursor-pointer hover:bg-emerald-50/50 hover:border-emerald-200 transition-all">
                        <input type="radio" name="tipe" value="IN" class="text-emerald-600 focus:ring-emerald-500" <?= ($defaultTipe === 'IN') ? 'checked' : '' ?>>
                        <span>Masuk (+)</span>
                    </label>
                    <label class="flex items-center justify-center gap-2 p-3 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-700 cursor-pointer hover:bg-rose-50/50 hover:border-rose-200 transition-all">
                        <input type="radio" name="tipe" value="OUT" class="text-rose-600 focus:ring-rose-500" <?= ($defaultTipe === 'OUT') ? 'checked' : '' ?>>
                        <span>Keluar (-)</span>
                    </label>
                    <label class="flex items-center justify-center gap-2 p-3 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-700 cursor-pointer hover:bg-amber-50/50 hover:border-amber-200 transition-all">
                        <input type="radio" name="tipe" value="ADJUSTMENT" class="text-amber-600 focus:ring-amber-500">
                        <span>Penyesuaian</span>
                    </label>
                </div>
            </div>

            <!-- Field 3: Pemasok Asal (Jika Masuk) -->
            <div id="supplierContainer" class="<?= ($defaultTipe === 'OUT') ? 'hidden' : '' ?>">
                <label for="supplier_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Pemasok / Supplier Asal Barang
                </label>
                <select name="supplier_id" id="supplier_id" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer">
                    <option value="">-- Tanpa / Supplier Tidak Ditentukan --</option>
                    <?php if (!empty($suppliers)): foreach ($suppliers as $s): ?>
                        <option value="<?= $s['id'] ?>">
                            <?= esc($s['nama_supplier']) ?> (<?= esc($s['kode_supplier']) ?>)
                        </option>
                    <?php endforeach; endif; ?>
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Opsional: Hubungkan transaksi masuk dengan vendor terdaftar untuk pelacakan performa rantai pasok.</p>
            </div>

            <!-- Field 4: Pelanggan / Departemen Penerima (Jika Keluar) -->
            <div id="customerContainer" class="<?= ($defaultTipe === 'IN') ? 'hidden' : '' ?>">
                <label for="customer_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Pelanggan / Departemen Penerima
                </label>
                <select name="customer_id" id="customer_id" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer">
                    <option value="">-- Tanpa / Pelanggan Tidak Ditentukan --</option>
                    <?php if (!empty($customers)): foreach ($customers as $c): ?>
                        <option value="<?= $c['id'] ?>">
                            <?= esc($c['nama_pelanggan']) ?> (<?= esc($c['kode_pelanggan']) ?> - <?= esc($c['tipe']) ?>)
                        </option>
                    <?php endforeach; endif; ?>
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Opsional: Hubungkan pengeluaran stok dengan klien atau divisi penerima untuk audit trail.</p>
            </div>

            <!-- Field 5: Kuantitas Unit -->
            <div>
                <label for="jumlah" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Kuantitas / Jumlah Unit Mutasi <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="number" 
                    id="jumlah" 
                    name="jumlah" 
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" 
                    min="1" 
                    placeholder="Masukkan jumlah unit (misal: 10)" 
                    required
                >
            </div>

            <!-- Field 6: Keterangan / Referensi Dokumen -->
            <div>
                <label for="keterangan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Keterangan Operasional / Nomor Referensi (PO/DO)
                </label>
                <textarea 
                    name="keterangan" 
                    id="keterangan" 
                    rows="3" 
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" 
                    placeholder="Contoh: Penerimaan PO #9921 dari Vendor / Pengiriman DO #4401 ke Departemen IT"
                ></textarea>
            </div>

            <!-- Actions Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="<?= base_url('products') ?>" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-indigo-700 transition-all cursor-pointer">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Simpan Transaksi Mutasi</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const radioTipos = document.querySelectorAll('input[name="tipe"]');
    const supplierContainer = document.getElementById('supplierContainer');
    const customerContainer = document.getElementById('customerContainer');

    function updateVisibility(val) {
        if (!supplierContainer || !customerContainer) return;
        if (val === 'IN') {
            supplierContainer.classList.remove('hidden');
            customerContainer.classList.add('hidden');
        } else if (val === 'OUT') {
            supplierContainer.classList.add('hidden');
            customerContainer.classList.remove('hidden');
        } else {
            // ADJUSTMENT
            supplierContainer.classList.add('hidden');
            customerContainer.classList.add('hidden');
        }
    }

    radioTipos.forEach(r => {
        r.addEventListener('change', () => {
            updateVisibility(r.value);
        });
    });
});
</script>

<?= $this->endSection() ?>
