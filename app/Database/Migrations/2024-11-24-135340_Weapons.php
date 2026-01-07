<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrationTest extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => 
            [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'image' => 
            [
                'type' => 'JSON',
                'null' => false,
            ],

            'name' => 
            [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],

            'class' => 
            [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],

            'SlashDamage' => 
            [
                'type' => 'FLOAT',
                'null' => false,
                'default' => 0.0,
            ],

            'BluntDamage' => 
            [
                'type' => 'FLOAT',
                'null' => false,
                'default' => 0.0,
            ],

            'description' =>
            [
                'type' => 'VARCHAR',
                'constraint' => 2500,
                'null' => false,
                'default' => '...',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('weapons', true);
    }

    public function down()
    {
        $this->forge->dropTable('weapons', true);
    }
}
