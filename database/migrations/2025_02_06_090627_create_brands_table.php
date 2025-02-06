<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->foreignId('category_id')->constrained()->onDelete('cascade');  // Foreign key to Category table
            $table->string('name');  // Brand name
            $table->string('slug')->unique();  // URL slug (unique)
            $table->text('image_url')->nullable();  // Logo URL (optional)
            $table->text('description')->nullable();  // Description (optional)
            $table->enum('is_new', ['1', '0'])->default('0');
            $table->enum('is_top', ['1', '0'])->default('0');
            $table->enum('is_feature', ['1', '0'])->default('0');
            $table->boolean('status')->default(true);  // Brand is active or not
            $table->timestamps();  // Created and updated timestamps
      
        });
    }

    public function down()
    {
        Schema::dropIfExists('brands');
    }

}
