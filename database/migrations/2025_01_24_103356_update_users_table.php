<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('name'); // Add 'last_name' column
            $table->string('dial_code', 10)->nullable()->after('last_name'); // Add 'dial_code' column
            $table->string('phone_otp')->nullable()->after('dial_code'); // Add 'dial_code' column
            $table->string('email_otp')->nullable()->after('phone_otp'); // Add 'dial_code' column
            $table->string('joining_referal')->nullable()->after('phone_otp'); // Add 'dial_code' column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_name'); // Drop 'last_name' column
            $table->dropColumn('dial_code'); // Drop 'dial_code' column
            $table->dropColumn('phone_otp'); // Drop 'dial_code' column
            $table->dropColumn('email_otp'); // Drop 'dial_code' column
            $table->dropColumn('joining_referal')->nullable()->after('phone_otp'); // Add 'dial_code' column

        });
    }
}
