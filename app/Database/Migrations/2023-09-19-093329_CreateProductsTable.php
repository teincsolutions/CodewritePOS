<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;
use CodeIgniter\Database\RawSql;

class CreateProductsTable extends Migration
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
            'barcode' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'unique' => true,
            ],
            'sku' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
                'null' => true,
                'unique' => true,
            ],
            'brand_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'       => true,
                'null' => true,
                'default' => null,
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'       => true,
                'null' => true,
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
            'tax_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null' => true,
            ],
            'discount' => [
                'type' => 'DECIMAL',
                'constraint' => "8,2",
                'default' => 0
            ],
            'pdiscount' => [
                'type' => 'DECIMAL',
                'constraint' => "8,2",
                'default' => 0
            ],
            'min_qty' => [
                'type' => 'DECIMAL',
                'constraint' => "10,2",
                'null' => false,
                'default' => 10
            ],
            'unit_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'       => true,
                'null' => false,
            ],
            'unit_qty' => [
                'type' => 'DECIMAL',
                'constraint' => "6,2",
                'null' => false,
                'default' => 1
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'expiration' => [
                'type'       => 'DATE',
                'null' => true,
            ],
            'image_uri' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'discontinued' => [
                'type' => 'BOOLEAN',
                'default' => 0
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
            ]
        ];

        $forge->addField($fields);
        $forge->addPrimaryKey('id');
        $forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT', 'fk_product_user_id');
        $forge->addForeignKey('brand_id', 'brands', 'id', 'RESTRICT', 'RESTRICT', 'fk_product_brand_id');
        $forge->addForeignKey('category_id', 'categories', 'id', 'RESTRICT', 'RESTRICT', 'fk_product_cateogry_id');
        $forge->addForeignKey('unit_id', 'units', 'id', 'RESTRICT', 'RESTRICT', 'fk_product_unit_id');
        $forge->addForeignKey('tax_id', 'taxes', 'id', 'RESTRICT', 'RESTRICT', 'fk_product_tax_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('products', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('products', true);
    }
}
