<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPerformanceIndexes extends Migration
{
    public function up()
    {
        // 1. Indeks untuk tabel stock_movements untuk mempercepat query laporan berkala & audit trail
        $this->db->query('ALTER TABLE `stock_movements` ADD INDEX `idx_movements_created_tipe` (`created_at`, `tipe`)');
        $this->db->query('ALTER TABLE `stock_movements` ADD INDEX `idx_movements_supplier_id` (`supplier_id`)');
        $this->db->query('ALTER TABLE `stock_movements` ADD INDEX `idx_movements_customer_id` (`customer_id`)');

        // 2. Indeks untuk tabel products untuk mempercepat filter kategori dan status stok
        $this->db->query('ALTER TABLE `products` ADD INDEX `idx_products_kategori` (`kategori`)');
        $this->db->query('ALTER TABLE `products` ADD INDEX `idx_products_stok_min` (`stok`, `stok_minimum`)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `stock_movements` DROP INDEX `idx_movements_created_tipe`');
        $this->db->query('ALTER TABLE `stock_movements` DROP INDEX `idx_movements_supplier_id`');
        $this->db->query('ALTER TABLE `stock_movements` DROP INDEX `idx_movements_customer_id`');

        $this->db->query('ALTER TABLE `products` DROP INDEX `idx_products_kategori`');
        $this->db->query('ALTER TABLE `products` DROP INDEX `idx_products_stok_min`');
    }
}
