<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;
use CodeIgniter\Database\RawSql;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        $forge = Database::forge();
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null' => false,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'address' => [
                'type'      => 'TEXT',
                'null' => true,
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'image_uri' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['opened', 'closed'],
                'default'    => 'opened',
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['wholeseller', 'retailer'],
                'default'    => 'retailer',
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ]
        ];

        $forge->addField($fields);
        $forge->addPrimaryKey('id');
        $forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT', 'fk_customer_user_id');
       
        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('customers', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('customers', true);
    }
}
