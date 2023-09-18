<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class CreateContainerReceivingItemsTable extends Migration
{
   
    public function up()
    {
        $forge = Database::forge();
        $fields = [
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 18,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'store_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
                'null' => true,
            ],
            'container_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
            ],
            'container_receiving_id' => [
                'type'       => 'BIGINT',
                'constraint' => 18,
                'unsigned'  => true,
                'null' => true,
            ],
            'unit_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'null' => false,
                'default' => 0
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'null' => false,
                'default' => 0
            ],
            'qty' => [
                'type'       => 'DECIMAL',
                'constraint' => "10,2",
                'null' => false,
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
        $forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_container_receivings_items_store_id');
        $forge->addForeignKey('container_receiving_id', 'container_receivings', 'id', 'CASCADE', 'CASCADE', 'fk_container_receivings_items_container_receiving_id');
        $forge->addForeignKey('container_id', 'containers', 'id', 'RESTRICT', 'RESTRICT', 'fk_container_receivings_items_container_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('container_receivings_items', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('container_receivings_items', true);
    }
}
