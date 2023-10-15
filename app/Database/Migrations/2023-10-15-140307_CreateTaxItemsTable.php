<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class CreateTaxItemsTable extends Migration
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
            'item_label' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'unique'     => true,
            ],
            'tax_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null' => true,
            ],
            'item_rate' => [
                'type'       => 'DECIMAL',
                'constraint' => "32,2",
                'null' => false,
                'default' => 0
            ],
        ];

        $forge->addField($fields);
        $forge->addPrimaryKey('id');
        $forge->addForeignKey('tax_id', 'taxes', 'id', 'RESTRICT', 'RESTRICT', 'fk_tax_items_tax_id');

        $attributes = ['ENGINE' => 'InnoDB'];
        $forge->createTable('tax_items', true, $attributes);
    }

    public function down()
    {
        $forge = Database::forge();
        $forge->dropTable('tax_items', true);
    }
}
