<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class AlterUsersTable extends Migration
{
    public function up()
    {
        $forge = Database::forge();
        $fields = [
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => '16',
                'null' => true,
            ],
            'firstname' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'lastname' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ],
            'address' => [
                'type'      => 'TEXT',
                'null' => true,
            ],
            'photo_uri' => [
                'type'      => 'TEXT',
                'null' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => true,
            ]
        ];
        $forge->addColumn('users', $fields);
        $forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT', 'fk_user_user_id');
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropColumn('users', ['email', 'phone', 'firstname', 'lastname', 'address', 'user_id']);
    }
}
