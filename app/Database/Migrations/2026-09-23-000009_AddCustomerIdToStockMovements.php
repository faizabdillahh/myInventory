<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomerIdToStockMovements extends Migration
{
    public function up()
    {
        $fields = [
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => false,
                'null'       => true,
                'after'      => 'supplier_id',
            ],
        ];

        $this->forge->addColumn('stock_movements', $fields);

        // Tambahkan Foreign Key constraint
        $this->db->query('
            ALTER TABLE `stock_movements`
            ADD CONSTRAINT `fk_stock_movements_customer`
            FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
            ON DELETE SET NULL ON UPDATE CASCADE
        ');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `stock_movements` DROP FOREIGN KEY `fk_stock_movements_customer`');
        $this->forge->dropColumn('stock_movements', 'customer_id');
    }
}
