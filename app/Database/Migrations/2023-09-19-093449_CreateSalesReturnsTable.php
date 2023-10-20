<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;
use CodeIgniter\Database\RawSql;

class CreateSalesReturnsTable extends Migration
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
            'invoice' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'unique' => true,
            ],
            'return_date' => [
                'type'       => 'DATE',
                'default' => new RawSql('CURRENT_DATE')
            ],
            'sale_id' => [
                'type'       => 'BIGINT',
                'constraint' => 18,
                'unsigned'   => true,
                'null' => true,
            ],
            'tax_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null' => true,
            ],
            'tax' => [
                'type' => 'DECIMAL',
                'constraint' => "10,2",
                'default' => 0,
            ],
            'discount' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'null' => false,
                'defaut' => 0.00
            ],
            'shipping' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'null' => false,
                'defaut' => 0.00
            ],
            'order_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'completed'],
                'default' => 'pending'
            ],
            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['due', 'paid'],
                'default' => 'due'
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'null' => false,
                'defaut' => 0.00
            ],
            'payment_type' => [
                'type' => 'ENUM',
                'constraint' => ['cash', 'debit','momo'],
                'default' => 'cash'
            ],
            'paid' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'null' => false,
                'defaut' => 0.00
            ],
            'store_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => true,
            ], 
            'store_closing_id' => [
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
        $forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT', 'fk_sales_returns_user_id');
        $forge->addForeignKey('sale_id', 'sales', 'id', 'CASCADE', 'CASCADE', 'fk_sales_returns_sale_id');
        $forge->addForeignKey('store_closing_id', 'store_closings', 'id', 'RESTRICT', 'RESTRICT', 'fk_sales_returns_store_closing_id');
        $forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_sales_returns_store_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('sales_returns', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('sales_returns', true);
    }
}
