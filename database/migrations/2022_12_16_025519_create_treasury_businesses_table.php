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
        Schema::create('treasury_businesses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->unique();
            $table->string('orNo')->unique()->nullable();
            $table->string('businessId')->nullable();
            $table->text('otherFees')->nullable();
            $table->string('totalOtherFees')->nullable();
            // $table->string('businessTax')->nullable();
            // $table->string('businessTaxPenalty')->nullable();
            // $table->string('businessTaxInterest')->nullable();
            // $table->string('mayorsPermit')->nullable();
            // $table->string('mayorsPermitPenalty')->nullable();
            // $table->string('summaryOtherFees')->nullable();
            // $table->string('delinquent')->nullable();
            // $table->string('taxWithheldDiscount')->nullable();
            $table->text('details')->nullable();
            $table->string('totalDetailsPreviousYear')->nullable();
            $table->string('totalDetailsCurrentYear')->nullable();
            // $table->string('assessedBy')->nullable();
            // $table->string('assessedDate')->nullable();
            $table->boolean('isActive')->default(1);
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
        Schema::dropIfExists('treasury_businesses');
    }
};
