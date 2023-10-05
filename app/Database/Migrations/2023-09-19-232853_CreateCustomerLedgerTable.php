<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;
use CodeIgniter\Database\RawSql;

class CreateCustomerLedgerTable extends Migration
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
            'tdate' => [
                'type'       => 'DATE',
                'default' => new RawSql('CURRENT_DATE')
            ],
            'customer_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'sale_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 18,
                'unsigned'       => true,
                'null' => true,
            ],
            'payment_type' => [
                'type' => 'ENUM',
                'constraint' => ['cash', 'debit','momo'],
                'default' => 'cash'
            ],
            'ledger_type' => [
                'type' => 'ENUM',
                'constraint' => ['sales', 'returns'],
                'default' => 'sales'
            ],
            'debit' => [
                'type'           => 'DECIMAL',
                'constraint'     => "32,2",
                'unsigned'       => true,
            ],
            'credit' => [
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
        $forge->addForeignKey('customer_id', 'customers', 'id', 'CASCADE', 'CASCADE', 'fk_customer_ledger_customer_id');
        $forge->addForeignKey('sale_id', 'sales', 'id', 'CASCADE', 'CASCADE', 'fk_customer_ledger_sale_id');
        $forge->addForeignKey('sales_return_id', 'sales_returns', 'id', 'CASCADE', 'CASCADE', 'fk_customer_ledger_sales_return_id');
        $forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT', 'fk_customer_ledger_user_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('customer_ledgers', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('customer_ledgers', true);
    }
}
