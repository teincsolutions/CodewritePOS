<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;
use CodeIgniter\Database\RawSql;

class CreateProductUnitTransfersTable extends Migration
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
            'product_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'from_unit_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'to_unit_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null' => true,
                'unsigned'   => true,
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
        $forge->addForeignKey('from_unit_id', 'units', 'id', 'CASCADE', 'CASCADE', 'fk_product_unit_transfer_from_unit_id');
        $forge->addForeignKey('to_unit_id', 'units', 'id', 'CASCADE', 'CASCADE', 'fk_product_unit_transfer_to_unit_id');
        $forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT', 'fk_product_unit_transfer_user_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('product_unit_transfers', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('product_unit_transfers', true);
    }
}
