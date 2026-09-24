<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'           => 1,
                'nama_lengkap' => 'Administrator Sistem',
                'username'     => 'admin',
                'email'        => 'admin@example.com',
                'password'     => password_hash('admin123', PASSWORD_BCRYPT),
                'role'         => 'admin',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'id'           => 2,
                'nama_lengkap' => 'Staf Operasional Gudang',
                'username'     => 'staff',
                'email'        => 'staff@example.com',
                'password'     => password_hash('staff123', PASSWORD_BCRYPT),
                'role'         => 'staff',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]
        ];

        foreach ($users as $userData) {
            $user = $this->db->table('users')->where('username', $userData['username'])->get()->getRow();
            if (!$user) {
                $this->db->table('users')->insert($userData);
            } else {
                $this->db->table('users')->where('id', $user->id)->update([
                    'role' => $userData['role']
                ]);
            }
        }
    }
}
