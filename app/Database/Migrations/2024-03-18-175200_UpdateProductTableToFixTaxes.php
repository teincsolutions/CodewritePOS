<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class UpdateProductTableToFixTaxes extends Migration
{
    public function up()
    {
        $db = Database::connect();
        $db->disableForeignKeyChecks();
       
        $forge = Database::forge();
        $fields = [
            'taxes' => [
                'type'       => 'TEXT',
                'null' => true,
            ]
        ];
        $forge->addColumn('products', $fields);
       // $forge->dropColumn('products', ['tax_id']);
       $db->enableForeignKeyChecks();
    }

    public function down()
    {
        $db = Database::connect();
        $db->disableForeignKeyChecks();

        $forge = Database::forge();
        $fields = [
            'tax_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ]
        ];
        $forge->addColumn('products', $fields);
        $forge->dropColumn('products', ['taxes']);
        $db->enableForeignKeyChecks();
    }
}
