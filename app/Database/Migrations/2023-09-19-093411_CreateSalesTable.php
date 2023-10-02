<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;
use CodeIgniter\Database\RawSql;

class CreateSalesTable extends Migration
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
            'invoice' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'unique' => true,
            ],
            'sales_date' => [
                'type'       => 'DATE',
                'default' => new RawSql('CURRENT_DATE')
            ],
            'store_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => true,
            ],
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => true,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['walk-in', 'customer'],
                'default' => 'walk-in'
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
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
            'payment_type' => [
                'type' => 'ENUM',
                'constraint' => ['cash', 'debit','momo'],
                'default' => 'cash'
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'null' => false,
                'defaut' => 0.00
            ],
            'paid' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'null' => false,
                'defaut' => 0.00
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

        $forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT', 'fk_sales_user_id');
        $forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_sales_store_id');
        $forge->addForeignKey('customer_id', 'customers', 'id', 'CASCADE', 'CASCADE', 'fk_sales_customer_id');
        $forge->addForeignKey('tax_id', 'taxes', 'id', 'RESTRICT', 'RESTRICT', 'fk_sales_tax_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('sales', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('sales', true);
    }
}
