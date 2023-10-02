<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;
use CodeIgniter\Database\RawSql;

class CreatePurchaseReturnsTable extends Migration
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
            'purchase_id' => [
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
        $forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT', 'fk_purchase_returns_user_id');
        $forge->addForeignKey('purchase_id', 'purchases', 'id', 'CASCADE', 'CASCADE', 'fk_purchase_returns_purchase_id');
        $forge->addForeignKey('tax_id', 'taxes', 'id', 'RESTRICT', 'RESTRICT', 'fk_purchase_return_tax_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('purchase_returns', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('purchase_returns', true);
    }
}
