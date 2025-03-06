<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCampaigsAddNewColoumnClickId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->uuid('unique_source_id')->nullable()->after('id')->index();
            $table->integer('conversion_status')->default(0);
            $table->dropColumn('unique_p1_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('unique_source_id');
            $table->uuid('unique_p1_id')->nullable()->after('id')->index();
            $table->dropColumn('conversion_status');

        });
    }
}
