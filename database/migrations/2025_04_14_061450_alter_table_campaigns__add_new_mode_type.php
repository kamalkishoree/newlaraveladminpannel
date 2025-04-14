<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCampaignsAddNewModeType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
             //mode_type
              $table->string('mode_type')->nullable()->after('conversion_status');
              $table->string('deal_id')->nullable()->after('brand_id');
              $table->string('coupon_id')->nullable()->after('deal_id');
              $table->string('financial_id')->nullable()->after('coupon_id');
              $table->string('brand_id')->nullable()->change();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('mode_type');
            $table->dropColumn('deal_id');
            $table->dropColumn('coupon_id');
            $table->dropColumn('financial_id');
        });
    }
}
