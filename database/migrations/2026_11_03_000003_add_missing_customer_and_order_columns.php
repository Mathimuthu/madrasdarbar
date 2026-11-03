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
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'door_no')) {
                $table->string('door_no')->nullable()->after('address');
            }
            if (!Schema::hasColumn('customers', 'area')) {
                $table->string('area')->nullable()->after('door_no');
            }
            if (!Schema::hasColumn('customers', 'latlon')) {
                $table->string('latlon')->nullable()->after('area'); // "lat,lon"
            }
            if (!Schema::hasColumn('customers', 'fcm_token')) {
                $table->text('fcm_token')->nullable()->after('latlon');
            }
        });

        Schema::table('customer_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_orders', 'order_notes')) {
                $table->text('order_notes')->nullable()->after('order_status');
            }
            if (!Schema::hasColumn('customer_orders', 'delivery_charge')) {
                $table->decimal('delivery_charge', 10, 2)->default(0)->after('order_total_price');
            }
            if (!Schema::hasColumn('customer_orders', 'delivery_charge_added_by')) {
                $table->foreignId('delivery_charge_added_by')->nullable()->after('delivery_charge')
                    ->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('delivery_charge_added_by');
            $table->dropColumn(['order_notes', 'delivery_charge']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['door_no', 'area', 'latlon', 'fcm_token']);
        });
    }
};
