<?php

/**
 * ==============================================================================
 * FILE: app/Helpers/format_helper.php
 * DESKRIPSI: Helper Sentralisasi Pemformatan Mata Uang, Angka, Tanggal, & Persentase
 * ==============================================================================
 */

if (!function_exists('format_rupiah')) {
    /**
     * Memformat angka nominal menjadi mata uang Rupiah Indonesia (Rp)
     *
     * @param float|int|string|null $nominal Angka yang akan diformat
     * @param bool $withSymbol Apakah menyertakan prefix 'Rp ' (default: true)
     * @return string Contoh: 'Rp 250.000' atau '250.000'
     */
    function format_rupiah(float|int|string|null $nominal, bool $withSymbol = true): string
    {
        $val = (float)($nominal ?? 0);
        $formatted = number_format($val, 0, ',', '.');
        return $withSymbol ? 'Rp ' . $formatted : $formatted;
    }
}

if (!function_exists('format_angka')) {
    /**
     * Memformat angka kuantitas unit atau metrik dengan pemisah ribuan titik
     *
     * @param float|int|string|null $angka
     * @param int $decimals Jumlah angka di belakang koma (default: 0)
     * @return string Contoh: '1.500' atau '12,5'
     */
    function format_angka(float|int|string|null $angka, int $decimals = 0): string
    {
        $val = (float)($angka ?? 0);
        return number_format($val, $decimals, ',', '.');
    }
}

if (!function_exists('format_tanggal_indonesia')) {
    /**
     * Memformat tanggal/waktu ke dalam bahasa Indonesia formal
     *
     * @param string|int|null $datetime String tanggal atau Unix timestamp
     * @param bool $withTime Apakah menyertakan jam:menit WIB (default: false)
     * @return string Contoh: '23 September 2026' atau '23 September 2026, 14:30 WIB'
     */
    function format_tanggal_indonesia(string|int|null $datetime, bool $withTime = false): string
    {
        if (empty($datetime)) {
            return '-';
        }

        $timestamp = is_numeric($datetime) ? (int)$datetime : strtotime($datetime);
        if (!$timestamp) {
            return (string)$datetime;
        }

        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $hari   = date('j', $timestamp);
        $bulan  = $bulanIndo[(int)date('n', $timestamp)] ?? date('F', $timestamp);
        $tahun  = date('Y', $timestamp);

        $result = "{$hari} {$bulan} {$tahun}";

        if ($withTime) {
            $jam = date('H:i', $timestamp);
            $result .= ", {$jam} WIB";
        }

        return $result;
    }
}

if (!function_exists('format_tanggal_singkat')) {
    /**
     * Memformat tanggal ke dalam bentuk ringkas 3 huruf bulan (cocok untuk tabel padat)
     *
     * @param string|int|null $datetime
     * @return string Contoh: '23 Sep 2026'
     */
    function format_tanggal_singkat(string|int|null $datetime): string
    {
        if (empty($datetime)) {
            return '-';
        }

        $timestamp = is_numeric($datetime) ? (int)$datetime : strtotime($datetime);
        if (!$timestamp) {
            return (string)$datetime;
        }

        $bulanSingkat = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $hari  = date('j', $timestamp);
        $bulan = $bulanSingkat[(int)date('n', $timestamp)] ?? date('M', $timestamp);
        $tahun = date('Y', $timestamp);

        return "{$hari} {$bulan} {$tahun}";
    }
}

if (!function_exists('format_persen')) {
    /**
     * Memformat angka persentase dengan simbol %
     *
     * @param float|int|string|null $value
     * @param int $decimals Jumlah angka desimal (default: 1)
     * @return string Contoh: '25,5%'
     */
    function format_persen(float|int|string|null $value, int $decimals = 1): string
    {
        $val = (float)($value ?? 0);
        return number_format($val, $decimals, ',', '.') . '%';
    }
}
