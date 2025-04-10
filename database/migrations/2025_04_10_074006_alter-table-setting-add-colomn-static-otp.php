<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSettingAddColomnStaticOtp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
             //static OTP
              $table->integer('static_otp')->default(0)->after('currency_id');
               // SMTP Fields
               $table->string('mail_mailer')->nullable();
               $table->string('mail_host')->nullable();
               $table->string('mail_port')->nullable();
               $table->string('mail_username')->nullable();
               $table->string('mail_password')->nullable();
               $table->string('mail_encryption')->nullable();
               $table->string('mail_from_address')->nullable();
               $table->string('mail_from_name')->nullable();
   
               // OTP Fields
               $table->string('otp_service')->nullable(); // e.g., 'twilio', 'msg91'
               $table->string('otp_api_key')->nullable();
               $table->string('otp_api_secret')->nullable();
               $table->string('otp_sender_id')->nullable();
               $table->string('otp_template_id')->nullable();
               $table->boolean('otp_enabled')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('static_otp');
            $table->dropColumn([
                'mail_mailer',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_from_address',
                'mail_from_name',
            ]);
            // OTP fields
            $table->dropColumn([
                'otp_service',
                'otp_api_key',
                'otp_api_secret',
                'otp_sender_id',
                'otp_template_id',
                'otp_enabled',
            ]);
        });
    }
}
