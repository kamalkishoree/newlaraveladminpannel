<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users');
            $table->foreignId('referred_id')->constrained('users');
            $table->string('referral_code');
            $table->enum('status', ['pending', 'completed','rejected']);
            $table->decimal('reward_amount', 10, 2)->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('referrals');
    }
}; 