<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();  // Primary key for product
            $table->string('name');  // Product name
            $table->string('slug')->unique();  // URL-friendly product name
            $table->text('description')->nullable();  // Full description
            $table->text('short_description')->nullable();  // Short description
            $table->decimal('price', 10, 2)->nullable();  // Price
            $table->decimal('discount_price', 10, 2)->nullable();  // Discounted price
            $table->string('sku')->unique();  // SKU
            $table->text('target_url')->nullable();  // SKU
            $table->integer('stock_quantity')->default(0);  // Stock quantity
            $table->boolean('status')->default(true);  // Active status
            $table->enum('is_new', ['1', '0'])->default('0');
            $table->enum('is_top', ['1', '0'])->default('0');
            $table->enum('is_feature', ['1', '0'])->default('0');
            $table->text('image_url')->nullable();  // Main image path
            $table->json('additional_images')->nullable();  // Additional product images
            $table->text('video_url')->nullable();  // Video URL
            $table->boolean('is_featured')->default(false);  // Featured product
            $table->boolean('is_visible')->default(true);  // Visibility status
            $table->decimal('weight', 8, 2)->nullable();  // Product weight
            $table->json('dimensions')->nullable();  // Dimensions (length, width, height)
            $table->string('shipping_class')->nullable();  // Shipping class
            $table->timestamp('available_from')->nullable();  // When it becomes available
            $table->timestamp('available_until')->nullable();  // When it is no longer available
            $table->timestamps();  // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
