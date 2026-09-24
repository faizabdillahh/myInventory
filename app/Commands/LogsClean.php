<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class LogsClean extends BaseCommand
{
    protected $group       = 'Maintenance';
    protected $name        = 'logs:clean';
    protected $description = 'Membersihkan berkas log sistem yang lebih tua dari batas hari tertentu.';
    protected $usage       = 'logs:clean [options]';
    protected $options     = [
        '--days' => 'Batas usia berkas log dalam hari (default: 30 hari).',
    ];

    public function run(array $params)
    {
        $days = (int) ($params['days'] ?? CLI::getOption('days') ?? 30);
        if ($days < 1) {
            CLI::error('Nilai parameter --days harus lebih besar dari 0.');
            return;
        }

        $logPath = WRITEPATH . 'logs' . DIRECTORY_SEPARATOR;
        if (!is_dir($logPath)) {
            CLI::error("Direktori log tidak ditemukan: {$logPath}");
            return;
        }

        CLI::write("Memindai berkas log yang lebih tua dari {$days} hari...", 'yellow');

        $cutoffTime   = time() - ($days * 86400);
        $deletedCount = 0;
        $freedBytes   = 0;

        $files = scandir($logPath);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || $file === 'index.html' || $file === '.gitkeep') {
                continue;
            }

            $filePath = $logPath . $file;
            if (is_file($filePath)) {
                $fileMtime = filemtime($filePath);
                if ($fileMtime < $cutoffTime) {
                    $fileSize = filesize($filePath);
                    if (@unlink($filePath)) {
                        $deletedCount++;
                        $freedBytes += $fileSize;
                        CLI::write("  - Dihapus: {$file} (" . round($fileSize / 1024, 2) . " KB)", 'light_gray');
                    }
                }
            }
        }

        $freedKb = round($freedBytes / 1024, 2);
        if ($deletedCount > 0) {
            CLI::write("Selesai! Berhasil menghapus {$deletedCount} berkas log (Ruang disk terbebas: {$freedKb} KB).", 'green');
        } else {
            CLI::write("Tidak ada berkas log yang lebih tua dari {$days} hari.", 'green');
        }
    }
}
