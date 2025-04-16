<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_id');
            $table->datetime('order_datetime');
            $table->decimal('order_amount', 10, 2);
            $table->string('order_status');
            $table->enum('status', ['inactive', 'opened', 'approved', 'closed'])->default('inactive');
            $table->string('subject');
            $table->text('description');
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}; 