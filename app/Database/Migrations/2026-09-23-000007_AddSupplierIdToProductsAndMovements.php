<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSupplierIdToProductsAndMovements extends Migration
{
    public function up()
    {
        // 1. Tambah kolom supplier_id ke tabel products
        $this->forge->addColumn('products', [
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'user_id',
            ],
        ]);

        // 2. Tambah kolom supplier_id ke tabel stock_movements
        $this->forge->addColumn('stock_movements', [
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'user_id',
            ],
        ]);

        // 3. Tambahkan foreign key constraints
        $this->db->query('ALTER TABLE `products` ADD CONSTRAINT `fk_products_supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE `stock_movements` ADD CONSTRAINT `fk_stock_movements_supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `products` DROP FOREIGN KEY `fk_products_supplier_id`');
        $this->db->query('ALTER TABLE `stock_movements` DROP FOREIGN KEY `fk_stock_movements_supplier_id`');
        $this->forge->dropColumn('products', 'supplier_id');
        $this->forge->dropColumn('stock_movements', 'supplier_id');
    }
}
