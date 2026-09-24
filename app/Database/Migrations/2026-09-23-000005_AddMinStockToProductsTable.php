<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMinStockToProductsTable extends Migration
{
    public function up()
    {
        $fields = [
            'stok_minimum' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 5,
                'after'      => 'stok',
            ],
        ];

        $this->forge->addColumn('products', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'stok_minimum');
    }
}
