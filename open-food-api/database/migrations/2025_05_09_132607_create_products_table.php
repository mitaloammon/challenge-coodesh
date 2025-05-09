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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); 
            $table->string('code')->nullable(); 
            $table->string('url')->nullable();
            $table->string('creator')->nullable(); 
            $table->timestamp('created_t')->nullable();
            $table->timestamp('created_datetime')->nullable(); 
            $table->timestamp('last_modified_t')->nullable(); 
            $table->timestamp('last_modified_datetime')->nullable(); 
            $table->string('product_name')->nullable(); 
            $table->string('generic_name')->nullable(); 
            $table->string('quantity')->nullable(); 
            $table->string('packaging')->nullable(); 
            $table->string('brands')->nullable(); 
            $table->string('categories')->nullable();
            $table->string('origins')->nullable(); 
            $table->string('manufacturing_places')->nullable(); 
            $table->string('labels')->nullable(); 
            $table->string('image_url')->nullable(); 
            $table->string('image_small_url')->nullable(); 
            $table->string('serving_size')->nullable();
            $table->boolean('no_nutriments')->default(false);
            $table->string('nutrition_grade_fr')->nullable();
            $table->string('main_category')->nullable(); 
            $table->string('main_category_fr')->nullable(); 
            $table->float('energy_100g')->nullable(); 
            $table->float('fat_100g')->nullable(); 
            $table->float('saturated_fat_100g')->nullable(); 
            $table->float('sodium_100g')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
