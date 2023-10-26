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
            'tdate' => [
                'type'       => 'DATE',
                'default' => new RawSql('CURRENT_DATE')
            ],
            'store_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
                'null' => true,
            ],
            'sale_id' => [
                'type'       => 'BIGINT',
                'constraint' => 18,
                'unsigned'  => true,
                'null' => false,
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'null' => false,
                'default' => 0
            ],
            'container_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
            ],
            'qty_in' => [
                'type'       => 'DECIMAL',
                'constraint' => "10,2",
                'null' => true,
            ],
            'qty_out' => [
                'type'       => 'DECIMAL',
                'constraint' => "10,2",
                'null' => true,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'null' => false,
                'default' => 0
            ],
        ];

        $forge->addField($fields);
        $forge->addPrimaryKey('id');
        $forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_customer_container_store_id');
        $forge->addForeignKey('sale_id', 'sales', 'id', 'CASCADE', 'CASCADE', 'fk_customer_containers_sale_id');
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
