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
        Schema::create('faas_machineries', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('refID')->unique();
            $table->string('ARPNo')->nullable();
            $table->string('pin')->nullable();
            $table->string('transactionCode')->nullable();
            $table->foreignUuid('primaryOwnerId');
            $table->string('ownerAddress')->nullable();
            $table->string('ownerTelephoneNo')->nullable();
            $table->string('ownerTin')->nullable();
            $table->string('administrator')->nullable();
            $table->string('administratorAddress')->nullable();
            $table->string('administratorTelephoneNo')->nullable();
            $table->string('administratorTin')->nullable();
            $table->string('noOfStreet')->nullable();
            $table->string('barangayId')->nullable();
            $table->string('cityId')->nullable();
            $table->string('provinceId')->nullable();
            $table->string('landOwnerId')->nullable();
            $table->foreignUuid('buildingOwnerId')->nullable();
            $table->foreignUuid('landOwnerPin')->nullable();
            $table->string('buildingOwnerPin')->nullable();
            $table->text('propertyAppraisal')->nullable();
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
            $table->string('TDNo')->nullable();
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
        Schema::dropIfExists('faas_machineries');
    }
};
