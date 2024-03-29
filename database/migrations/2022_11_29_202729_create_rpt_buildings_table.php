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
        Schema::create('rpt_buildings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('refID')->unique();
            $table->string('faasId')->nullable();

            $table->string('unitConstructionCost')->nullable();
            $table->string('unitConstructionSubTotal')->nullable();
            $table->string('costOfAdditionalItemsSubTotal')->nullable();
            $table->string('totalConstructionCost')->nullable();
            $table->string('depreciationRate')->nullable();
            $table->string('depreciationCost')->nullable();
            $table->string('totalPercentDepreciation')->nullable();
            $table->string('marketValue')->nullable();

            $table->string('transactionCode')->nullable();
            $table->text('propertyAssessment')->nullable();

            $table->string('totalPropertyAssessmentMarketValue')->nullable();
            $table->string('totalPropertyAssessmentAssessmentValue')->nullable();
            
            $table->string('assessmentType')->nullable();
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
        Schema::dropIfExists('rpt_buildings');
    }
};
