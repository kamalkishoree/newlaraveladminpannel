<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code')->unique()->nullable()->after('email');
            $table->decimal('referral_balance', 10, 2)->default(0)->after('referral_code');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'referral_balance']);
        });
    }
}; 