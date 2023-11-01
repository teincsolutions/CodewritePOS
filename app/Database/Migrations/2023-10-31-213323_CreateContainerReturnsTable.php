<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
use Config\Database;

class CreateContainerReturnsTable extends Migration
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
            'return_date' => [
                'type'       => 'DATE',
                'default' => new RawSql('CURRENT_DATE')
            ],
            'customer_id' => [
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
        $forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT', 'fk_container_returns_user_id');
        $forge->addForeignKey('customer_id', 'customers', 'id', 'CASCADE', 'CASCADE', 'fk_container_returns_customer_id');
        $forge->addForeignKey('store_closing_id', 'store_closings', 'id', 'RESTRICT', 'RESTRICT', 'fk_container_return_store_closing_id');
        $forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_container_returns_store_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('container_returns', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('container_returns', true);
    }
}
