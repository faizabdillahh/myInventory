<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'user_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'user_role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'staff',
            ],
            'module' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'record_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
            'details' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'user_agent' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('created_at');
        $this->forge->addKey('module');
        $this->forge->addKey('action');
        $this->forge->addKey('user_id');
        $this->forge->addKey(['module', 'action', 'created_at']);

        // Foreign Key ke users table
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL', 'fk_activity_logs_user');

        $this->forge->createTable('activity_logs', true, [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('activity_logs', true);
    }
}
