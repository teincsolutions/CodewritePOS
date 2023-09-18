<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
use Config\Database;

class CreateUserStoresTable extends Migration
{
    public function up()
    {
        $forge = Database::forge();

        $fields = [
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'store_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
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
        $forge->addPrimaryKey(['user_id', 'store_id']);
        $forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE', 'fk_user_store_user_id');
        $forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE', 'fk_user_store_store_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('user_stores', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('user_stores', true);
    }
}
