<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;
use CodeIgniter\Database\RawSql;

class CreateProductTransfersTable extends Migration
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
            'transfer_date' => [
                'type'       => 'DATE',
                'default' => new RawSql('CURRENT_DATE')
            ],
            'from_store_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'to_store_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null' => true,
                'unsigned'   => true,
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
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ]
        ];

        $forge->addField($fields);
        $forge->addPrimaryKey('id');
        $forge->addForeignKey('from_store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_product_transfer_from_store_id');
        $forge->addForeignKey('to_store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_product_transfer_to_store_id');
        $forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT', 'fk_product_transfer_user_id');
        $forge->addForeignKey('store_closing_id', 'store_closings', 'id', 'RESTRICT', 'RESTRICT', 'fk_product_transfer_store_closing_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('product_transfers', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('product_transfers', true);
    }
}
