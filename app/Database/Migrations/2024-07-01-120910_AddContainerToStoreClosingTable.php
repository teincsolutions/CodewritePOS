<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class AddContainerToStoreClosingTable extends Migration
{
    public function up()
    {
        $forge = Database::forge();
        $fields = [
            'container_sale_total' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'after' => 'sale_total'
            ]
        ];
        $forge->addColumn('store_closings', $fields);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropColumn('store_closings', ['container_sale_total']);
    }
}