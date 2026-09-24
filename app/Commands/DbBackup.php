<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class DbBackup extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:backup';
    protected $description = 'Mengekspor seluruh skema dan data database ke dalam berkas dump SQL.';

    public function run(array $params)
    {
        $db = Database::connect();
        $dbName = $db->database;

        $backupDir = WRITEPATH . 'backups' . DIRECTORY_SEPARATOR;
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = 'backup_' . $dbName . '_' . date('Ymd_His') . '.sql';
        $filePath = $backupDir . $filename;

        CLI::write("Memulai pencadangan basis data [{$dbName}]...", 'yellow');

        $fp = fopen($filePath, 'w');
        if (!$fp) {
            CLI::error("Gagal membuka berkas tujuan: {$filePath}");
            return;
        }

        // Header SQL
        fwrite($fp, "-- ============================================================\n");
        fwrite($fp, "-- InventarisPro Database Backup\n");
        fwrite($fp, "-- Database: {$dbName}\n");
        fwrite($fp, "-- Tanggal : " . date('Y-m-d H:i:s') . "\n");
        fwrite($fp, "-- ============================================================\n\n");
        fwrite($fp, "SET FOREIGN_KEY_CHECKS=0;\n");
        fwrite($fp, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
        fwrite($fp, "SET time_zone = \"+00:00\";\n\n");

        $tables = $db->listTables();
        CLI::write("Ditemukan " . count($tables) . " tabel untuk diekspor.", 'cyan');

        foreach ($tables as $table) {
            CLI::write("  - Mengekspor tabel: {$table}...", 'light_gray');

            // Drop table
            fwrite($fp, "\n-- ------------------------------------------------------------\n");
            fwrite($fp, "-- Struktur Tabel `{$table}`\n");
            fwrite($fp, "-- ------------------------------------------------------------\n");
            fwrite($fp, "DROP TABLE IF EXISTS `{$table}`;\n");

            // Create table
            $createTableQuery = $db->query("SHOW CREATE TABLE `{$table}`")->getRowArray();
            $createSql = $createTableQuery['Create Table'] ?? '';
            fwrite($fp, $createSql . ";\n\n");

            // Dump data
            $rows = $db->table($table)->get()->getResultArray();
            $rowCount = count($rows);

            if ($rowCount > 0) {
                fwrite($fp, "-- Data untuk Tabel `{$table}` ({$rowCount} baris)\n");
                $chunks = array_chunk($rows, 100);

                foreach ($chunks as $chunk) {
                    $insertValues = [];
                    foreach ($chunk as $row) {
                        $escapedRow = array_map(function ($val) use ($db) {
                            if ($val === null) {
                                return 'NULL';
                            }
                            return $db->escape($val);
                        }, $row);
                        $insertValues[] = '(' . implode(', ', $escapedRow) . ')';
                    }

                    $columns = array_keys($chunk[0]);
                    $quotedCols = array_map(fn($c) => "`{$c}`", $columns);
                    $insertSql = "INSERT INTO `{$table}` (" . implode(', ', $quotedCols) . ") VALUES\n" . implode(",\n", $insertValues) . ";\n";
                    fwrite($fp, $insertSql);
                }
                fwrite($fp, "\n");
            }
        }

        fwrite($fp, "SET FOREIGN_KEY_CHECKS=1;\n");
        fwrite($fp, "\n-- Selesai pencadangan basis data\n");
        fclose($fp);

        $fileSize = round(filesize($filePath) / 1024, 2);
        CLI::write("Pencadangan berhasil disimpan!", 'green');
        CLI::write("Lokasi berkas : {$filePath}", 'green');
        CLI::write("Ukuran berkas : {$fileSize} KB", 'green');
    }
}
