<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class AddSubCategoryToExpensesTable extends Migration
{
    public function up()
    {
        $forge = Database::forge();
        $fields = [
            'expense_subcategory_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ]
        ];
        $forge->addColumn('expenses', $fields);
        $forge->addForeignKey('expense_subcategory_id', 'expense_subcategories', 'id',  'CASCADE', 'CASCADE', 'fk_expense_expense_subcategory_id');

    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropColumn('expenses', ['expense_subcategory_id']);
    }
}