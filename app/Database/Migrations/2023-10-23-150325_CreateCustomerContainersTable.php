<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
use Config\Database;

class CreateCustomerContainersTable extends Migration
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
            'store_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
                'null' => true,
            ],
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
                'null' => false,
            ],
            'container_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
            ],
            'instock' => [
                'type'       => 'DECIMAL',
                'constraint' => "10,2",
                'null' => true,
            ],
        ];

        $forge->addField($fields);
        $forge->addPrimaryKey('id');
        $forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_customer_container_store_id');
        $forge->addForeignKey('customer_id', 'customers', 'id', 'CASCADE', 'CASCADE', 'fk_customer_containers_customer_id');
        $forge->addForeignKey('container_id', 'containers', 'id', 'RESTRICT', 'RESTRICT', 'fk_customer_container_container_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('customer_containers', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('customer_containers', true);
    }
}
