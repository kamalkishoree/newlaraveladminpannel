<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('conversions', function (Blueprint $table) {
            $table->bigInteger('user_id')->after('id');
            $table->char('click_id', 24)->nullable()->after('user_id');
            $table->string('unique_source_id')->nullable()->after('click_id');
            $table->string('method', 50)->nullable()->after('unique_source_id');
            $table->decimal('sale', 10, 2)->nullable()->after('method');
            $table->string('p1')->nullable()->after('sale'); // Moved to an appropriate location
            $table->string('p2')->nullable()->after('p1');
            $table->string('p3')->nullable()->after('p2');
            $table->string('p4')->nullable()->after('p3');
            $table->string('p5')->nullable()->after('p4');
            $table->string('sub1')->nullable()->after('p5');
            $table->string('txn_id')->nullable()->after('sub1'); // Compatible placement
            $table->text('note')->nullable()->after('txn_id');
            $table->string('currency', 10)->nullable()->after('note');
            $table->decimal('payout', 10, 3)->nullable()->after('currency');
            $table->string('brand')->nullable()->after('payout');
            $table->string('status', 50)->nullable()->after('brand');
            $table->unsignedBigInteger('campaign_id')->nullable()->after('status');
            $table->string('campaign_name')->nullable()->after('campaign_id');
        });
    }

    public function down(): void
    {
        Schema::table('conversions', function (Blueprint $table) {
            $table->dropColumn([
                'user_id',
                'click_id',
                'unique_source_id',
                'method',
                'sale',
                'p1',
                'p2',
                'p3',
                'p4',
                'p5',
                'sub1',
                'txn_id',
                'note',
                'currency',
                'payout',
                'brand',
                'status',
                'campaign_id',
                'campaign_name',
            ]);
        });
    }
};
