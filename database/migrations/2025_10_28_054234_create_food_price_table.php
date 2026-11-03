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
        Schema::create('food_price', function (Blueprint $table) {
            $table->unsignedBigInteger('food_id');
            $table->unsignedBigInteger('location_id');
            $table->decimal('price', 10, 2);
            
            $table->primary(['food_id', 'location_id']);

            $table->foreign('food_id')
                ->references('id')
                ->on('food_menus')
                ->onDelete('cascade'); 

            $table->foreign('location_id')
                ->references('location_id')
                ->on('location')
                ->onDelete('cascade'); 
            $table->timestamps(); 
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_price');
    }
};
