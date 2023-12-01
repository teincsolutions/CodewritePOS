<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class AddCustomerLimitsAlterCustomersTable extends Migration
{
    public function up()
    {
        $forge = Database::forge();
        $fields = [
            'credit_limit' => [
                'type'           => 'DECIMAL',
                'constraint'     => "32,2",
                'unsigned'       => true,
                'default' => 0
            ],
            'credit_limit_days' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 14,
                'unsigned'       => true,
            ]
        ];
        $forge->addColumn('customers', $fields);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropColumn('customers', ['credit_limit', 'credit_limit_days']);
    }
}
