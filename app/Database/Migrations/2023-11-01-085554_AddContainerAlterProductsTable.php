<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class AddContainerAlterProductsTable extends Migration
{
    public function up()
    {
        $forge = Database::forge();
        $fields = [
            'has_container' => [
                'type'       => 'BOOLEAN',
                'constraint' => 1,
                'null' => false,
                'default' => 0
            ],
            'container_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => true,
            ],
        ];
        $forge->addColumn('products', $fields);
        $forge->addForeignKey('container_id', 'containers', 'id', 'SET NULL', 'SET NULL', 'fk_products_container_id');
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropColumn('products', ['has_container', 'container_id']);
    }
}
