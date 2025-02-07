<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(false); // Banner title
            $table->text('description')->nullable(); // Optional description
            $table->text('image_url')->nullable(false); // Image URL for the banner
            $table->text('redirect_url')->nullable(); // URL to navigate on banner click
            $table->enum('platform', ['mobile', 'website', 'both'])->default('both'); // Platform type
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status of the banner
            $table->timestamp('start_date')->nullable(true); // Required start date
            $table->timestamp('end_date')->nullable(true); // Required end date
            $table->timestamps();
        });
    } 
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
