<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHomeScreensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'comment'    => 'Display name e.g. Wedding, Christmas',
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'comment'    => 'Used as tab anchor e.g. wedding, christmas',
            ],
            'is_default' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '1 = main Home screen, 0 = custom screen',
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1 = active/show, 0 = hidden',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'comment'    => 'Order of tabs in admin panel',
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => false,
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug', 'uq_home_screens_slug');
        $this->forge->createTable('home_screens', true);

        // Seed default Home screen
        $this->db->table('home_screens')->insert([
            'name'       => 'Home',
            'slug'       => 'home',
            'is_default' => 1,
            'status'     => 1,
            'sort_order' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('home_screens', true);
    }
}
