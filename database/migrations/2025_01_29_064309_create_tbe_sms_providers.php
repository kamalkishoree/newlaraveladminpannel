<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbeSmsProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_providers', function (Blueprint $table) {
            $table->id();
            $table->string('provider_name'); // SMS provider name
            $table->string('sms_from'); // SMS From or Client ID
            $table->string('api_key'); // API Key or Email
            $table->string('api_secret'); // API Secret or Password
            $table->string('app_id'); // SMS provider name
            $table->integer('status')->default(0); // SMS provider name
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
        Schema::dropIfExists('sms_providers');
    }
}
