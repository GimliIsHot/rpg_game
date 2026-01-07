<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NPCS extends Migration
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

            'name' => 
            [
                'type'=> 'VARCHAR',
                'constraint' => 255,
            ],

            'description' => 
            [
                'type'=> 'VARCHAR',
                'constraint' => 2500,
            ],

            'image' => 
            [
                'type' => 'JSON',
                'null' => false,
            ],

            'armor_pieces' => 
            [
                'type' => 'JSON',
                'null' => true,
            ],

            'weapons' => 
            [
                'type' => 'JSON',
                'null' => true,
            ],

            'abilities' => 
            [
                'type' => 'JSON',
                'null' => true,
            ],

            'health' => 
            [
                'type' => 'FLOAT',
                'null' => false,
                'default' => 0.0,
            ],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('NPCS');
    }

    public function down()
    {
        $this->forge->dropTable('NPCS');
    }
}
