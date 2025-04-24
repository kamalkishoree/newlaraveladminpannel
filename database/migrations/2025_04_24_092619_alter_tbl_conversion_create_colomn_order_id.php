<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTblConversionCreateColomnOrderId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversions', function (Blueprint $table) {
             $table->integer('order_id')->nullable()->after('unique_source_id');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversions', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }
}
