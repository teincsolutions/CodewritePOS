<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
use Config\Database;

class CreateStoreProductsTable extends Migration
{
    public function up()
    {
        $forge = Database::forge();

        $fields = [
            'product_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'store_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'unit_cost' => [
                'type' => 'DECIMAL',
                'constraint' => "32,2",
                'null' => true,
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => "32,2",
                'null' => false,
            ],
            'unit_ws_price' => [
                'type' => 'DECIMAL',
                'constraint' => "32,2",
                'null' => true,
            ],
            'discount' => [
                'type' => 'DECIMAL',
                'constraint' => "8,2",
                'default' => 0
            ],
            'discontinued' => [
                'type' => 'BOOLEAN',
                'default' => 0
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ];

        $forge->addField($fields);
        $forge->addPrimaryKey(['product_id', 'store_id']);
        $forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE', 'fk_store_product_product_id');
        $forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_store_product_store_id');
        
        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('store_products', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('store_products', true);
    }
}
