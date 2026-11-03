<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_order_code_to_customer_orders_table.php
public function up()
{
    Schema::table('customer_orders', function (Blueprint $table) {
        $table->string('order_code')->unique()->nullable()->after('id');
        $table->index('order_code');
    });
}

public function down()
{
    Schema::table('customer_orders', function (Blueprint $table) {
        $table->dropIndex(['order_code']);
        $table->dropColumn('order_code');
    });
}
};
