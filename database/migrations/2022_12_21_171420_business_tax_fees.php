<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_tax_fees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->nullable();
            $table->string('business_fees_id')->nullable();
            $table->date('effective_date')->nullable();
            $table->string('chart_of_accounts_lvl4_id')->nullable();
            $table->string('business_categories')->nullable();
            $table->string('Basis')->nullable();
            $table->json('range_box')->nullable();
            $table->string('computation')->nullable();
            $table->string('amount_value')->nullable();
            $table->char('isActive', 1)->default('Y');
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
        //
    }
};
