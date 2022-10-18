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
            $table->id();
            $table->string('ARPNo')->unique();
            $table->string('pin')->nullable();
            $table->string('transactionCode')->nullable();
            $table->string('primaryOwner');
            $table->string('secondaryOwners')->nullable();
            $table->string('ownerAddress')->nullable();
            $table->string('ownerTelephoneNo')->nullable();
            $table->string('ownerTin')->nullable();
            $table->string('administrator')->nullable();
            $table->string('administratorAddress')->nullable();
            $table->string('administratorTelephoneNo')->nullable();
            $table->string('administratorTin')->nullable();
            $table->string('noOfStreet')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('landOwner')->nullable();
            $table->string('buildingOwner')->nullable();
            $table->string('landOwnerPin')->nullable();
            $table->string('buildingOwnerPin')->nullable();
            $table->text('propertyAppraisal')->nullable();
            $table->text('propertyAssessment')->nullable();
            $table->string('assessmentType')->nullable();
            $table->string('assessmentEffectivity')->nullable();
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
