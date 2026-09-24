# AI Rule: Clean Code & SOLID Standards

> Aturan ini wajib diterapkan oleh Agen AI saat menghasilkan atau merefaktor kode di repositori ini.

---

## 1. Naming Conventions (Aturan Penamaan)
- **Kelas & Interface**: `PascalCase` (contoh: `StockMovementService`, `ProductRepositoryInterface`).
- **Method & Fungsi**: `camelCase` (contoh: `getProductsWithUser()`, `calculateTotalAsset()`).
- **Variabel & Properti**: `camelCase` (contoh: `$totalProducts`, `$searchKeyword`).
- **Tabel Database**: `snake_case` jamak (contoh: `products`, `users`, `stock_movements`).
- **Kolom Database**: `snake_case` tunggal (contoh: `kode_produk`, `created_at`).
- **File View**: `snake_case` (contoh: `stock_movement.php`, `index.php`).

---

## 2. Struktur Fungsi & Kompleksitas
1. **Panjang Maksimal**: Jaga method/fungsi di bawah **35 baris**. Pecah logika kompleks menjadi *private helper functions*.
2. **Early Returns / Guard Clauses**:
   Gunakan pengembalian cepat untuk menangani kondisi error/validasi di baris teratas, hindari *nested if-else* lebih dari 2 tingkat.
3. **Type Safety**:
   Wajib mendeklarasikan tipe data parameter dan return type:
   ```php
   public function updateStock(int $productId, int $amount, string $type): bool
   ```
4. **Larangan Magic Numbers & Magic Strings**:
   Gunakan konstanta atau string yang jelas maknanya:
   ```php
   // ❌ const LOW_STOCK_THRESHOLD = 5;
   // Bukan if ($item['stok'] <= 5) langsung tanpa konteks
   ```

---

## 3. Komentar & Dokumentasi
1. Tulis DocBlock standar pada setiap class dan method publik (`@param`, `@return`, `@throws`).
2. Jangan menulis komentar yang hanya mengulang baris kode (misal: `// menambah stok $stok++`). Jelaskan **mengapa** (*why*), bukan *apa* (*what*).
