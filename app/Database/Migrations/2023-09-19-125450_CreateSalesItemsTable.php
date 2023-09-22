<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;
use CodeIgniter\Database\RawSql;

class CreateSalesItemsTable extends Migration
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
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'  => true,
            ],
            'sale_id' => [
                'type'       => 'BIGINT',
                'constraint' => 18,
                'unsigned'  => true,
                'null' => true,
            ],
            'qty' => [
                'type'       => 'DECIMAL',
                'constraint' => "10,2",
                'null' => false,
            ],
        ];

        $forge->addField($fields);
        $forge->addPrimaryKey('id');
        $forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_sales_items_store_id');
        $forge->addForeignKey('sale_id', 'sales', 'id', 'CASCADE', 'CASCADE', 'fk_sales_items_sale_id');
        $forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE', 'fk_sales_items_product_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('sales_items', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('sales_items', true);
    }
}
