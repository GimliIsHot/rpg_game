<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Abilities extends Migration
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

            'SlashDamage' => 
            [
                'type' => 'FLOAT',
                'default' => 0.0,
                'null' => false,
            ],

            'BluntDamage' => 
            [
                'type' => 'FLOAT',
                'default' => 0.0,
                'null' => false,
            ],

            'MagicalDamage' => 
            [
                'type' => 'FLOAT',
                'default' => 0.0,
                'null' => false,
            ],

            'description' =>
            [
                'type' => 'VARCHAR',
                'constraint' => 2500,
            ],
        ]);
        $this->forge->addKey('id', true); 
        $this->forge->createTable('Abilities');  
    }

    public function down()
    {
        $this->forge->dropTable('Abilities');
    }
}
