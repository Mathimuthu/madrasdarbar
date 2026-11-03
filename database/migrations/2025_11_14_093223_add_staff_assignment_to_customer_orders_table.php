<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, drop the existing table if it exists
        Schema::dropIfExists('order_histories');

        // Add assigned_staff_id to customer_orders if not exists
        if (!Schema::hasColumn('customer_orders', 'assigned_staff_id')) {
            Schema::table('customer_orders', function (Blueprint $table) {
                $table->foreignId('assigned_staff_id')->nullable()->constrained('users')->onDelete('set null');
            });
        }

        // Create order history table with string order_id
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->string('order_id'); // Match the string type from customer_orders
            $table->foreignId('staff_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action'); // accepted, unaccepted, status_changed, etc.
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Add foreign key with string reference
            $table->foreign('order_id')->references('id')->on('customer_orders')->onDelete('cascade');
            
            // Add index for better performance
            $table->index('order_id');
            $table->index('staff_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_histories');
        
        if (Schema::hasColumn('customer_orders', 'assigned_staff_id')) {
            Schema::table('customer_orders', function (Blueprint $table) {
                $table->dropForeign(['assigned_staff_id']);
                $table->dropColumn('assigned_staff_id');
            });
        }
    }
};