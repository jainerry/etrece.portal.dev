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
        Schema::create('faas_building_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('arpNo')->unique();
            $table->string('refID')->unique();
            $table->string('code')->nullable();
            $table->string('primary_owner')->nullable();
            $table->string('ownerAddress')->nullable();
            $table->string('tel_no')->nullable();
            $table->string('owner_tin_no')->nullable();
            $table->string('administrator')->nullable();
            $table->string('admin_address')->nullable();
            $table->string('admin_tel_no')->nullable();
            $table->string('admin_tin_no')->nullable();
            $table->string('no_of_street')->nullable();
            $table->string('barangay_id')->nullable();
            $table->string('municipality_id')->nullable();
            $table->string('province_id')->nullable();
            $table->string('oct_tct_no')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('survey_no')->nullable();
            $table->string('block_no')->nullable();
            $table->string('area')->nullable();
            $table->string('kind_of_building_id')->nullable();
            $table->string('structural_type_id')->nullable();
            $table->string('building_permit_no')->nullable();
            $table->string('building_permit_date_issued')->nullable();
            $table->string('condominium_certificate_of_title')->nullable();
            $table->string('certificate_of_completion_issued_on')->nullable();
            $table->string('certificate_of_occupancy_issued_on')->nullable();
            $table->string('date_constructed')->nullable();
            $table->string('date_occupied')->nullable();
            $table->string('no_of_storeys')->nullable();
            $table->string('area_first_floor')->nullable();
            $table->string('area_second_floor')->nullable();
            $table->string('area_third_floor')->nullable();
            $table->string('area_fourth_floor')->nullable();
            $table->string('flooring_1')->nullable();
            $table->string('flooring_2')->nullable();
            $table->string('flooring_3')->nullable();
            $table->string('flooring_4')->nullable();
            $table->string('walling_1')->nullable();
            $table->string('walling_2')->nullable();
            $table->string('walling_3')->nullable();
            $table->string('walling_4')->nullable();
            $table->char('isActive', 1)->default('Y');
            $table->string('assessmentStatusId')->nullable();
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
        Schema::dropIfExists('faas_building_profiles');
    }
};
