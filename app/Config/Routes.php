<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rute Tamu (Hanya dapat diakses jika belum login)
$routes->group('', ['namespace' => 'App\Controllers\Web', 'filter' => 'guest'], function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attemptLogin');

    // Registrasi publik dialihkan ke login demi keamanan aset perusahaan
    $routes->get('register', static function () {
        return redirect()->to(base_url('login'))->with('error', 'Pendaftaran akun hanya dapat dilakukan oleh Administrator melalui menu Manajemen Pengguna.');
    });
    $routes->post('register', static function () {
        return redirect()->to(base_url('login'));
    });
});

// Rute Logout
$routes->group('', ['namespace' => 'App\Controllers\Web'], function ($routes) {
    $routes->get('logout', 'Auth::logout');
    $routes->post('logout', 'Auth::logout');
});

// =========================================================================
// RUTE KHUSUS ADMINISTRATOR (HAK AKSES PENUH / RBAC LEVEL TINGGI)
// =========================================================================
$routes->group('', ['namespace' => 'App\Controllers\Web', 'filter' => ['auth', 'role:admin']], function ($routes) {
    // Modul Manajemen Pengguna & Hak Akses (User Management)
    $routes->get('users', 'Users::index');
    $routes->get('users/new', 'Users::new');
    $routes->post('users', 'Users::create');
    $routes->get('users/edit/(:num)', 'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
    $routes->post('users/delete/(:num)', 'Users::delete/$1');

    // Manajemen Taksonomi Kategori (Create, Edit, Delete)
    $routes->get('categories/new', 'Categories::new');
    $routes->post('categories', 'Categories::create');
    $routes->get('categories/edit/(:num)', 'Categories::edit/$1');
    $routes->post('categories/update/(:num)', 'Categories::update/$1');
    $routes->post('categories/delete/(:num)', 'Categories::delete/$1');

    // Proteksi Penghapusan Master Data (Hanya Admin)
    $routes->post('products/delete/(:num)', 'Products::delete/$1');
    $routes->post('suppliers/delete/(:num)', 'Suppliers::delete/$1');
    $routes->post('customers/delete/(:num)', 'Customers::delete/$1');

    // Laporan Finansial Sensitif (Valuasi Aset & Modal Usaha)
    $routes->get('reports', 'Reports::index');
    $routes->get('reports/export/valuation', 'Reports::exportValuationCsv');
});

// =========================================================================
// RUTE OPERASIONAL HARIAN (DAPAT DIAKSES OLEH ADMINISTRATOR & STAFF GUDANG)
// =========================================================================
$routes->group('', ['namespace' => 'App\Controllers\Web', 'filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Products::index');
    $routes->get('products', 'Products::index');
    $routes->get('products/new', 'Products::new');
    $routes->post('products', 'Products::create');
    $routes->get('products/edit/(:num)', 'Products::edit/$1');
    $routes->post('products/update/(:num)', 'Products::update/$1');

    // Melihat Daftar Kategori untuk Navigasi Katalog
    $routes->get('categories', 'Categories::index');

    // Modul Mutasi Stok (Stock Movement & Audit History)
    $routes->get('stock/history', 'Stock::history');
    $routes->get('stock/in', 'Stock::stockIn');
    $routes->get('stock/out', 'Stock::stockOut');
    $routes->post('stock/movement', 'Stock::createMovement');

    // Modul Pemasok / Supplier (Operasional)
    $routes->get('suppliers', 'Suppliers::index');
    $routes->get('suppliers/new', 'Suppliers::new');
    $routes->post('suppliers', 'Suppliers::create');
    $routes->get('suppliers/edit/(:num)', 'Suppliers::edit/$1');
    $routes->post('suppliers/update/(:num)', 'Suppliers::update/$1');

    // Modul Pelanggan / Departemen Penerima (Operasional)
    $routes->get('customers', 'Customers::index');
    $routes->get('customers/new', 'Customers::new');
    $routes->post('customers', 'Customers::create');
    $routes->get('customers/edit/(:num)', 'Customers::edit/$1');
    $routes->post('customers/update/(:num)', 'Customers::update/$1');

    // Laporan Operasional Harian (Rekap Mutasi & Pengadaan Stok)
    $routes->get('reports/movements', 'Reports::movements');
    $routes->get('reports/restock', 'Reports::restock');
    $routes->get('reports/export/movements', 'Reports::exportMovementsCsv');
    $routes->get('reports/export/restock', 'Reports::exportRestockCsv');
});

// =========================================================================
// RESTFUL API V1 (MOBILE BARCODE SCANNER & INTEGRASI SISTEM)
// =========================================================================
$routes->group('api/v1', ['namespace' => 'App\Controllers\Api\V1'], function ($routes) {
    // Katalog & Lookup Barcode Instan
    $routes->get('products', 'ProductApiController::index');
    $routes->get('products/(:num)', 'ProductApiController::show/$1');
    $routes->get('products/barcode/(:segment)', 'ProductApiController::barcode/$1');

    // Mutasi Stok (Inbound & Outbound Scanning)
    $routes->post('stock/movement', 'StockApiController::createMovement');
});

// Dukungan kompatibilitas backward untuk URL lama (.php)
$routes->get('index.php', static function () {
    return redirect()->to(base_url('products'), 301);
});
$routes->get('login.php', static function () {
    return redirect()->to(base_url('login'), 301);
});
$routes->get('register.php', static function () {
    return redirect()->to(base_url('register'), 301);
});
$routes->get('create.php', static function () {
    return redirect()->to(base_url('products/new'), 301);
});
$routes->get('edit.php', static function () {
    $id = request()->getGet('id');
    return $id ? redirect()->to(base_url('products/edit/' . $id), 301) : redirect()->to(base_url('products'), 301);
});
$routes->post('delete.php', static function () {
    $id = request()->getGet('id') ?? request()->getPost('id');
    return $id ? redirect()->to(base_url('products/delete/' . $id), 307) : redirect()->to(base_url('products'), 301);
});
$routes->get('logout.php', static function () {
    return redirect()->to(base_url('logout'), 301);
});
