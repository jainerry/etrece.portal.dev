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
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->unique();
            $table->string('buss_id')->unique()->nullable();
            $table->string('business_name')->unique();
            $table->string('owner_id')->nullable();
            $table->string('main_office_address')->nullable();
            $table->char('property_owner',1)->nullable();
            $table->string('lessor_name')->nullable();

            $table->string('tel')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('tin')->nullable();


            $table->string('buss_type')->nullable();
            $table->string('corp_type')->nullable();
            
            $table->string('trade_name_franchise')->nullable();
            $table->string('business_activity_id')->nullable();
            $table->string('other_buss_type')->nullable();
            $table->string('buss_activity_address_id')->nullable();
            $table->string('same_as_head_office')->nullable();
            $table->string('sec_no')->nullable();
            $table->date('sec_reg_date')->nullable();
            $table->string('dti_no')->nullable();
            $table->date('dti_reg_date')->nullable();

            $table->char('weight_and_measure',1)->nullable();
            $table->string('area')->nullable();
            $table->char('unit_of_measurement',1)->nullable();
            $table->char('weight_and_measure_value',1)->nullable();


            $table->string('tax_incentives')->nullable();
            $table->string('certificate')->nullable();
            
            $table->json('line_of_business')->nullable();
            $table->json('number_of_employees')->nullable();
            $table->json('vehicles')->nullable();


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
        Schema::dropIfExists('business_profiles');
    }
};
