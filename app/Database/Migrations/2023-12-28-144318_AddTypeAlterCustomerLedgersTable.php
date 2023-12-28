<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class AddTypeAlterCustomerLedgersTable extends Migration
{
    public function up()
    {
        $forge = Database::forge();
        $fields = [
            'ledger_type' => [
                'type'           => 'DECIMAL',
                'constraint'     => "32,2",
                'unsigned'       => true,
                'default' => 0
            ]
        ];
        $forge->addColumn('customers', $fields);
    }

    public function down()
    {
        $forge = Database::forge();
    }
}
