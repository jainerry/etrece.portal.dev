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
        Schema::create('treasury_rpts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->unique();
            $table->string('orNo')->unique()->nullable();
            $table->string('rptId')->nullable();
            $table->string('rptType')->nullable();
            $table->string('year')->nullable();
            $table->string('periodCovered')->nullable();
            // $table->text('otherFees')->nullable();
            // $table->string('totalOtherFees')->nullable();
            $table->text('summary')->nullable();
            $table->string('totalSummaryAmount')->nullable();
            $table->string('paymentDate')->nullable();
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
        Schema::dropIfExists('treasury_rpts');
    }
};
