<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Armor extends Migration
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
                'null' => false,
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
            ],

            'class' => 
            [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],

            'SlashProtection' => 
            [
                'type' => 'FLOAT',
                'null' => false,
                'default' => 0.0,
            ],

            'BluntProtection' => 
            [
                'type' => 'FLOAT',
                'null' => false,
                'default' => 0.0,
            ],

            'description' => 
            [
                'type' => 'VARCHAR',
                'constraint' => 2500,
            ],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('Armor');
    }

    public function down()
    {
        $this->forge->dropTable('Armor');
    }
}
