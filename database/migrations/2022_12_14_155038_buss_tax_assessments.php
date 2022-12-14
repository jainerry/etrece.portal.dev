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
        Schema::create('buss_tax_assessments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->unique();
            $table->string('application_type')->nullable();
            $table->string('business_profiles_id')->nullable();
            $table->string('assessment_date')->nullable();
            $table->string('assessment_year')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('net_profit')->nullable();
            $table->string('num_of_employees')->nullable();
            $table->string('other_fees')->nullable();
            $table->string('deliquent')->nullable();
            $table->string('tax_withheld_and_discount')->nullable();
            $table->string('remarks')->nullable();
            $table->text('assessmentLevels')->nullable();
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
