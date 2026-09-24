<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Tombol Kembali ke Master Pelanggan -->
<div class="mb-5">
    <a href="<?= base_url('customers') ?>" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 hover:text-slate-900 transition-all">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Kembali ke Master Pelanggan</span>
    </a>
</div>

<!-- Kartu Kontainer Formulir Edit Pelanggan -->
<div class="card form-card mx-auto max-w-3xl rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
    <div class="card-header p-6 border-b border-slate-100">
        <h2 class="card-title text-lg font-bold text-slate-900 flex items-center gap-2.5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
            </div>
            <span>Edit Data Pelanggan / Departemen</span>
        </h2>
        <p class="card-subtitle text-xs text-slate-500 mt-1">
            Perbarui rincian informasi entitas tujuan: <strong class="text-slate-800 font-semibold"><?= esc($customer['nama_pelanggan']) ?></strong>
        </p>
    </div>

    <!-- Alert Notifikasi Error Validasi -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="p-6 pb-0">
            <div class="alert alert-danger rounded-xl border border-rose-200 bg-rose-50/90 p-4 text-xs font-medium text-rose-800">
                <ul class="list-disc pl-5 space-y-1">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form Edit Pelanggan -->
    <form action="<?= base_url('customers/update/' . $customer['id']) ?>" method="POST" autocomplete="off" class="p-6 space-y-5">
        <?= csrf_field() ?>

        <div class="form-row grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <!-- Kode Pelanggan -->
            <div class="form-group">
                <label for="kode_pelanggan" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Kode Pelanggan / ID Entitas <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="kode_pelanggan" 
                    name="kode_pelanggan" 
                    class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all uppercase placeholder-slate-400 font-mono font-medium" 
                    value="<?= esc(old('kode_pelanggan', $customer['kode_pelanggan'])) ?>" 
                    required
                >
                <small class="block text-[11px] text-slate-400 mt-1">Kode unik identitas pelanggan atau departemen.</small>
            </div>

            <!-- Tipe Pelanggan -->
            <div class="form-group">
                <label for="tipe" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Tipe Entitas <span class="text-rose-500">*</span>
                </label>
                <?php $currentTipe = old('tipe', $customer['tipe']); ?>
                <select id="tipe" name="tipe" class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all cursor-pointer" required>
                    <option value="BISNIS" <?= ($currentTipe === 'BISNIS') ? 'selected' : '' ?>>Bisnis / Perusahaan (B2B)</option>
                    <option value="INDIVIDUAL" <?= ($currentTipe === 'INDIVIDUAL') ? 'selected' : '' ?>>Perorangan / Ritel (B2C)</option>
                    <option value="DEPARTEMEN" <?= ($currentTipe === 'DEPARTEMEN') ? 'selected' : '' ?>>Departemen / Divisi Internal</option>
                </select>
                <small class="block text-[11px] text-slate-400 mt-1">Kategori hubungan mitra penerima barang.</small>
            </div>
        </div>

        <!-- Nama Pelanggan / Departemen -->
        <div class="form-group">
            <label for="nama_pelanggan" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                Nama Lengkap Pelanggan / Departemen <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                id="nama_pelanggan" 
                name="nama_pelanggan" 
                class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 font-medium" 
                value="<?= esc(old('nama_pelanggan', $customer['nama_pelanggan'])) ?>" 
                required
            >
        </div>

        <div class="form-row grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <!-- Kontak Person (PIC) -->
            <div class="form-group">
                <label for="kontak_person" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Nama Kontak Person (PIC / Penanggung Jawab)
                </label>
                <input 
                    type="text" 
                    id="kontak_person" 
                    name="kontak_person" 
                    class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400" 
                    value="<?= esc(old('kontak_person', $customer['kontak_person'] ?? '')) ?>"
                >
            </div>

            <!-- Telepon / WhatsApp -->
            <div class="form-group">
                <label for="telepon" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                    Nomor Telepon / WhatsApp
                </label>
                <input 
                    type="text" 
                    id="telepon" 
                    name="telepon" 
                    class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400" 
                    value="<?= esc(old('telepon', $customer['telepon'] ?? '')) ?>"
                >
            </div>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                Alamat Email Resmi
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400" 
                value="<?= esc(old('email', $customer['email'] ?? '')) ?>"
            >
        </div>

        <!-- Alamat Kantor / Lokasi Pengiriman -->
        <div class="form-group">
            <label for="alamat" class="form-label block text-xs font-bold text-slate-700 mb-1.5">
                Alamat Kantor / Lokasi Pengiriman
            </label>
            <textarea 
                id="alamat" 
                name="alamat" 
                class="form-control w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-slate-400 resize-y" 
                rows="3"
            ><?= esc(old('alamat', $customer['alamat'] ?? '')) ?></textarea>
        </div>

        <!-- Tombol Aksi Simpan atau Batal -->
        <div class="form-actions pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="<?= base_url('customers') ?>" class="btn btn-secondary rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="btn btn-primary inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-5 py-2 text-xs font-bold text-white shadow-xs shadow-indigo-600/20 hover:bg-indigo-700 transition-all cursor-pointer">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                <span>Perbarui Data Pelanggan</span>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
