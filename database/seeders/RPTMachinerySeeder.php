<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RPTMachinerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rptMachineries = [
            [
                'id' => '980913a7-c391-4ca6-ba2d-9d97dcdf4050',
                'refID' => 'RPT-MCHN'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'faasId' => '98079b17-58e5-4479-816b-ae988ed2b82a',
                'propertyAppraisal' => '[{"kindOfMachinery":"Mixed machineries","brandModel":"test only","capacity":"test only","dateAcquired":"2022","conditionWhenAcquired":"New","economicLifeEstimated":"1","economicLifeRemain":"1","yearInstalled":"2022","yearOfInitialOperation":"2023","originalCost":"839,393.00","conversionFactor":"test only","rcn":"test only","noOfYearsUsed":"1","rateOfDepreciation":"5%","totalDepreciationPercentage":"5%","totalDepreciationValue":"41,969.65","depreciatedValue":"797,423.35"}]',
                'propertyAssessment' => '[{"actualUse":"54ce5aa0-0da3-41cd-8e0e-cb881803cfc3","actualUse_fake":"RES","marketValue":"797,423.35","assessmentLevel":"40%","assessmentValue":"318,969.34","yearOfEffectivity":"2023"}]',
                'totalPropertyAssessmentMarketValue' => '797,423.35',
                'totalPropertyAssessmentAssessmentValue' => '318,969.34',
                'assessmentType' => 'Exempt',
                'assessmentEffectivityValue'=>'1st Quarter of 2023',
                'assessedBy' => 'John Doe',
                'assessedDate' => '2022-12-22',
                'recommendingPersonel' => 'Jane Doe',
                'recommendingApprovalDate' => '2022-12-22',
                'approvedBy'=>'Juan Dela Cruz',
                'approvedDate' => '2022-12-22',
                'memoranda' => 'Test Only',
                'recordOfAssesmentEntryDate' => '',
                'recordingPersonel' => '',
                'totalOriginalCost' => '839,393.00',
                'totalTotalDepreciationValue' => '41,969.65',
                'totalDepreciatedValue' => '797,423.35',
                'TDNo'=>'TD-MCHN'.'-'.str_pad((0), 6, "0", STR_PAD_LEFT),
                'isApproved'=>'1',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980919f5-eb48-455f-911a-0891505639c1',
                'refID' => 'RPT-MCHN'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'faasId' => '98079bcc-7b12-42b2-99ee-8a6a0c6dea7d',
                'propertyAppraisal' => '[{"kindOfMachinery":"Mixed machineries","brandModel":"test only","capacity":"test only","dateAcquired":"2022","conditionWhenAcquired":"New","economicLifeEstimated":"2","economicLifeRemain":"2","yearInstalled":"2021","yearOfInitialOperation":"2023","originalCost":"2,040,540.00","conversionFactor":"test only","rcn":"test only","noOfYearsUsed":"2","rateOfDepreciation":"7%","totalDepreciationPercentage":"14%","totalDepreciationValue":"285,675.60","depreciatedValue":"1,754,864.40"}]',
                'propertyAssessment' => '[{"actualUse":"caa8de93-17ee-4423-b3c9-89cebab513a7","actualUse_fake":"IND","marketValue":"1,754,864.40","assessmentLevel":"50%","assessmentValue":"877,432.20","yearOfEffectivity":"2023"}]',
                'totalPropertyAssessmentMarketValue' => '1,754,864.40',
                'totalPropertyAssessmentAssessmentValue' => '877,432.20',
                'assessmentType' => 'Exempt',
                'assessmentEffectivityValue'=>'1st Quarter of 2023',
                'assessedBy' => 'John Doe',
                'assessedDate' => '2022-12-22',
                'recommendingPersonel' => 'Jane Doe',
                'recommendingApprovalDate' => '2022-12-22',
                'approvedBy'=>'Juan Dela Cruz',
                'approvedDate' => '2022-12-22',
                'memoranda' => 'Test Only',
                'recordOfAssesmentEntryDate' => '',
                'recordingPersonel' => '',
                'totalOriginalCost' => '2,040,540.00',
                'totalTotalDepreciationValue' => '285,675.60',
                'totalDepreciatedValue' => '1,754,864.40',
                'TDNo'=>'TD-MCHN'.'-'.str_pad((1), 6, "0", STR_PAD_LEFT),
                'isApproved'=>'1',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('rpt_machineries')->insert($rptMachineries);

    }
}
