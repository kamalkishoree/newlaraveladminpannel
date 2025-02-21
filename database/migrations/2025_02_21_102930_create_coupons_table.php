<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');  // Foreign key to Category table
            $table->string('headline');  // Brand name
            $table->string('sub_headline')->unique();  // URL slug (unique)
            $table->text('image_url')->nullable();  // Logo URL (optional)
            $table->text('description')->nullable();  // Description (optional)
            $table->text('sharing_message')->nullable();  // Description (optional)
            $table->string('code')->nullable();  // Description (optional)
            $table->text('target_url')->nullable();  // Description (optional)
            $table->text('clocking_url')->nullable();  // Description (optional)
            $table->enum('is_new', ['1', '0'])->default('0');
            $table->enum('is_top', ['1', '0'])->default('0');
            $table->enum('is_feature', ['1', '0'])->default('0');
            $table->dateTime('expires_at')->nullable();
            $table->boolean('status')->default(true);  // Brand is active or not
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
        Schema::dropIfExists('coupons');
    }
}
