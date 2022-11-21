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
            $table->string('buss_id')->unique();
            $table->string('business_name')->unique();
            $table->string('owner_cid');
            $table->string('property_owner');
            $table->string('lessor_name_cid');
            $table->string('tel_no')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('buss_type')->nullable();
            $table->string('corp_type')->nullable();
            $table->string('trade_name_franchise')->nullable();
            $table->string('business_activity_id')->nullable();
            $table->string('other_buss_type')->nullable();
            $table->string('faas_land_id')->nullable();
            $table->string('sec_no')->nullable();
            $table->date('sec_reg_date');
            $table->string('dti_no')->nullable();
            $table->date('dti_reg_date');
            $table->string('tax_incentives')->nullable();
            $table->string('certificate')->nullable();
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
