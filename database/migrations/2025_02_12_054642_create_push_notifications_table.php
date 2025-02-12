<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default(0);
            $table->text('message')->nullable();
            $table->text('image_url')->nullable();
            $table->string('users')->nullable();
            $table->string('email_subject')->nullable();
            $table->text('email_body')->nullable();
            $table->text('description')->nullable();
            $table->string('push_url_option')->nullable();
            $table->string('push_url_option_value')->nullable();
            $table->dateTime('schedule_datetime')->nullable();
            $table->enum('status', [0, 1, 2])->default(0);
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
        Schema::dropIfExists('push_notifications');
    }
}
