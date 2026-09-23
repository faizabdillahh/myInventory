<?php
/**
 * ==============================================================================
 * FILE: create.php
 * DESKRIPSI: Controller & View Penambahan Produk Baru (CREATE)
 * ==============================================================================
 * Menerapkan Clean Code & Best Practices:
 * 1. Logika penyimpanan dan pengecekan keunikan SKU didelegasikan ke model Product.
 * 2. Proteksi terhadap serangan CSRF menggunakan token validasi.
 * 3. Sanitasi output menggunakan helper e() dan old().
 * 4. Pemisahan kode yang bersih dan mudah dipelihara.
 */

// 1. Memeriksa status login (Middleware)
require_once __DIR__ . '/includes/auth_check.php';

// 2. Memuat koneksi database dan model Product
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Product.php';

// Judul halaman
$page_title = "Tambah Produk Baru";

// Inisialisasi model Product
$productModel = new Product($pdo);

// Variabel penampung pesan error
$errorMessage = '';

// 3. Menangani pengiriman formulir via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi token keamanan CSRF
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $errorMessage = "Token keamanan (CSRF) tidak valid atau sesi telah kedaluwarsa. Silakan refresh halaman.";
    } else {
        $kodeProduk = strtoupper(trim($_POST['kode_produk'] ?? ''));
        $namaProduk = trim($_POST['nama_produk'] ?? '');
        $kategori   = trim($_POST['kategori'] ?? '');
        $harga      = trim($_POST['harga'] ?? '0');
        $stok       = trim($_POST['stok'] ?? '0');
        $deskripsi  = trim($_POST['deskripsi'] ?? '');

        // Validasi input
        if (empty($kodeProduk) || empty($namaProduk) || empty($kategori)) {
            $errorMessage = "Kode Produk, Nama Produk, dan Kategori wajib diisi!";
        } elseif (!is_numeric($harga) || (float)$harga < 0) {
            $errorMessage = "Harga harus berupa angka dan tidak boleh bernilai negatif!";
        } elseif (!ctype_digit((string)$stok) || (int)$stok < 0) {
            $errorMessage = "Jumlah stok harus berupa bilangan bulat positif atau nol!";
        } elseif ($productModel->isSkuTaken($kodeProduk)) {
            $errorMessage = "Kode Produk '{$kodeProduk}' sudah digunakan oleh produk lain. Silakan gunakan kode unik!";
        } else {
            // Memanggil method create pada model Product
            $isCreated = $productModel->create([
                'user_id'     => $_SESSION['user_id'],
                'kode_produk' => $kodeProduk,
                'nama_produk' => $namaProduk,
                'kategori'    => $kategori,
                'harga'       => $harga,
                'stok'        => $stok,
                'deskripsi'   => $deskripsi
            ]);

            if ($isCreated) {
                set_flash('success', "Produk '{$namaProduk}' ({$kodeProduk}) berhasil ditambahkan ke inventaris!");
                redirect('index.php');
            } else {
                $errorMessage = "Terjadi kesalahan saat menyimpan data produk ke database.";
            }
        }
    }
}

// Memuat template header HTML dan navigasi
require_once __DIR__ . '/includes/header.php';
?>

<!-- Tombol Kembali ke Dashboard -->
<div style="margin-bottom: 20px;">
    <a href="index.php" class="btn btn-secondary btn-sm">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Kembali ke Daftar Produk
    </a>
</div>

<!-- Kartu Kontainer Formulir Tambah Produk -->
<div class="card form-card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Produk Baru
            </h2>
            <p class="card-subtitle">Lengkapi rincian formulir di bawah ini untuk mencatatkan stok barang baru.</p>
        </div>
    </div>

    <!-- Menampilkan pesan error validasi jika ada kesalahan input -->
    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div><?= e($errorMessage) ?></div>
        </div>
    <?php endif; ?>

    <!-- Formulir Tambah Data Produk -->
    <form action="create.php" method="POST" autocomplete="off">
        <!-- Proteksi CSRF Token -->
        <?= csrf_field() ?>

        <div class="form-row">
            <!-- Kolom Kode Produk / SKU -->
            <div class="form-group">
                <label for="kode_produk" class="form-label">Kode SKU / Produk <span style="color: var(--danger);">*</span></label>
                <input 
                    type="text" 
                    id="kode_produk" 
                    name="kode_produk" 
                    class="form-control" 
                    placeholder="Contoh: PRD-006" 
                    value="<?= e(old('kode_produk')) ?>" 
                    required 
                    autofocus
                >
                <small style="color: var(--text-muted); font-size: 0.75rem;">Harus unik, otomatis dikonversi ke huruf kapital.</small>
            </div>

            <!-- Kolom Kategori Produk -->
            <div class="form-group">
                <label for="kategori" class="form-label">Kategori <span style="color: var(--danger);">*</span></label>
                <select id="kategori" name="kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php
                    $kategoriList = ['Elektronik', 'Aksesoris Komputer', 'Perabotan', 'Pakaian', 'Makanan & Minuman', 'Kesehatan & Kecantikan', 'Alat Tulis', 'Lainnya'];
                    $selectedKategori = old('kategori');
                    foreach ($kategoriList as $kat):
                    ?>
                        <option value="<?= e($kat) ?>" <?= ($selectedKategori === $kat) ? 'selected' : '' ?>><?= e($kat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Kolom Nama Produk -->
        <div class="form-group">
            <label for="nama_produk" class="form-label">Nama Produk / Barang <span style="color: var(--danger);">*</span></label>
            <input 
                type="text" 
                id="nama_produk" 
                name="nama_produk" 
                class="form-control" 
                placeholder="Contoh: Monitor LG UltraGear 24 Inch" 
                value="<?= e(old('nama_produk')) ?>" 
                required
            >
        </div>

        <div class="form-row">
            <!-- Kolom Harga Satuan -->
            <div class="form-group">
                <label for="inputHarga" class="form-label">Harga Satuan (Rp) <span style="color: var(--danger);">*</span></label>
                <input 
                    type="number" 
                    step="0.01" 
                    min="0" 
                    id="inputHarga" 
                    name="harga" 
                    class="form-control" 
                    placeholder="0" 
                    value="<?= e(old('harga')) ?>" 
                    required
                >
            </div>

            <!-- Kolom Jumlah Stok -->
            <div class="form-group">
                <label for="stok" class="form-label">Jumlah Stok Awal <span style="color: var(--danger);">*</span></label>
                <input 
                    type="number" 
                    min="0" 
                    id="stok" 
                    name="stok" 
                    class="form-control" 
                    placeholder="0" 
                    value="<?= e(old('stok')) ?>" 
                    required
                >
            </div>
        </div>

        <!-- Kolom Deskripsi Produk -->
        <div class="form-group">
            <label for="deskripsi" class="form-label">Deskripsi / Spesifikasi Tambahan</label>
            <textarea 
                id="deskripsi" 
                name="deskripsi" 
                class="form-control" 
                rows="4" 
                placeholder="Tuliskan keterangan lengkap atau rincian spesifikasi barang di sini..."
            ><?= e(old('deskripsi')) ?></textarea>
        </div>

        <!-- Tombol Aksi Simpan atau Batal -->
        <div class="form-actions">
            <a href="index.php" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Simpan Produk
            </button>
        </div>
    </form>
</div>

<?php
// Memuat template footer HTML
require_once __DIR__ . '/includes/footer.php';
?>
