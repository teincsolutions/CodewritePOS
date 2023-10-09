<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
use Config\Database;

class CreateStoreClosingTable extends Migration
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
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'opening_balance' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
            ],
            'stock_adjust_balance' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
            ],
            'product_transfer_balance' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
            ],
            'customer_payment' => [
                'type'           => 'DECIMAL',
                'constraint'     => "32,2",
                'unsigned'       => true,
            ],
            'supplier_payment' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'unsigned'     => true,
            ],
            'sale_total' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'unsigned'     => true,
            ],
            'sale_return_total' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'unsigned'     => true,
            ],
            'purchase_return_total' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'unsigned'     => true,
            ],
            'expense_total' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'unsigned'     => true,
            ],
            'cash_in_hand' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'unsigned'     => true,
            ],
            'cashup' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'unsigned'     => true,
            ],
            'closing_balance' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'unsigned'     => true,
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
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ]
        ];

        $forge->addField($fields);
        $forge->addPrimaryKey('id');
        $forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_store_closing_store_id');
        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('store_closings', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('store_closings', true);
    }
}
