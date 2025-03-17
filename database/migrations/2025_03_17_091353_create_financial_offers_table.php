<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_offers', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->foreignId('category_id')->constrained()->onDelete('cascade');  // Foreign key to Category table
            $table->string('name');  // Brand name
            $table->string('slug')->unique();  // URL slug (unique)
            $table->text('image_url')->nullable();  // Logo URL (optional)
            $table->text('description')->nullable();  // Description (optional)
            $table->text('target_url')->nullable();  // Target URL (optional)
            $table->enum('is_new', ['1', '0'])->default('0');
            $table->enum('is_top', ['1', '0'])->default('0');
            $table->enum('is_feature', ['1', '0'])->default('0');
            $table->boolean('status')->default(true);  // Brand is active or not
            $table->timestamps();  // Created and updated timestamps
        
            // Newly added columns
            $table->string('clocking_url')->nullable();
            $table->text('profit_tracking_hours')->nullable();
            $table->text('profit_confirmation_days')->nullable();
            $table->decimal('cashback_profit', 10, 2)->nullable();
            $table->text('cashback_rates')->nullable();
            $table->text('cashback_terms')->nullable();
            $table->enum('payout_type', ['flat', 'percentage', 'custom'])->default('flat');
            $table->decimal('payout_amount', 10, 2)->default(0); // Added precision (10,2) to match cashback_profit
            $table->string('affiliate_network_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_offers');
    }
}
