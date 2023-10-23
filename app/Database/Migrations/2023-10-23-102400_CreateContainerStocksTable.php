<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
use Config\Database;

class CreateContainerStocksTable extends Migration
{
    public function up()
    {
        $forge = Database::forge();

        $fields = [
            'container_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'store_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'instock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'     => false,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ];

        $forge->addField($fields);
        $forge->addPrimaryKey(['container_id', 'store_id']);
        $forge->addForeignKey('container_id', 'containers', 'id', 'CASCADE', 'CASCADE', 'fk_container_stock_container_id');
        $forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_container_stock_store_id');
        
        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('container_stocks', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('container_stocks', true);
    }
}
