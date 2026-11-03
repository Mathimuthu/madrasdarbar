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
        Schema::create('order_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('order_id')->constrained('customer_orders')->cascadeOnDelete();
            $table->foreignId('charge_id')->nullable()->constrained('charges')->nullOnDelete(); // null = custom charge
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->decimal('calculated_amount', 10, 2);
            $table->tinyInteger('type')->default(1); // 1 = Fixed, 2 = Percentage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_charges');
    }
};
