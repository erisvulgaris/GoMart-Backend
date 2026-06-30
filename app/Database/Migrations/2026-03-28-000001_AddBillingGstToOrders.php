<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBillingGstToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'billing_gst' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => '',
                'after'      => 'delivery_instruction',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'billing_gst');
    }
}
