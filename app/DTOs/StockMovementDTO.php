<?php

namespace App\DTOs;

/**
 * Data Transfer Object (DTO) untuk Transaksi Mutasi Stok
 * Mengenkapsulasi input mutasi stok dengan validasi tipe data yang aman.
 */
class StockMovementDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly int $userId,
        public readonly string $tipe, // 'IN', 'OUT', 'ADJUSTMENT'
        public readonly int $jumlah,
        public readonly ?int $supplierId = null,
        public readonly ?int $customerId = null,
        public readonly ?string $keterangan = null
    ) {
        $normalizedTipe = strtoupper($this->tipe);
        if (!in_array($normalizedTipe, ['IN', 'OUT', 'ADJUSTMENT'], true)) {
            throw new \InvalidArgumentException("Tipe mutasi tidak valid. Harus IN, OUT, atau ADJUSTMENT.");
        }

        if ($normalizedTipe === 'ADJUSTMENT') {
            if ($this->jumlah < 0) {
                throw new \InvalidArgumentException("Hasil penyesuaian stok fisik (opname) tidak boleh bernilai negatif.");
            }
        } else {
            if ($this->jumlah <= 0) {
                throw new \InvalidArgumentException("Jumlah unit mutasi harus bernilai lebih dari 0.");
            }
        }
    }

    /**
     * Membentuk instance DTO dari data array input form / API
     */
    public static function fromArray(array $data, int $userId): self
    {
        $supplierId = !empty($data['supplier_id']) ? (int)$data['supplier_id'] : null;
        $customerId = !empty($data['customer_id']) ? (int)$data['customer_id'] : null;

        return new self(
            productId: (int)($data['product_id'] ?? 0),
            userId: $userId,
            tipe: strtoupper(trim($data['tipe'] ?? 'IN')),
            jumlah: (int)($data['jumlah'] ?? 0),
            supplierId: $supplierId,
            customerId: $customerId,
            keterangan: !empty($data['keterangan']) ? trim($data['keterangan']) : null
        );
    }
}
