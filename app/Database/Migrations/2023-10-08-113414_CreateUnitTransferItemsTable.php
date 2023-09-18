<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class CreateUnitTransferItemsTable extends Migration
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
            'from_unit_qty' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
                'null' => true,
            ],
            'to_unit_qty' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
                'null' => true,
            ],
            'from_product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
                'null' => false,
            ],
            'to_product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
                'null' => false,
            ],
            'unit_transfer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
                'null' => false,
            ],
        ];

        $forge->addField($fields);
        $forge->addPrimaryKey('id');
        $forge->addForeignKey('unit_transfer_id', 'product_unit_transfers', 'id', 'CASCADE', 'CASCADE', 'fk_unit_transfer_items_unit_transfer_id');
        $forge->addForeignKey('from_product_id', 'products', 'id', 'RESTRICT', 'RESTRICT', 'fk_unit_transfer_items_from_product_id');
        $forge->addForeignKey('to_product_id', 'products', 'id', 'RESTRICT', 'RESTRICT', 'fk_unit_transfer_items_to_product_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('unit_transfer_items', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('unit_transfer_items', true);
    }
}
