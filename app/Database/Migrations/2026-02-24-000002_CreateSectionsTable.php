<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSectionsTable extends Migration
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
            'home_screen_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'FK to home_screens.id',
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'Main heading e.g. Trending near you',
            ],
            'short_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'comment'    => 'Subtitle/description text',
            ],
            'content_type' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=Categories, 2=Products',
            ],
            'product_content_type' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'comment'    => '1=Basic 2=TopSellers 3=TopSelling 4=TopRatings 5=PriceRange 6=InterestBased',
            ],
            'section_type' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '0=Dynamic, 1=Manually',
            ],
            'product_type' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'comment'    => '1=Physical, 2=Digital',
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Filter by category (0 or NULL = all)',
            ],
            'sub_category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Filter by sub-category (0 or NULL = all)',
            ],
            'brand_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Filter by brand (0 or NULL = all brands)',
            ],
            'seller_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Filter by seller (0 or NULL = all sellers)',
            ],
            'selling_type' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'comment'    => '1=Weekly, 2=Monthly, 3=Yearly',
            ],
            'price_min' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'default'    => 0.00,
                'comment'    => 'Min price filter',
            ],
            'price_max' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'default'    => 0.00,
                'comment'    => 'Max price filter',
            ],
            'screen_layout' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'potrait_item',
                'comment'    => 'category_list | category_grid | potrait_item',
            ],
            'no_of_content' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 10,
                'comment'    => 'Number of items to fetch/show',
            ],
            'no_of_row' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 1,
                'comment'    => 'Rows in grid layout',
            ],
            'view_all' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '1=Show View All button',
            ],
            'load_more' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '1=Show Load More button',
            ],
            'order_by_upload' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=ASC, 2=DESC (by upload date)',
            ],
            'order_by_like' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=ASC, 2=DESC (by likes)',
            ],
            'background_type' => [
                'type'       => 'ENUM',
                'constraint' => ['color', 'image'],
                'default'    => 'color',
            ],
            'bg_color' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
                'default'    => '#FFFFFF',
                'comment'    => 'Hex color e.g. #FFFFFF',
            ],
            'bg_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'comment'    => 'Background image file path',
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=Show, 0=Hidden',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'comment'    => 'Drag-and-drop order within a screen',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('home_screen_id', false, false, 'idx_sections_home_screen');
        $this->forge->addKey(['home_screen_id', 'status', 'sort_order'], false, false, 'idx_sections_status_sort');
        $this->forge->addKey('category_id', false, false, 'idx_sections_category');
        $this->forge->addKey('brand_id', false, false, 'idx_sections_brand');
        $this->forge->addForeignKey('home_screen_id', 'home_screens', 'id', 'CASCADE', 'CASCADE', 'fk_sections_home_screen');
        $this->forge->createTable('sections', true);
    }

    public function down()
    {
        $this->forge->dropTable('sections', true);
    }
}
