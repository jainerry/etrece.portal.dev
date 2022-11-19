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
            $table->uuid('id')->primary();
            $table->string('refID')->unique();
            $table->string('ARPNo',25)->nullable();
            $table->string('transactionCode',25)->nullable();
            $table->foreignUuid('primary_owner')->nullable();
            $table->string('ownerAddress')->nullable();
            $table->string('tel_no',25)->nullable();
            $table->string('owner_tin_no',25)->nullable();
            $table->string('administrator')->nullable();
            $table->string('admin_address')->nullable();
            $table->string('admin_tel_no',25)->nullable();
            $table->string('admin_tin_no',25)->nullable();
            $table->string('no_of_street')->nullable();
            $table->foreignUuid('barangay_id')->nullable();
            $table->foreignUuid('municipality_id')->nullable();
            $table->foreignUuid('province_id')->nullable();
            $table->string('oct_tct_no',25)->nullable();
            $table->string('lot_no',25)->nullable();
            $table->string('survey_no',25)->nullable();
            $table->string('block_no',25)->nullable();
            $table->string('area')->nullable();
            $table->foreignUuid('kind_of_building_id')->nullable();
            $table->foreignUuid('buildingAge')->nullable();
            $table->foreignUuid('structural_type_id')->nullable();
            $table->string('building_permit_no',25)->nullable();
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
            $table->string('totalFloorArea')->nullable();
            $table->string('roof')->nullable();
            $table->string('other_roof')->nullable();
            $table->string('floor1_flooring')->nullable();
            $table->string('floor2_flooring')->nullable();
            $table->string('floor3_flooring')->nullable();
            $table->string('floor4_flooring')->nullable();
            $table->string('floor1_otherFlooring')->nullable();
            $table->string('floor2_otherFlooring')->nullable();
            $table->string('floor3_otherFlooring')->nullable();
            $table->string('floor4_otherFlooring')->nullable();
            $table->string('floor1_walling')->nullable();
            $table->string('floor2_walling')->nullable();
            $table->string('floor3_walling')->nullable();
            $table->string('floor4_walling')->nullable();
            $table->string('floor1_otherWalling')->nullable();
            $table->string('floor2_otherWalling')->nullable();
            $table->string('floor3_otherWalling')->nullable();
            $table->string('floor4_otherWalling')->nullable();
            $table->text('additionalItems')->nullable();
            $table->string('unitConstructionCost')->nullable();
            $table->string('unitConstructionSubTotal')->nullable();
            $table->string('totalConstructionCost')->nullable();
            $table->string('depreciationRate')->nullable();
            $table->string('depreciationCost')->nullable();
            $table->string('totalPercentDepreciation')->nullable();
            $table->string('marketValue')->nullable();
            $table->text('propertyAssessment')->nullable();
            $table->string('assessmentType')->nullable();
            $table->string('assessmentEffectivity')->nullable();
            $table->string('assessmentEffectivityValue')->nullable();
            $table->string('assessedBy')->nullable();
            $table->string('assessedDate')->nullable();
            $table->string('recommendingPersonel')->nullable();
            $table->string('recommendingApprovalDate')->nullable();
            $table->string('approvedBy')->nullable();
            $table->string('approvedDate')->nullable();
            $table->text('memoranda')->nullable();
            $table->string('recordOfAssesmentEntryDate')->nullable();
            $table->string('recordingPersonel')->nullable();
            $table->string('TDNo',25)->nullable();
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
        Schema::dropIfExists('faas_building_profiles');
    }
};
