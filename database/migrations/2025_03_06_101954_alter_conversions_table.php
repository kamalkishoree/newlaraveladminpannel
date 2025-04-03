<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('conversions', function (Blueprint $table) {
            // Ensure existing columns have the right size and data type
            $table->bigInteger('user_id')->nullable()->after('id');
            $table->bigInteger('user_campaign_id')->nullable()->after('user_id');
            $table->string('unique_source_id', 100)->nullable()->after('user_campaign_id');
            $table->string('campaign_id', 100)->nullable()->after('unique_source_id');
            $table->string('campaign_name', 255)->nullable()->after('campaign_id');
            $table->string('publisher_id', 100)->nullable()->after('campaign_name');
            $table->string('aff_name', 100)->nullable()->after('publisher_id');
            $table->string('aff_id', 100)->nullable()->after('aff_name');
            $table->char('click_id', 100)->nullable()->after('aff_id');
            $table->timestamp('click_time')->nullable()->after('click_id');
            $table->string('conversion_id', 100)->nullable()->after('click_time');
            $table->timestamp('conversion_datetime')->nullable()->after('conversion_id');
            $table->string('conversion_status', 100)->nullable()->after('conversion_datetime');
            $table->decimal('payout', 10, 3)->nullable()->after('conversion_status');
            $table->string('currency', 10)->nullable()->after('payout');
            $table->string('txn_id', 100)->nullable()->after('currency');
            $table->decimal('sale_amount', 10, 2)->nullable()->after('txn_id');
            $table->ipAddress('click_ip')->nullable()->after('sale_amount');
            $table->string('city', 100)->nullable()->after('click_ip');
            $table->string('region', 100)->nullable()->after('city');
            $table->string('isp', 150)->nullable()->after('region');
            $table->string('goal_name', 100)->nullable()->after('isp');
            $table->string('goal_id', 100)->nullable()->after('goal_name');

        });
    }

    public function down()
    {
        Schema::table('conversions', function (Blueprint $table) {
            $table->dropColumn([
                'user_id','user_campaign_id','unique_source_id', 'campaign_id', 'campaign_name', 'publisher_id', 'aff_name', 'aff_id', 
                'click_id', 'click_time', 'conversion_id', 'conversion_datetime', 
                'conversion_status', 'payout', 'currency', 'txn_id', 'sale_amount', 
                'click_ip', 'city', 'region', 'isp', 'goal_name', 'goal_id'
            ]);
        });
    }
};
