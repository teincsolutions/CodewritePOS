<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;
use CodeIgniter\Database\RawSql;

class CreatePurchasesTable extends Migration
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
            'purchase_date' => [
                'type'       => 'DATE',
                'default' => new RawSql('CURRENT_DATE')
            ],
            'store_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => true,
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => true,
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
        $forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT', 'fk_purchase_user_id');
        $forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_purchase_store_id');
        $forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'CASCADE', 'fk_purchase_supplier_id');
        $forge->addForeignKey('tax_id', 'taxes', 'id', 'RESTRICT', 'RESTRICT', 'fk_purchase_tax_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('purchases', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('purchases', true);
    }
}
