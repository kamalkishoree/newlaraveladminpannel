<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('transaction_type'); // 'credit', 'debit'
            $table->decimal('amount', 10, 2);
            $table->decimal('balance_after', 10, 2)->nullable();
            $table->string('currency')->default('INR');
            $table->string('status')->default('pending'); // 'pending', 'completed', 'failed'
            $table->string('source')->nullable(); // e.g. 'conversion', 'withdrawal'
            $table->string('txn_id')->nullable(); // Transaction ID for reference
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
        Schema::dropIfExists('transactions');
    }
}
