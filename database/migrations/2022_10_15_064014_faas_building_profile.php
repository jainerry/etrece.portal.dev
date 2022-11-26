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
            $table->string('ARPNo',25)->unique()->nullable();
            $table->string('transactionCode',25)->nullable();
            $table->string('primary_owner')->nullable();
            $table->string('ownerAddress')->nullable();
            $table->string('tel_no',25)->nullable();
            $table->string('owner_tin_no',25)->nullable();
            $table->string('administrator')->nullable();
            $table->string('admin_address')->nullable();
            $table->string('admin_tel_no',25)->nullable();
            $table->string('admin_tin_no',25)->nullable();
            $table->string('no_of_street')->nullable();
            $table->string('barangay_id')->nullable();
            $table->string('municipality_id')->nullable();
            $table->string('province_id')->nullable();
            $table->string('oct_tct_no',25)->nullable();
            $table->string('lot_no',25)->nullable();
            $table->string('survey_no',25)->nullable();
            $table->string('block_no',25)->nullable();
            $table->string('area')->nullable();
            $table->string('kind_of_building_id')->nullable();
            $table->string('buildingAge')->nullable();
            $table->string('structural_type_id')->nullable();
            $table->string('building_permit_no',25)->nullable();
            $table->string('building_permit_date_issued')->nullable();
            $table->string('condominium_certificate_of_title')->nullable();
            $table->string('certificate_of_completion_issued_on')->nullable();
            $table->string('certificate_of_occupancy_issued_on')->nullable();
            $table->string('date_constructed')->nullable();
            $table->string('date_occupied')->nullable();
            $table->string('no_of_storeys')->nullable();
            $table->text('floorsArea')->nullable();
            $table->string('totalFloorArea')->nullable();
            $table->string('roof')->nullable();
            $table->string('other_roof')->nullable();
            $table->text('flooring')->nullable();
            $table->text('walling')->nullable();
            $table->text('additionalItems')->nullable();
            $table->string('unitConstructionCost')->nullable();
            $table->string('unitConstructionSubTotal')->nullable();
            $table->string('costOfAdditionalItemsSubTotal')->nullable();
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
            $table->boolean('isActive')->default(1);
            $table->boolean('isApproved')->default(0);
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
