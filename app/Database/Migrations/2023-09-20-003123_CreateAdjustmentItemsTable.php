<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;
use CodeIgniter\Database\RawSql;

class CreateAdjustmentItemsTable extends Migration
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
            'adjustment_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
                'null' => true,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
            ],
            'unit_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'null' => false,
                'default' => 0
            ],
            'instock_qty' => [
                'type'       => 'DECIMAL',
                'constraint' => "10,2",
                'null' => false,
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
        $forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_adjustment_items_store_id');
        $forge->addForeignKey('adjustment_id', 'stock_adjustments', 'id', 'CASCADE', 'CASCADE', 'fk_adjustment_items_adjustment_id');
        $forge->addForeignKey('product_id', 'products', 'id', 'RESTRICT', 'RESTRICT', 'fk_adjustment_items_product_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('adjustments_items', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('adjustments_items', true);
    }
}
