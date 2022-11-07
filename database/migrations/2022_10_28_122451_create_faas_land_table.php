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
            $table->id();
            $table->string('ARPNo')->unique();
            $table->string('pin')->nullable();
            $table->string('transactionCode')->nullable();
            $table->string('octTctNo')->nullable();
            $table->string('lotNo')->nullable();
            $table->string('blkNo')->nullable();
            $table->string('previousOwnerId')->nullable();
            $table->string('primaryOwnerId');
            $table->string('ownerAddress')->nullable();
            $table->string('ownerTelephoneNo')->nullable();
            $table->string('administratorId')->nullable();
            $table->string('administratorAddress')->nullable();
            $table->string('administratorTelephoneNo')->nullable();
            $table->string('streetId')->nullable();
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
        Schema::dropIfExists('faas_lands');
    }
};
