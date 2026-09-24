<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUpdatedAtToUsersTable extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('updated_at', 'users')) {
            $this->forge->addColumn('users', [
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'created_at',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('updated_at', 'users')) {
            $this->forge->dropColumn('users', 'updated_at');
        }
    }
}
