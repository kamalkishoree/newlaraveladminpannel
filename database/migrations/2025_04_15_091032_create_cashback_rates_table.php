<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashbackRatesTable extends Migration
{
    public function up()
    {
        Schema::create('cashback_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->string('profit')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cashback_rates');
    }
}
