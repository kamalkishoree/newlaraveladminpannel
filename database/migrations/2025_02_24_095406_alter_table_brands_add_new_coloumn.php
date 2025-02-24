<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableBrandsAddNewColoumn extends Migration
{
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->string('clocking_url')->nullable()->after('target_url');
            $table->text('profit_tracking_hours')->nullable()->after('clocking_url');
            $table->text('profit_confirmation_days')->nullable()->after('profit_tracking_hours');
            $table->decimal('cashback_profit', 10, 2)->nullable()->after('profit_confirmation_days');
            $table->text('cashback_rates')->nullable()->after('cashback_profit');
            $table->text('cashback_terms')->nullable()->after('cashback_rates');
            $table->enum('payout_type', ['flat', 'percentage', 'custom'])->default('flat')->after('cashback_terms');
            $table->decimal('payout_amount')->default(0)->after('payout_type');
            $table->string('affiliate_network_id')->nullable()->after('payout_amount');
        });
    }

    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn([
                'profit_tracking_hours',
                'profit_confirmation_days',
                'cashback_profit',
                'cashback_rates',
                'cashback_terms',
                'payout_amount',
                'payout_type',
                'clocking_url',
                'affiliate_network_id',
            ]);
        });
    }
}
