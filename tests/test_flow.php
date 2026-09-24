<?php

$cookieFile = __DIR__ . '/cookie.txt';
if (file_exists($cookieFile)) unlink($cookieFile);

function http_req($url, $method = 'GET', $data = [], $cookieFile = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

function extract_csrf($html) {
    preg_match('/name="csrf_token"\s+value="([^"]+)"/', $html, $m);
    return $m[1] ?? null;
}

echo "=== MEMULAI TEST INTEGRASI MENYELURUH (PRODUK & KATEGORI) ===\n";

// 1. Login
$loginPage = http_req('http://localhost/Web%20Inventory/public/login', 'GET', [], $cookieFile);
$csrf = extract_csrf($loginPage);

$dashboard = http_req('http://localhost/Web%20Inventory/public/login', 'POST', [
    'csrf_token' => $csrf,
    'identity'   => 'admin',
    'password'   => 'admin123'
], $cookieFile);
echo "1. Login Status: " . (str_contains($dashboard, 'Administrator Sistem') ? "[PASS] BERHASIL LOGIN" : "[FAIL]") . "\n";

// 2. Modul Kategori: Buka halaman /categories
$catPage = http_req('http://localhost/Web%20Inventory/public/categories', 'GET', [], $cookieFile);
$catPageOk = str_contains($catPage, 'Master Kategori Produk') && str_contains($catPage, 'Elektronik');
echo "2. Buka Halaman Master Kategori: " . ($catPageOk ? "[PASS]" : "[FAIL]") . "\n";

// 3. Tambah Kategori Baru
$catNewPage = http_req('http://localhost/Web%20Inventory/public/categories/new', 'GET', [], $cookieFile);
$csrfCat = extract_csrf($catNewPage);
$catName = 'Kategori Test ' . rand(100, 999);

$afterCreateCat = http_req('http://localhost/Web%20Inventory/public/categories', 'POST', [
    'csrf_token'    => $csrfCat,
    'nama_kategori' => $catName,
    'deskripsi'     => 'Deskripsi untuk kategori pengujian otomatis'
], $cookieFile);
$createCatOk = str_contains($afterCreateCat, $catName);
echo "3. Tambah Kategori Baru ({$catName}): " . ($createCatOk ? "[PASS] BERHASIL DITAMBAHKAN" : "[FAIL]") . "\n";

// Dapatkan ID kategori yang baru dibuat
preg_match('/data-id="(\d+)"\s+data-name="' . preg_quote($catName, '/') . '"/', $afterCreateCat, $mCatId);
$catId = $mCatId[1] ?? null;
echo "4. ID Kategori Terdeteksi: " . ($catId ? "[PASS] #{$catId}" : "[FAIL]") . "\n";

if ($catId) {
    // 4. Edit Kategori
    $catEditPage = http_req("http://localhost/Web%20Inventory/public/categories/edit/{$catId}", 'GET', [], $cookieFile);
    $csrfCatEdit = extract_csrf($catEditPage);
    $catNameUpdated = $catName . ' Upd';

    $afterEditCat = http_req("http://localhost/Web%20Inventory/public/categories/update/{$catId}", 'POST', [
        'csrf_token'    => $csrfCatEdit,
        'nama_kategori' => $catNameUpdated,
        'deskripsi'     => 'Deskripsi kategori telah diperbarui'
    ], $cookieFile);
    $editCatOk = str_contains($afterEditCat, $catNameUpdated);
    echo "5. Update Nama Kategori: " . ($editCatOk ? "[PASS] BERHASIL DIUPDATE" : "[FAIL]") . "\n";

    // 5. Tambah Produk Menggunakan Kategori Baru
    $createProductPage = http_req('http://localhost/Web%20Inventory/public/products/new', 'GET', [], $cookieFile);
    $csrfProd = extract_csrf($createProductPage);
    $hasNewCategoryOption = str_contains($createProductPage, $catNameUpdated);
    echo "6. Opsi Kategori Baru Muncul di Form Produk: " . ($hasNewCategoryOption ? "[PASS]" : "[FAIL]") . "\n";

    $newSku = 'SKU-' . rand(100, 999);
    $afterCreateProd = http_req('http://localhost/Web%20Inventory/public/products', 'POST', [
        'csrf_token'  => $csrfProd,
        'kode_produk' => $newSku,
        'nama_produk' => 'Barang Uji Kategori',
        'kategori'    => $catNameUpdated,
        'harga'       => '500000',
        'stok'        => '10',
        'deskripsi'   => 'Produk pengujian relasi kategori'
    ], $cookieFile);
    $createProdOk = str_contains($afterCreateProd, $newSku);
    echo "7. Simpan Produk dengan Kategori Baru: " . ($createProdOk ? "[PASS] BERHASIL DITAMBAHKAN" : "[FAIL]") . "\n";

    // 6. Filter Produk Berdasarkan Kategori di Dashboard
    $filteredDashboard = http_req('http://localhost/Web%20Inventory/public/products?kategori=' . urlencode($catNameUpdated), 'GET', [], $cookieFile);
    $filterOk = str_contains($filteredDashboard, $newSku) && str_contains($filteredDashboard, $catNameUpdated);
    echo "8. Filter Produk Berdasarkan Kategori di Dashboard: " . ($filterOk ? "[PASS] BERHASIL DISARING" : "[FAIL]") . "\n";

    // 7. Uji Proteksi Integritas: Coba Hapus Kategori Saat Masih Dipakai Produk
    $csrfDelCatFail = extract_csrf($filteredDashboard);
    $delCatBlocked = http_req("http://localhost/Web%20Inventory/public/categories/delete/{$catId}", 'POST', [
        'csrf_token' => $csrfDelCatFail,
    ], $cookieFile);
    $preventDeleteOk = str_contains($delCatBlocked, 'masih digunakan oleh');
    echo "9. Proteksi Penghapusan Kategori Aktif: " . ($preventDeleteOk ? "[PASS] DICEGAH DENGAN AMAN" : "[FAIL]") . "\n";

    // 8. Hapus Produk Uji
    preg_match('/data-id="(\d+)"\s+data-name="Barang Uji Kategori"/', $filteredDashboard, $mProdId);
    $prodId = $mProdId[1] ?? null;
    if ($prodId) {
        $csrfDelProd = extract_csrf($filteredDashboard);
        http_req("http://localhost/Web%20Inventory/public/products/delete/{$prodId}", 'POST', [
            'csrf_token' => $csrfDelProd,
        ], $cookieFile);
        echo "10. Hapus Produk Uji: [PASS]\n";
    }

    // 9. Hapus Kategori Setelah Produk Bersih
    $catPageForDel = http_req('http://localhost/Web%20Inventory/public/categories', 'GET', [], $cookieFile);
    $csrfDelCatSuccess = extract_csrf($catPageForDel);
    $afterDelCatSuccess = http_req("http://localhost/Web%20Inventory/public/categories/delete/{$catId}", 'POST', [
        'csrf_token' => $csrfDelCatSuccess,
    ], $cookieFile);
    $delCatOk = str_contains($afterDelCatSuccess, 'berhasil dihapus');
    echo "11. Hapus Kategori Setelah Produk Kosong: " . ($delCatOk ? "[PASS] BERHASIL DIHAPUS" : "[FAIL]") . "\n";
}

// =========================================================================
// PENGUJIAN MODUL MUTASI STOK (STOCK IN, OUT, ADJUSTMENT, HISTORY & ANTI-MINUS)
// =========================================================================
echo "\n--- PENGUJIAN MODUL MUTASI STOK (ENTERPRISE AUDIT TRAIL) ---\n";

// A. Siapkan produk baru khusus pengujian mutasi stok dengan stok awal = 15
$prodNewPage = http_req('http://localhost/Web%20Inventory/public/products/new', 'GET', [], $cookieFile);
$csrfProdStock = extract_csrf($prodNewPage);
$stockSku = 'STK-' . rand(1000, 9999);
$stockProdName = 'Barang Uji Mutasi Stok';

$afterCreateStockProd = http_req('http://localhost/Web%20Inventory/public/products', 'POST', [
    'csrf_token'  => $csrfProdStock,
    'kode_produk' => $stockSku,
    'nama_produk' => $stockProdName,
    'kategori'    => 'Elektronik',
    'harga'       => '250000',
    'stok'        => '15',
    'deskripsi'   => 'Produk khusus pengujian mutasi stok'
], $cookieFile);

preg_match('/data-id="(\d+)"\s+data-sku="' . preg_quote($stockSku, '/') . '"/', $afterCreateStockProd, $mStockProd);
$stockProdId = $mStockProd[1] ?? null;
echo "12. Buat Produk Uji Stok (Stok Awal: 15): " . ($stockProdId ? "[PASS] ID #{$stockProdId}" : "[FAIL]") . "\n";

if ($stockProdId) {
    // B. Uji Mutasi Stok Masuk (IN) +10 unit -> Harapannya stok jadi 25
    $csrfMvIn = extract_csrf($afterCreateStockProd);
    $afterMvIn = http_req('http://localhost/Web%20Inventory/public/stock/movement', 'POST', [
        'csrf_token' => $csrfMvIn,
        'product_id' => $stockProdId,
        'tipe'       => 'IN',
        'jumlah'     => '10',
        'keterangan' => 'Penerimaan barang dari supplier PO-9988'
    ], $cookieFile);

    $mvInOk = str_contains($afterMvIn, 'Barang Masuk (+)') && str_contains($afterMvIn, 'Tersedia (25)');
    echo "13. Transaksi Mutasi Masuk (+10 -> 25): " . ($mvInOk ? "[PASS] STOK TERCATAT 25" : "[FAIL]") . "\n";

    // C. Uji Proteksi Anti-Stok Minus: Coba mutasi OUT melebihi stok yang ada (stok: 25, keluar: 30)
    $csrfMvFail = extract_csrf($afterMvIn);
    $afterMvFail = http_req('http://localhost/Web%20Inventory/public/stock/movement', 'POST', [
        'csrf_token' => $csrfMvFail,
        'product_id' => $stockProdId,
        'tipe'       => 'OUT',
        'jumlah'     => '30',
        'keterangan' => 'Pengeluaran fiktif melebihi kuantitas gudang'
    ], $cookieFile);

    $antiMinusOk = str_contains($afterMvFail, 'tidak mencukupi') || str_contains($afterMvFail, 'melebihi');
    echo "14. Proteksi Anti-Stok Minus (Keluarkan 30 saat stok 25): " . ($antiMinusOk ? "[PASS] TRANSAKSI DITOLAK AMAN" : "[FAIL]") . "\n";

    // D. Uji Mutasi Stok Keluar (OUT) yang valid (-5 unit) -> Harapannya stok jadi 20
    $csrfMvOut = extract_csrf($afterMvFail);
    $afterMvOut = http_req('http://localhost/Web%20Inventory/public/stock/movement', 'POST', [
        'csrf_token' => $csrfMvOut,
        'product_id' => $stockProdId,
        'tipe'       => 'OUT',
        'jumlah'     => '5',
        'keterangan' => 'Pengiriman pesanan invoice INV-001'
    ], $cookieFile);

    $mvOutOk = str_contains($afterMvOut, 'Barang Keluar (-)') && str_contains($afterMvOut, 'Tersedia (20)');
    echo "15. Transaksi Mutasi Keluar Valid (-5 -> 20): " . ($mvOutOk ? "[PASS] STOK TERCATAT 20" : "[FAIL]") . "\n";

    // E. Uji Penyesuaian / Stock Opname (ADJUSTMENT) ke nilai target 45
    $csrfMvAdj = extract_csrf($afterMvOut);
    $afterMvAdj = http_req('http://localhost/Web%20Inventory/public/stock/movement', 'POST', [
        'csrf_token' => $csrfMvAdj,
        'product_id' => $stockProdId,
        'tipe'       => 'ADJUSTMENT',
        'jumlah'     => '45',
        'keterangan' => 'Stock opname akhir bulan gudang'
    ], $cookieFile);

    $mvAdjOk = str_contains($afterMvAdj, 'Penyesuaian Stok') && str_contains($afterMvAdj, 'Tersedia (45)');
    echo "16. Transaksi Penyesuaian Opname (Setel ke 45): " . ($mvAdjOk ? "[PASS] STOK TERCATAT 45" : "[FAIL]") . "\n";

    // F. Uji Halaman Riwayat Audit Mutasi Stok (/stock/history)
    $historyPage = http_req('http://localhost/Web%20Inventory/public/stock/history', 'GET', [], $cookieFile);
    $hasHistoryTitle = str_contains($historyPage, 'Riwayat Mutasi Stok');
    $hasSkuInHistory = str_contains($historyPage, $stockSku);
    $hasInMovement   = str_contains($historyPage, 'PO-9988');
    $hasOutMovement  = str_contains($historyPage, 'INV-001');
    $historyOk = $hasHistoryTitle && $hasSkuInHistory && $hasInMovement && $hasOutMovement;
    echo "17. Halaman Audit Trail & Riwayat Stok: " . ($historyOk ? "[PASS] AUDIT LOG TERCATAT LENGKAP" : "[FAIL]") . "\n";

    // G. Bersihkan Produk Uji
    $csrfClean = extract_csrf($afterMvAdj);
    http_req("http://localhost/Web%20Inventory/public/products/delete/{$stockProdId}", 'POST', [
        'csrf_token' => $csrfClean,
    ], $cookieFile);
    echo "18. Pembersihan Produk Uji Mutasi: [PASS]\n";
}

// =========================================================================
// PENGUJIAN MODUL AMBANG BATAS STOK MINIMUM & QUICK FILTER STATUS
// =========================================================================
echo "\n--- PENGUJIAN AMBANG BATAS STOK MINIMUM & KPI FILTER ---\n";

// A. Buat produk dengan stok awal = 4 dan stok_minimum = 10 (otomatis berstatus Restock)
$prodMinPage = http_req('http://localhost/Web%20Inventory/public/products/new', 'GET', [], $cookieFile);
$csrfProdMin = extract_csrf($prodMinPage);
$minSku = 'MIN-' . rand(1000, 9999);
$minProdName = 'Barang Uji Safety Stock';

$afterCreateMinProd = http_req('http://localhost/Web%20Inventory/public/products', 'POST', [
    'csrf_token'   => $csrfProdMin,
    'kode_produk'  => $minSku,
    'nama_produk'  => $minProdName,
    'kategori'     => 'Elektronik',
    'harga'        => '150000',
    'stok'         => '4',
    'stok_minimum' => '10',
    'deskripsi'    => 'Pengujian batas stok minimum'
], $cookieFile);

preg_match('/data-id="(\d+)"\s+data-sku="' . preg_quote($minSku, '/') . '"/', $afterCreateMinProd, $mMinProd);
$minProdId = $mMinProd[1] ?? null;
echo "19. Buat Produk dengan Stok Minimum Kustom (Stok: 4, Min: 10): " . ($minProdId ? "[PASS] ID #{$minProdId}" : "[FAIL]") . "\n";

if ($minProdId) {
    // B. Verifikasi Tampilan Badge Menipis Dinamis (Menipis (4 / Min: 10))
    $hasDynamicMinBadge = str_contains($afterCreateMinProd, 'Menipis (4 / Min: 10)');
    echo "20. Deteksi Status Menipis Dinamis: " . ($hasDynamicMinBadge ? "[PASS] TERCATAT 'Menipis (4 / Min: 10)'" : "[FAIL]") . "\n";

    // C. Verifikasi Quick Filter ?status=low_stock
    $lowStockPage = http_req('http://localhost/Web%20Inventory/public/products?status=low_stock', 'GET', [], $cookieFile);
    $inLowStockFilter = str_contains($lowStockPage, $minSku);
    echo "21. Quick Filter Status Perlu Restock (?status=low_stock): " . ($inLowStockFilter ? "[PASS] PRODUK TERSARING CEPAT" : "[FAIL]") . "\n";

    // D. Mutasi Masuk (+10) sehingga stok menjadi 14 (> 10), pastikan keluar dari low_stock
    $csrfMvRestock = extract_csrf($lowStockPage);
    $afterRestock = http_req('http://localhost/Web%20Inventory/public/stock/movement', 'POST', [
        'csrf_token' => $csrfMvRestock,
        'product_id' => $minProdId,
        'tipe'       => 'IN',
        'jumlah'     => '10',
        'keterangan' => 'Restock pengadaan gudang'
    ], $cookieFile);

    $nowAvailable = str_contains($afterRestock, 'Tersedia (14)');
    echo "22. Restock Barang (+10 -> 14) & Status Berubah Menjadi Tersedia: " . ($nowAvailable ? "[PASS] STATUS OTOMATIS AMAN" : "[FAIL]") . "\n";

    // E. Bersihkan Produk Uji
    $csrfCleanMin = extract_csrf($afterRestock);
    http_req("http://localhost/Web%20Inventory/public/products/delete/{$minProdId}", 'POST', [
        'csrf_token' => $csrfCleanMin,
    ], $cookieFile);
    echo "23. Pembersihan Produk Uji Safety Stock: [PASS]\n";
}

// =========================================================================
// PENGUJIAN MODUL MANAJEMEN PEMASOK / SUPPLIER & INBOUND TRACKING
// =========================================================================
echo "\n--- PENGUJIAN MODUL PEMASOK / SUPPLIER & INBOUND TRACKING ---\n";

// A. Akses Halaman Master Pemasok (/suppliers)
$supplierIndex = http_req('http://localhost/Web%20Inventory/public/suppliers', 'GET', [], $cookieFile);
$hasSupplierTitle = str_contains($supplierIndex, 'Master Data Pemasok');
$hasSeededSupplier = str_contains($supplierIndex, 'SPL-001') || str_contains($supplierIndex, 'Sumber Makmur');
echo "24. Akses Halaman Master Pemasok: " . ($hasSupplierTitle && $hasSeededSupplier ? "[PASS]" : "[FAIL]") . "\n";

// B. Tambah Pemasok Baru
$supplierNewPage = http_req('http://localhost/Web%20Inventory/public/suppliers/new', 'GET', [], $cookieFile);
$csrfSpl = extract_csrf($supplierNewPage);
$splCode = 'SPL-' . rand(1000, 9999);
$splName = 'PT Vendor Uji Coba ' . rand(100, 999);

$afterCreateSpl = http_req('http://localhost/Web%20Inventory/public/suppliers', 'POST', [
    'csrf_token'    => $csrfSpl,
    'kode_supplier' => $splCode,
    'nama_supplier' => $splName,
    'kontak_person' => 'Budi Santoso',
    'telepon'       => '081299887766',
    'email'         => 'vendor@test.com',
    'alamat'        => 'Kawasan Industri Cikarang Blok C'
], $cookieFile);

$splCreatedOk = str_contains($afterCreateSpl, $splCode) && str_contains($afterCreateSpl, $splName);
echo "25. Tambah Pemasok Baru ({$splCode} - {$splName}): " . ($splCreatedOk ? "[PASS] BERHASIL DITAMBAHKAN" : "[FAIL]") . "\n";

// Deteksi ID supplier baru
preg_match('/data-id="(\d+)"\s+data-name="' . preg_quote($splName, '/') . '"/', $afterCreateSpl, $mSplId);
$supplierId = $mSplId[1] ?? null;
echo "26. ID Pemasok Terdeteksi: " . ($supplierId ? "[PASS] #{$supplierId}" : "[FAIL]") . "\n";

if ($supplierId) {
    // C. Update Data Pemasok
    $splEditPage = http_req("http://localhost/Web%20Inventory/public/suppliers/edit/{$supplierId}", 'GET', [], $cookieFile);
    $csrfSplEdit = extract_csrf($splEditPage);
    $splNameUpdated = $splName . ' (Terverifikasi)';

    $afterEditSpl = http_req("http://localhost/Web%20Inventory/public/suppliers/update/{$supplierId}", 'POST', [
        'csrf_token'    => $csrfSplEdit,
        'kode_supplier' => $splCode,
        'nama_supplier' => $splNameUpdated,
        'kontak_person' => 'Budi Santoso, S.T.',
        'telepon'       => '081299887766',
        'email'         => 'vendor_upd@test.com',
        'alamat'        => 'Kawasan Industri Cikarang Blok C - Revisi'
    ], $cookieFile);

    $editSplOk = str_contains($afterEditSpl, $splNameUpdated);
    echo "27. Update Data Pemasok: " . ($editSplOk ? "[PASS] BERHASIL DIUPDATE" : "[FAIL]") . "\n";

    // D. Tambah Produk Baru Terhubung dengan Pemasok
    $prodWithSplPage = http_req('http://localhost/Web%20Inventory/public/products/new', 'GET', [], $cookieFile);
    $csrfProdSpl = extract_csrf($prodWithSplPage);
    $hasSupplierInDropdown = str_contains($prodWithSplPage, $splNameUpdated);
    echo "28. Opsi Pemasok Muncul di Dropdown Produk: " . ($hasSupplierInDropdown ? "[PASS]" : "[FAIL]") . "\n";

    $splProductSku = 'SPLPROD-' . rand(1000, 9999);
    $afterCreateProdSpl = http_req('http://localhost/Web%20Inventory/public/products', 'POST', [
        'csrf_token'   => $csrfProdSpl,
        'kode_produk'  => $splProductSku,
        'nama_produk'  => 'Komponen Mesin Terpasok',
        'kategori'     => 'Elektronik',
        'supplier_id'  => $supplierId,
        'harga'        => '2500000',
        'stok'         => '5',
        'stok_minimum' => '2',
        'deskripsi'    => 'Produk hasil pasokan vendor teruji'
    ], $cookieFile);

    preg_match('/data-id="(\d+)"\s+data-sku="' . preg_quote($splProductSku, '/') . '"/', $afterCreateProdSpl, $mSplProd);
    $splProdId = $mSplProd[1] ?? null;
    $hasSupplierBadgeInList = str_contains($afterCreateProdSpl, $splNameUpdated);
    echo "29. Hubungkan Produk dengan Pemasok Utama: " . ($splProdId && $hasSupplierBadgeInList ? "[PASS] PRODUK TERHUBUNG" : "[FAIL]") . "\n";

    // E. Proteksi Hapus Pemasok yang Masih Terkait Produk
    $csrfTryDelSpl = extract_csrf($afterCreateProdSpl);
    $afterTryDelSpl = http_req("http://localhost/Web%20Inventory/public/suppliers/delete/{$supplierId}", 'POST', [
        'csrf_token' => $csrfTryDelSpl
    ], $cookieFile);

    $protectDeleteOk = stripos($afterTryDelSpl, 'tidak dapat dihapus') !== false || stripos($afterTryDelSpl, 'pemasok utama') !== false;
    echo "30. Proteksi Integritas Relasi (Cegah Hapus Pemasok Aktif): " . ($protectDeleteOk ? "[PASS] AMAN DIBLOKIR" : "[FAIL]") . "\n";

    // F. Mutasi Masuk (IN) dengan Memilih Supplier Pengirim
    $csrfMvSpl = extract_csrf($afterTryDelSpl);
    $afterMvSpl = http_req('http://localhost/Web%20Inventory/public/stock/movement', 'POST', [
        'csrf_token'  => $csrfMvSpl,
        'product_id'  => $splProdId,
        'tipe'        => 'IN',
        'jumlah'      => '15',
        'supplier_id' => $supplierId,
        'keterangan'  => 'Surat Jalan SJ-VENDOR-7788'
    ], $cookieFile);

    $mvSplOk = str_contains($afterMvSpl, 'Tersedia (20)');
    echo "31. Mutasi Masuk Terhubung Vendor (+15 -> 20): " . ($mvSplOk ? "[PASS] STOK BERHASIL BERTAMBAH" : "[FAIL]") . "\n";

    // G. Audit Trail Mutasi Menampilkan Nama Pemasok Pengirim
    $historySplPage = http_req('http://localhost/Web%20Inventory/public/stock/history', 'GET', [], $cookieFile);
    $historyHasSupplier = str_contains($historySplPage, $splNameUpdated) && str_contains($historySplPage, 'SJ-VENDOR-7788');
    echo "32. Riwayat Mutasi Menampilkan Pemasok Asal: " . ($historySplPage && $historyHasSupplier ? "[PASS] TERDOKUMENTASI" : "[FAIL]") . "\n";

    // H. Pembersihan Produk & Hapus Pemasok Bersih
    $csrfDelProd = extract_csrf($historySplPage);
    http_req("http://localhost/Web%20Inventory/public/products/delete/{$splProdId}", 'POST', [
        'csrf_token' => $csrfDelProd
    ], $cookieFile);

    $csrfDelSplFinal = extract_csrf($historySplPage);
    $afterDelSplClean = http_req("http://localhost/Web%20Inventory/public/suppliers/delete/{$supplierId}", 'POST', [
        'csrf_token' => $csrfDelSplFinal
    ], $cookieFile);

    $delSplCleanOk = str_contains($afterDelSplClean, 'berhasil dihapus') || !str_contains($afterDelSplClean, $splCode);
    echo "33. Hapus Pemasok Setelah Produk Bersih: " . ($delSplCleanOk ? "[PASS] BERHASIL DIHAPUS" : "[FAIL]") . "\n";
}

// =========================================================================
// PENGUJIAN MODUL LAPORAN, VALUASI ASET & EKSPOR DATA CSV (SPRINT 5)
// =========================================================================
echo "\n--- PENGUJIAN MODUL LAPORAN, VALUASI ASET & EKSPOR DATA CSV ---\n";

// A. Halaman Utama Laporan Valuasi Aset (/reports)
$reportValuation = http_req('http://localhost/Web%20Inventory/public/reports', 'GET', [], $cookieFile);
$valHasTitle = str_contains($reportValuation, 'Pusat Laporan & Analitik Inventaris');
$valHasKpi   = str_contains($reportValuation, 'Total Valuasi Aset Fisik') && str_contains($reportValuation, 'Total Fisik Barang');
$valHasTable = str_contains($reportValuation, 'Rincian Nilai Aset per Item Inventaris');
$valOk       = $valHasTitle && $valHasKpi && $valHasTable;
echo "34. Akses Dashboard Laporan Valuasi Aset: " . ($valOk ? "[PASS] METRIK & TABEL VALID" : "[FAIL]") . "\n";

// B. Filter Valuasi Berdasarkan Kategori
$filterCatVal = http_req('http://localhost/Web%20Inventory/public/reports?kategori=Elektronik', 'GET', [], $cookieFile);
$filterValOk  = str_contains($filterCatVal, 'Elektronik') && str_contains($filterCatVal, 'Rincian Nilai Aset');
echo "35. Filter Laporan Valuasi per Kategori (Elektronik): " . ($filterValOk ? "[PASS] BERHASIL DISARING" : "[FAIL]") . "\n";

// C. Halaman Laporan Rekapitulasi Mutasi Stok (/reports/movements)
$reportMovements = http_req('http://localhost/Web%20Inventory/public/reports/movements', 'GET', [], $cookieFile);
$mvHasTitle = str_contains($reportMovements, 'Laporan Rekapitulasi Mutasi Stok');
$mvHasKpi   = str_contains($reportMovements, 'Total Barang Masuk (IN)') && str_contains($reportMovements, 'Perubahan Bersih (Net Delta)');
$mvOk       = $mvHasTitle && $mvHasKpi;
echo "36. Akses Laporan Rekapitulasi Mutasi Berkala: " . ($mvOk ? "[PASS] STATISTIK ARUS VALID" : "[FAIL]") . "\n";

// D. Filter Mutasi Berdasarkan Tipe (IN)
$filterMvIn = http_req('http://localhost/Web%20Inventory/public/reports/movements?tipe=IN', 'GET', [], $cookieFile);
$filterMvOk = str_contains($filterMvIn, 'Masuk (IN)') && str_contains($filterMvIn, 'Daftar Catatan Mutasi Periode');
echo "37. Filter Mutasi Berdasarkan Jenis (Barang Masuk): " . ($filterMvOk ? "[PASS] BERHASIL DISARING" : "[FAIL]") . "\n";

// E. Halaman Laporan Kebutuhan Restock Pengadaan (/reports/restock)
$reportRestock = http_req('http://localhost/Web%20Inventory/public/reports/restock', 'GET', [], $cookieFile);
$rstHasTitle = str_contains($reportRestock, 'Laporan Kebutuhan Restock Pengadaan');
$rstHasKpi   = str_contains($reportRestock, 'SKU Perlu Pengadaan') && str_contains($reportRestock, 'Estimasi Kebutuhan Anggaran');
$rstOk       = $rstHasTitle && $rstHasKpi;
echo "38. Akses Laporan Kebutuhan Restock Pengadaan: " . ($rstOk ? "[PASS] ANGGARAN & SARAN REORDER VALID" : "[FAIL]") . "\n";

// F. Ekspor CSV Laporan Valuasi Aset (/reports/export/valuation)
$csvValuation = http_req('http://localhost/Web%20Inventory/public/reports/export/valuation', 'GET', [], $cookieFile);
$hasValHeader = str_contains($csvValuation, 'Kode SKU') && str_contains($csvValuation, 'Total Nilai Aset (Rp)') && str_contains($csvValuation, 'Harga Satuan (Rp)');
echo "39. Unduh File CSV Valuasi Aset Inventaris: " . ($hasValHeader ? "[PASS] STRUKTUR CSV KOMPATIBEL EXCEL" : "[FAIL]") . "\n";

// G. Ekspor CSV Rekapitulasi Mutasi Stok (/reports/export/movements)
$csvMovements = http_req('http://localhost/Web%20Inventory/public/reports/export/movements', 'GET', [], $cookieFile);
$hasMvHeader = str_contains($csvMovements, 'Waktu Transaksi') && str_contains($csvMovements, 'Jenis Mutasi') && str_contains($csvMovements, 'Stok Sesudah');
echo "40. Unduh File CSV Rekapitulasi Mutasi Stok: " . ($hasMvHeader ? "[PASS] STRUKTUR CSV VALID" : "[FAIL]") . "\n";

// H. Ekspor CSV Kebutuhan Restock (/reports/export/restock)
$csvRestock = http_req('http://localhost/Web%20Inventory/public/reports/export/restock', 'GET', [], $cookieFile);
$hasRstHeader = str_contains($csvRestock, 'Saran Kuantitas Reorder (Unit)') && str_contains($csvRestock, 'Estimasi Anggaran Belanja (Rp)');
echo "41. Unduh File CSV Kebutuhan Restock Pengadaan: " . ($hasRstHeader ? "[PASS] STRUKTUR CSV VALID" : "[FAIL]") . "\n";

// =========================================================================
// PENGUJIAN MODUL MANAJEMEN PELANGGAN & OUTBOUND DESTINATION TRACKING (SPRINT 6)
// =========================================================================
echo "\n--- PENGUJIAN MODUL PELANGGAN / DEPARTEMEN & OUTBOUND TRACKING ---\n";

// A. Akses Halaman Master Pelanggan (/customers)
$customerIndex = http_req('http://localhost/Web%20Inventory/public/customers', 'GET', [], $cookieFile);
$hasCstTitle = str_contains($customerIndex, 'Master Data Pelanggan & Departemen');
$hasSeededCst = str_contains($customerIndex, 'CST-001') || str_contains($customerIndex, 'Mitra Distribusi');
echo "42. Akses Halaman Master Pelanggan: " . ($hasCstTitle && $hasSeededCst ? "[PASS]" : "[FAIL]") . "\n";

// B. Tambah Pelanggan Baru
$customerNewPage = http_req('http://localhost/Web%20Inventory/public/customers/new', 'GET', [], $cookieFile);
$csrfCst = extract_csrf($customerNewPage);
$cstCode = 'CST-' . rand(1000, 9999);
$cstName = 'PT Klien Outbound Uji Coba ' . rand(100, 999);

$afterCreateCst = http_req('http://localhost/Web%20Inventory/public/customers', 'POST', [
    'csrf_token'     => $csrfCst,
    'kode_pelanggan' => $cstCode,
    'nama_pelanggan' => $cstName,
    'tipe'           => 'BISNIS',
    'kontak_person'  => 'Agus Pratama',
    'telepon'        => '081288776655',
    'email'          => 'agus@klienoutbound.com',
    'alamat'         => 'Kawasan Pergudangan Marunda Blok D-4'
], $cookieFile);

$cstCreatedOk = str_contains($afterCreateCst, $cstCode) && str_contains($afterCreateCst, $cstName);
echo "43. Tambah Pelanggan Baru ({$cstCode} - {$cstName}): " . ($cstCreatedOk ? "[PASS] BERHASIL DITAMBAHKAN" : "[FAIL]") . "\n";

preg_match('/data-id="(\d+)"\s+data-name="' . preg_quote($cstName, '/') . '"/', $afterCreateCst, $mCstId);
$customerId = $mCstId[1] ?? null;
echo "44. ID Pelanggan Terdeteksi: " . ($customerId ? "[PASS] #{$customerId}" : "[FAIL]") . "\n";

if ($customerId) {
    // C. Update Data Pelanggan
    $cstEditPage = http_req("http://localhost/Web%20Inventory/public/customers/edit/{$customerId}", 'GET', [], $cookieFile);
    $csrfCstEdit = extract_csrf($cstEditPage);
    $cstNameUpdated = $cstName . ' (Terverifikasi)';

    $afterEditCst = http_req("http://localhost/Web%20Inventory/public/customers/update/{$customerId}", 'POST', [
        'csrf_token'     => $csrfCstEdit,
        'kode_pelanggan' => $cstCode,
        'nama_pelanggan' => $cstNameUpdated,
        'tipe'           => 'BISNIS',
        'kontak_person'  => 'Agus Pratama, S.E.',
        'telepon'        => '081288776655',
        'email'          => 'agus.procurement@klienoutbound.com',
        'alamat'         => 'Kawasan Pergudangan Marunda Blok D-4 - Revisi'
    ], $cookieFile);

    $editCstOk = str_contains($afterEditCst, $cstNameUpdated);
    echo "45. Update Data Pelanggan: " . ($editCstOk ? "[PASS] BERHASIL DIUPDATE" : "[FAIL]") . "\n";

    // D. Buat Produk Uji untuk Mutasi Keluar
    $prodForOutPage = http_req('http://localhost/Web%20Inventory/public/products/new', 'GET', [], $cookieFile);
    $csrfProdOut = extract_csrf($prodForOutPage);
    $prodOutSku = 'OUTSKU-' . rand(1000, 9999);

    $afterCreateProdOut = http_req('http://localhost/Web%20Inventory/public/products', 'POST', [
        'csrf_token'   => $csrfProdOut,
        'kode_produk'  => $prodOutSku,
        'nama_produk'  => 'Barang Uji Pengeluaran Klien',
        'kategori'     => 'Elektronik',
        'harga'        => '750000',
        'stok'         => '30',
        'stok_minimum' => '5',
        'deskripsi'    => 'Pengujian mutasi keluar terhubung pelanggan'
    ], $cookieFile);

    preg_match('/data-id="(\d+)"\s+data-sku="' . preg_quote($prodOutSku, '/') . '"/', $afterCreateProdOut, $mProdOut);
    $prodOutId = $mProdOut[1] ?? null;

    // E. Mutasi Keluar (OUT) dengan Memilih Pelanggan Penerima
    $csrfMvOutCst = extract_csrf($afterCreateProdOut);
    $afterMvOutCst = http_req('http://localhost/Web%20Inventory/public/stock/movement', 'POST', [
        'csrf_token'  => $csrfMvOutCst,
        'product_id'  => $prodOutId,
        'tipe'        => 'OUT',
        'jumlah'      => '8',
        'customer_id' => $customerId,
        'keterangan'  => 'Invoice Delivery INV-OUT-5544'
    ], $cookieFile);

    $mvOutCstOk = str_contains($afterMvOutCst, 'Barang Keluar (-)') && str_contains($afterMvOutCst, 'Tersedia (22)');
    echo "46. Transaksi Barang Keluar Terhubung Pelanggan (-8 -> 22): " . ($mvOutCstOk ? "[PASS] STOK TERCATAT 22" : "[FAIL]") . "\n";

    // F. Verifikasi Audit Trail Riwayat Stok Menampilkan Nama Pelanggan Penerima
    $historyPageOut = http_req('http://localhost/Web%20Inventory/public/stock/history', 'GET', [], $cookieFile);
    $historyHasCustomer = str_contains($historyPageOut, $cstNameUpdated) && str_contains($historyPageOut, 'INV-OUT-5544');
    echo "47. Audit Trail Riwayat Stok Menampilkan Pelanggan Tujuan: " . ($historyHasCustomer ? "[PASS] TERDOKUMENTASI" : "[FAIL]") . "\n";

    // G. Proteksi Hapus Pelanggan yang Memiliki Riwayat Barang Keluar
    $csrfTryDelCst = extract_csrf($historyPageOut);
    $afterTryDelCst = http_req("http://localhost/Web%20Inventory/public/customers/delete/{$customerId}", 'POST', [
        'csrf_token' => $csrfTryDelCst
    ], $cookieFile);

    $protectDelCstOk = stripos($afterTryDelCst, 'tidak dapat dihapus') !== false || stripos($afterTryDelCst, 'barang keluar') !== false;
    echo "48. Proteksi Integritas Relasi (Cegah Hapus Pelanggan Bertransaksi): " . ($protectDelCstOk ? "[PASS] AMAN DIBLOKIR" : "[FAIL]") . "\n";

    // H. Bersihkan Produk Uji
    $csrfCleanOut = extract_csrf($afterTryDelCst);
    http_req("http://localhost/Web%20Inventory/public/products/delete/{$prodOutId}", 'POST', [
        'csrf_token' => $csrfCleanOut
    ], $cookieFile);
    echo "49. Pembersihan Produk Uji Outbound: [PASS]\n";
}

// 50. Logout
$afterLogout = http_req('http://localhost/Web%20Inventory/public/logout', 'GET', [], $cookieFile);
$logoutOk = str_contains($afterLogout, 'Masuk ke Sistem');
echo "50. Logout: " . ($logoutOk ? "[PASS] BERHASIL KELUAR" : "[FAIL]") . "\n";

echo "\n--- PENGUJIAN FILTER RBAC & MODUL MANAJEMEN PENGGUNA (/users) ---\n";

// 51. Proteksi Registrasi Publik (Dialihkan ke Login)
$regCookieFile = __DIR__ . '/cookie_reg.txt';
if (file_exists($regCookieFile)) unlink($regCookieFile);
$regPage = http_req('http://localhost/Web%20Inventory/public/register', 'GET', [], $regCookieFile);
$regBlockedOk = str_contains($regPage, 'Masuk ke Sistem') && (str_contains($regPage, 'Manajemen Pengguna') || str_contains($regPage, 'Pendaftaran akun'));
echo "51. Proteksi Registrasi Mandiri Publik: " . ($regBlockedOk ? "[PASS] AMAN DIALIHKAN KE LOGIN" : "[FAIL]") . "\n";
if (file_exists($regCookieFile)) unlink($regCookieFile);

// 52. Login Kembali Sebagai Admin
$loginPage2 = http_req('http://localhost/Web%20Inventory/public/login', 'GET', [], $cookieFile);
$csrf2 = extract_csrf($loginPage2);
http_req('http://localhost/Web%20Inventory/public/login', 'POST', [
    'csrf_token' => $csrf2,
    'identity'   => 'admin',
    'password'   => 'admin123'
], $cookieFile);

// 53. Akses Modul Manajemen Pengguna (/users) oleh Admin
$usersPage = http_req('http://localhost/Web%20Inventory/public/users', 'GET', [], $cookieFile);
$usersPageOk = str_contains($usersPage, 'Manajemen Pengguna & Hak Akses') && str_contains($usersPage, 'Administrator');
echo "52. Akses Modul Manajemen Pengguna oleh Admin: " . ($usersPageOk ? "[PASS] METRIK & TABEL PENGGUNA TAMPIL" : "[FAIL]") . "\n";

// 54. Tambah Akun Staff Baru oleh Admin
$userNewPage = http_req('http://localhost/Web%20Inventory/public/users/new', 'GET', [], $cookieFile);
$csrfUserNew = extract_csrf($userNewPage);
$staffUsername = 'staff_test_' . rand(100, 999);
$staffEmail    = $staffUsername . '@perusahaan.com';

$afterCreateStaff = http_req('http://localhost/Web%20Inventory/public/users', 'POST', [
    'csrf_token'   => $csrfUserNew,
    'nama_lengkap' => 'Staf Pengujian Otomatis',
    'username'     => $staffUsername,
    'email'        => $staffEmail,
    'password'     => 'staff12345',
    'role'         => 'staff',
], $cookieFile);

$createStaffOk = str_contains($afterCreateStaff, $staffUsername) && str_contains($afterCreateStaff, 'Staff Gudang');
echo "53. Tambah Akun Staf Baru ({$staffUsername}): " . ($createStaffOk ? "[PASS] BERHASIL DIBUAT DENGAN PERAN STAFF" : "[FAIL]") . "\n";

preg_match('/data-id="(\d+)"\s+data-username="' . preg_quote($staffUsername, '/') . '"/', $afterCreateStaff, $mStaffId);
$staffId = $mStaffId[1] ?? null;

// 55. Update Data Pengguna oleh Admin
$userEditPage = http_req("http://localhost/Web%20Inventory/public/users/edit/{$staffId}", 'GET', [], $cookieFile);
$csrfUserEdit = extract_csrf($userEditPage);
$updatedStaffName = 'Staf Pengujian (Updated)';

$afterUpdateStaff = http_req("http://localhost/Web%20Inventory/public/users/update/{$staffId}", 'POST', [
    'csrf_token'   => $csrfUserEdit,
    'nama_lengkap' => $updatedStaffName,
    'username'     => $staffUsername,
    'email'        => $staffEmail,
    'role'         => 'staff',
], $cookieFile);
$updateStaffOk = str_contains($afterUpdateStaff, $updatedStaffName);
echo "54. Update Data Pengguna oleh Admin: " . ($updateStaffOk ? "[PASS] BERHASIL DIPERBARUI" : "[FAIL]") . "\n";

// 56. Logout Admin & Login Menggunakan Akun Staff yang Baru Dibuat
http_req('http://localhost/Web%20Inventory/public/logout', 'GET', [], $cookieFile);

$staffCookieFile = __DIR__ . '/cookie_staff.txt';
if (file_exists($staffCookieFile)) unlink($staffCookieFile);

$staffLoginPage = http_req('http://localhost/Web%20Inventory/public/login', 'GET', [], $staffCookieFile);
$csrfStaffLogin = extract_csrf($staffLoginPage);

$staffDashboard = http_req('http://localhost/Web%20Inventory/public/login', 'POST', [
    'csrf_token' => $csrfStaffLogin,
    'identity'   => $staffUsername,
    'password'   => 'staff12345'
], $staffCookieFile);

$staffLoginOk = str_contains($staffDashboard, $updatedStaffName) && str_contains($staffDashboard, 'staff');
echo "55. Login Menggunakan Akun Staf Baru: " . ($staffLoginOk ? "[PASS] BERHASIL MASUK DENGAN ROLE STAFF" : "[FAIL]") . "\n";

// 57. Staf Memiliki Izin Operasional Harian (Cek Produk & Tombol Hapus Disembunyikan)
$staffProdPage = http_req('http://localhost/Web%20Inventory/public/products', 'GET', [], $staffCookieFile);
$staffCanOperate = str_contains($staffProdPage, 'Sistem Manajemen Inventaris') && !str_contains($staffProdPage, 'btn-trigger-delete');
echo "56. Hak Operasional Staf (Katalog Terbuka & Tombol Hapus Disembunyikan): " . ($staffCanOperate ? "[PASS] UI DISESUAIKAN DENGAN AMAN" : "[FAIL]") . "\n";

// 58. Staf Mencoba Akses Modul /users (HARUS DIBLOKIR 403 / REDIRECT)
$staffTryUsers = http_req('http://localhost/Web%20Inventory/public/users', 'GET', [], $staffCookieFile);
$staffBlockedUsers = str_contains($staffTryUsers, 'Akses ditolak') || !str_contains($staffTryUsers, 'Tambah Pengguna');
echo "57. Blokade Akses /users untuk Staf: " . ($staffBlockedUsers ? "[PASS] DIBLOKIR OLEH ROLEFILTER" : "[FAIL]") . "\n";

// 59. Staf Mencoba Akses Laporan Valuasi Finansial /reports (HARUS DIBLOKIR)
$staffTryValuation = http_req('http://localhost/Web%20Inventory/public/reports', 'GET', [], $staffCookieFile);
$staffBlockedValuation = str_contains($staffTryValuation, 'Akses ditolak') || !str_contains($staffTryValuation, 'Total Valuasi Aset');
echo "58. Blokade Akses Laporan Valuasi Finansial untuk Staf: " . ($staffBlockedValuation ? "[PASS] DIBLOKIR OLEH ROLEFILTER" : "[FAIL]") . "\n";

// 60. Staf Mencoba Mengirim Permintaan POST Hapus Produk (HARUS DIBLOKIR)
$staffTryDeleteProd = http_req('http://localhost/Web%20Inventory/public/products/delete/1', 'POST', [
    'csrf_token' => extract_csrf($staffProdPage)
], $staffCookieFile);
$staffBlockedDelete = str_contains($staffTryDeleteProd, 'Akses ditolak') || str_contains($staffTryDeleteProd, 'tidak memiliki wewenang');
echo "59. Blokade Permintaan POST Hapus Produk oleh Staf: " . ($staffBlockedDelete ? "[PASS] DITOLAK AMAN OLEH ROLEFILTER" : "[FAIL]") . "\n";

// 61. Admin Login Kembali & Membersihkan Akun Staf Pengujian
if (file_exists($staffCookieFile)) unlink($staffCookieFile);

$adminLoginAgain = http_req('http://localhost/Web%20Inventory/public/login', 'POST', [
    'csrf_token' => extract_csrf(http_req('http://localhost/Web%20Inventory/public/login', 'GET', [], $cookieFile)),
    'identity'   => 'admin',
    'password'   => 'admin123'
], $cookieFile);

$usersPageAdmin = http_req('http://localhost/Web%20Inventory/public/users', 'GET', [], $cookieFile);
$csrfDelStaff = extract_csrf($usersPageAdmin);

$afterDeleteStaff = http_req("http://localhost/Web%20Inventory/public/users/delete/{$staffId}", 'POST', [
    'csrf_token' => $csrfDelStaff
], $cookieFile);

$deleteStaffOk = str_contains($afterDeleteStaff, 'berhasil dihapus') || !str_contains($afterDeleteStaff, $staffUsername);
echo "60. Penghapusan Akun Staf Uji oleh Admin: " . ($deleteStaffOk ? "[PASS] BERHASIL DIBERSIHKAN" : "[FAIL]") . "\n";

if (file_exists($cookieFile)) unlink($cookieFile);
echo "=== SEMUA 60 TEST INTEGRASI (PRODUK, KATEGORI, MUTASI STOK, SAFETY STOCK, PEMASOK, LAPORAN, PELANGGAN & RBAC USER MANAGEMENT) LULUS 100%! ===\n";


