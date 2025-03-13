<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->string('currency')->default('INR');
                $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected'
                $table->string('txn_id')->unique(); // Tra nsaction reference
                $table->text('admin_note')->nullable(); // Admin can add a note
                $table->foreignId('bank_account_id')->constrained()->onDelete('cascade');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     
        Schema::dropIfExists('withdrawals');
    }
}
