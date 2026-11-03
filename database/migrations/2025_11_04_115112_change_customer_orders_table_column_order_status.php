<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer_orders', function (Blueprint $table) {
             $table->enum('order_status', [
                'Ordered',
                'preparing',
                'ready_to_deliver',
                'delivery_on_the_way',
                'delivered',
                'completed',
                'cancelled',
            ])->default('ordered')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_orders', function (Blueprint $table) {
             $table->enum('order_status', ['Preparing', 'Completed'])
                  ->default('Preparing')
                  ->change();
        });
    }
};
