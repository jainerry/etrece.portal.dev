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
        Schema::create('faas_lands', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->unique();
            $table->string('ARPNo')->unique()->nullable();
            $table->string('pin')->nullable();
            $table->string('transactionCode')->nullable();
            $table->string('octTctNo')->nullable();
            $table->string('lotNo')->nullable();
            $table->string('blkNo')->nullable();
            $table->string('previousOwnerId')->nullable();
            $table->string('primaryOwnerId')->nullable();
            $table->text('primaryOwnerText')->nullable();
            $table->string('ownerAddress')->nullable();
            $table->string('ownerTelephoneNo')->nullable();
            $table->string('administrator')->nullable();
            $table->string('administratorAddress')->nullable();
            $table->string('administratorTelephoneNo')->nullable();
            $table->string('noOfStreet')->nullable();
            $table->string('barangayId')->nullable();
            $table->string('cityId')->nullable();
            $table->string('provinceId')->nullable();
            $table->string('propertyBoundaryNorth')->nullable();
            $table->string('propertyBoundaryEast')->nullable();
            $table->string('propertyBoundarySouth')->nullable();
            $table->string('propertyBoundaryWest')->nullable();
            $table->string('landSketch')->nullable();
            $table->text('landAppraisal')->nullable();
            $table->text('otherImprovements')->nullable();
            $table->text('marketValue')->nullable();
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
            $table->boolean('isIdleLand')->default(0);
            $table->boolean('isOwnerNonTreceResident', 1)->default(0);
            $table->string('totalLandAppraisalBaseMarketValue')->nullable();
            $table->string('totalOtherImprovementsBaseMarketValue')->nullable();
            $table->string('totalMarketValueMarketValue')->nullable();
            $table->string('totalPropertyAssessmentMarketValue')->nullable();
            $table->string('totalPropertyAssessmentAssessmentValue')->nullable();
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
        Schema::dropIfExists('faas_lands');
    }
};
