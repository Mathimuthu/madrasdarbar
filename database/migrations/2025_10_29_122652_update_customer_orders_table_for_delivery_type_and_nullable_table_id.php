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
            // 1️⃣ Drop foreign key constraint first
            $table->dropForeign(['dining_table_id']);

            // 2️⃣ Make column nullable, then reapply foreign key
            $table->foreignId('dining_table_id')
                ->nullable()
                ->change();

            $table->foreign('dining_table_id')
                ->references('id')
                ->on('dining_tables')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // 3️⃣ Add delivery_type column
            $table->string('delivery_type')->nullable()->after('order_total_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_orders', function (Blueprint $table) {
            // Drop new column
            $table->dropColumn('delivery_type');

            // Revert dining_table_id to NOT NULL
            $table->dropForeign(['dining_table_id']);
            $table->foreignId('dining_table_id')
                ->constrained('dining_tables')
                ->cascadeOnUpdate()
                ->cascadeOnDelete()
                ->change();
        });
    }
};
