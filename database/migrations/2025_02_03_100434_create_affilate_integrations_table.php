<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffilateIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affilate_integrations', function (Blueprint $table) {
            $table->id();
            $table->string('provider_name')->unique();  // Unique name for the provider
            $table->text('api_key')->nullable(false);  // API Key (required)
            $table->string('client_id')->nullable(true); // Client ID (required)
            $table->string('client_secret')->nullable(true); // Client Secret (required)
            $table->text('headers')->nullable();  // Optional headers (JSON format)
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
        Schema::dropIfExists('affilate_integrations');
    }
}
