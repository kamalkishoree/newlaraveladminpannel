<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('image_url')->nullable(); // Optional image for the category
            $table->enum('is_parent', ['1', '0'])->default('0');
            $table->unsignedBigInteger('parent_id')->nullable(); // For hierarchical categories
            $table->enum('is_new', ['1', '0'])->default('0');
            $table->enum('is_top', ['1', '0'])->default('0');
            $table->enum('is_feature', ['1', '0'])->default('0');
            $table->integer('sort_order')->default(0); // Sorting purposes
            $table->boolean('is_active')->default(true); // Soft status control
            $table->timestamps();
            $table->softDeletes();
            // Foreign key constraint for parent category
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_categories');
    }
}
