<?php

namespace App\Services;

use App\DTOs\ReportFilterDTO;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Repositories\ReportRepository;

/**
 * Service Layer untuk Analisis Laporan & Valuasi Inventaris
 */
class ReportService extends BaseService
{
    protected ReportRepositoryInterface $reportRepo;

    public function __construct(?ReportRepositoryInterface $reportRepo = null)
    {
        parent::__construct();
        $this->reportRepo = $reportRepo ?? new ReportRepository();
    }

    /**
     * Mengambil kumpulan data komprehensif untuk Dashboard Valuasi Aset
     */
    public function getValuationDashboardData(?string $kategori = null, ?int $supplierId = null): array
    {
        $summary    = $this->reportRepo->getValuationSummary();
        $byCategory = $this->reportRepo->getValuationByCategory();
        $bySupplier = $this->reportRepo->getValuationBySupplier();
        $details    = $this->reportRepo->getDetailedValuation($kategori, $supplierId);

        $totalValuasi = $summary['total_valuasi'] > 0 ? $summary['total_valuasi'] : 1;

        // Hitung persentase kontribusi aset per kategori
        foreach ($byCategory as &$cat) {
            $cat['persentase'] = round(($cat['total_valuasi'] / $totalValuasi) * 100, 1);
        }
        unset($cat);

        // Hitung persentase kontribusi aset per supplier
        foreach ($bySupplier as &$sup) {
            $sup['persentase'] = round(($sup['total_valuasi'] / $totalValuasi) * 100, 1);
        }
        unset($sup);

        return [
            'summary'     => $summary,
            'by_category' => $byCategory,
            'by_supplier' => $bySupplier,
            'details'     => $details,
        ];
    }

    /**
     * Mengambil data mutasi stok dalam rentang tanggal dan kriteria filter
     */
    public function getPeriodicMovementData(ReportFilterDTO $filter): array
    {
        return [
            'stats'     => $this->reportRepo->getPeriodicMovementStats($filter),
            'movements' => $this->reportRepo->getPeriodicMovements($filter),
        ];
    }

    /**
     * Mengambil data estimasi kebutuhan pengadaan restock
     */
    public function getRestockProcurementData(?int $supplierId = null): array
    {
        $items = $this->reportRepo->getRestockProcurementReport($supplierId);

        $totalSku          = count($items);
        $totalUnitKebutuhan = 0;
        $totalEstimasiBiaya = 0.0;

        foreach ($items as $item) {
            $totalUnitKebutuhan += (int)$item['saran_reorder'];
            $totalEstimasiBiaya += (float)$item['estimasi_biaya'];
        }

        return [
            'items'                 => $items,
            'total_sku'             => $totalSku,
            'total_unit_kebutuhan'  => $totalUnitKebutuhan,
            'total_estimasi_biaya'  => $totalEstimasiBiaya,
        ];
    }

    /**
     * Menghasilkan file CSV Laporan Valuasi Aset
     */
    public function generateValuationCsv(?string $kategori = null, ?int $supplierId = null): string
    {
        $data = $this->reportRepo->getDetailedValuation($kategori, $supplierId);

        $output = fopen('php://temp', 'r+');
        // Tambahkan UTF-8 BOM agar Excel dapat membaca karakter dengan benar
        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, [
            'No',
            'Kode SKU',
            'Nama Produk',
            'Kategori',
            'Pemasok Utama',
            'Stok Fisik',
            'Stok Minimum',
            'Harga Satuan (Rp)',
            'Total Nilai Aset (Rp)',
            'Status Inventaris'
        ]);

        $no = 1;
        foreach ($data as $row) {
            $stok = (int)$row['stok'];
            $min  = (int)($row['stok_minimum'] ?? 5);

            $status = 'Tersedia';
            if ($stok <= 0) {
                $status = 'Stok Habis';
            } elseif ($stok <= $min) {
                $status = 'Menipis (Perlu Restock)';
            }

            fputcsv($output, [
                $no++,
                $row['kode_produk'],
                $row['nama_produk'],
                $row['kategori'],
                $row['nama_supplier'] ?? '-',
                $stok,
                $min,
                number_format((float)$row['harga'], 2, ',', '.'),
                number_format((float)$row['nilai_aset'], 2, ',', '.'),
                $status,
            ]);
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }

    /**
     * Menghasilkan file CSV Rekapitulasi Mutasi Stok
     */
    public function generateMovementCsv(ReportFilterDTO $filter): string
    {
        $movements = $this->reportRepo->getPeriodicMovements($filter);

        $output = fopen('php://temp', 'r+');
        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, [
            'No',
            'Waktu Transaksi',
            'Kode SKU',
            'Nama Produk',
            'Kategori',
            'Jenis Mutasi',
            'Jumlah',
            'Stok Sebelum',
            'Stok Sesudah',
            'Pemasok Asal',
            'Operator',
            'Keterangan / Catatan'
        ]);

        $no = 1;
        foreach ($movements as $m) {
            fputcsv($output, [
                $no++,
                date('Y-m-d H:i:s', strtotime($m['created_at'])),
                $m['kode_produk'],
                $m['nama_produk'],
                $m['kategori'] ?? '-',
                $m['tipe'],
                ($m['tipe'] === 'IN' ? '+' : ($m['tipe'] === 'OUT' ? '-' : '')) . (int)$m['jumlah'],
                (int)$m['stok_sebelum'],
                (int)$m['stok_sesudah'],
                $m['nama_supplier'] ?? '-',
                $m['operator'] ?? 'Sistem',
                $m['keterangan'] ?? '-',
            ]);
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }

    /**
     * Menghasilkan file CSV Rekapitulasi Kebutuhan Restock Pengadaan
     */
    public function generateRestockCsv(?int $supplierId = null): string
    {
        $report = $this->getRestockProcurementData($supplierId);

        $output = fopen('php://temp', 'r+');
        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, [
            'No',
            'Kode SKU',
            'Nama Produk',
            'Kategori',
            'Pemasok Utama',
            'Kontak Vendor',
            'Stok Fisik',
            'Batas Minimum',
            'Saran Kuantitas Reorder (Unit)',
            'Harga Satuan (Rp)',
            'Estimasi Anggaran Belanja (Rp)'
        ]);

        $no = 1;
        foreach ($report['items'] as $item) {
            $kontak = [];
            if (!empty($item['telepon'])) $kontak[] = $item['telepon'];
            if (!empty($item['email'])) $kontak[] = $item['email'];

            fputcsv($output, [
                $no++,
                $item['kode_produk'],
                $item['nama_produk'],
                $item['kategori'],
                $item['nama_supplier'] ?? 'Belum Ditentukan',
                !empty($kontak) ? implode(' / ', $kontak) : '-',
                (int)$item['stok'],
                (int)$item['stok_minimum'],
                (int)$item['saran_reorder'],
                number_format((float)$item['harga'], 2, ',', '.'),
                number_format((float)$item['estimasi_biaya'], 2, ',', '.'),
            ]);
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }
}
