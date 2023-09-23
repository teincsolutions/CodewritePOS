<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class CreateSupplierItemsTable extends Migration
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
            'supplier_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ]
        ];

        $forge->addField($fields);
        $forge->addPrimaryKey(['product_id', 'supplier_id']);
        $forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE', 'fk_stock_product_id');
        $forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'CASCADE', 'fk_stock_supplier_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('stocks', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('stocks', true);
    }
}
