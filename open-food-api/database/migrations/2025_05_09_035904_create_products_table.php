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
            $table->bigInteger('code')->unique();
            $table->string('url')->nullable();
            $table->string('creator')->nullable();
            $table->bigInteger('created_t')->nullable();
            $table->bigInteger('last_modified_t')->nullable();
            $table->string('product_name')->nullable();
            $table->string('quantity')->nullable();
            $table->string('brands')->nullable();
            $table->text('categories')->nullable();
            $table->text('labels')->nullable();
            $table->string('cities')->nullable();
            $table->string('purchase_places')->nullable();
            $table->string('stores')->nullable();
            $table->text('ingredients_text')->nullable();
            $table->text('traces')->nullable();
            $table->string('serving_size')->nullable();
            $table->decimal('serving_quantity', 8, 2)->nullable();
            $table->integer('nutriscore_score')->nullable();
            $table->string('nutriscore_grade', 5)->nullable();
            $table->string('main_category')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamp('imported_t')->nullable();
            $table->enum('status', ['draft', 'imported', 'trash'])->default('draft');
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
