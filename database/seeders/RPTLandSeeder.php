<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RPTLandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rptLands = [
            [
                'id' => '98090daf-750c-4ce6-bec3-6e5e5f118a32',
                'refID' => 'RPT-LAND'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'faasId' => '98074d71-16bf-4b19-a9ad-d91786682ab1',
                'landAppraisal'=>'[{"classification":"58e0a3b9-b771-49df-9e39-63e2cb9cb106","subClass":null,"actualUse":"58e0a3b9-b771-49df-9e39-63e2cb9cb106","actualUse_fake":"RES","area":"120.00","baseMarketValue":"150,000.00"}]',
                'otherImprovements' => '[{"kind":"test only","totalNumber":"10","unitValue":"2,545.00","baseMarketValue":"25,450.00"}]',
                'marketValue' => '[{"baseMarketValue":"34,040.00","adjustmentFactor":"test","adjustmentFactorPercentage":"8%","valueAdjustment":"2,723.20","marketValue":"36,763.20"}]',
                'propertyAssessment' => '[{"actualUse":"58e0a3b9-b771-49df-9e39-63e2cb9cb106","actualUse_fake":"RES","marketValue":"212,213.20","assessmentLevel":"25%","assessmentValue":"53,053.30"}]',
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
                'TDNo'=>'TD-LAND'.'-'.str_pad((0), 6, "0", STR_PAD_LEFT),
                'totalPropertyAssessmentMarketValue' => '212,213.20',
                'totalPropertyAssessmentAssessmentValue' => '53,053.30',
                'totalLandAppraisalBaseMarketValue' => '150,000.00',
                'totalOtherImprovementsBaseMarketValue' => '25,450.00',
                'totalMarketValueMarketValue'=>'36,763.20',
                'isApproved'=>'1',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98091853-8cb4-43bf-936f-8bb90afaa79b',
                'refID' => 'RPT-LAND'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'faasId' => '9807530e-d573-4d12-b918-c1f70d952a82',
                'landAppraisal'=>'[{"classification":"ce234931-ec3b-4b7e-bd95-058641fd8487","subClass":"test","actualUse":"ce234931-ec3b-4b7e-bd95-058641fd8487","actualUse_fake":"IND","area":"125.00","baseMarketValue":"156,250.00"}]',
                'otherImprovements' => '[{"kind":"test only","totalNumber":"10","unitValue":"56,565.00","baseMarketValue":"565,650.00"}]',
                'marketValue' => '[{"baseMarketValue":"173,934.00","adjustmentFactor":"test","adjustmentFactorPercentage":"5%","valueAdjustment":"8,696.70","marketValue":"182,630.70"}]',
                'propertyAssessment' => '[{"actualUse":"ce234931-ec3b-4b7e-bd95-058641fd8487","actualUse_fake":"IND","marketValue":"904,530.70","assessmentLevel":"25%","assessmentValue":"226,132.67"}]',
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
                'TDNo'=>'TD-LAND'.'-'.str_pad((1), 6, "0", STR_PAD_LEFT),
                'totalPropertyAssessmentMarketValue' => '904,530.70',
                'totalPropertyAssessmentAssessmentValue' => '226,132.67',
                'totalLandAppraisalBaseMarketValue' => '156,250.00',
                'totalOtherImprovementsBaseMarketValue' => '565,650.00',
                'totalMarketValueMarketValue'=>'182,630.70',
                'isApproved'=>'1',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980918e9-af0f-4f6a-bd0f-0af83e93340a',
                'refID' => 'RPT-LAND'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'faasId' => '980760e0-6d4f-467c-b8fb-4ee39466a3ce',
                'landAppraisal'=>'[{"classification":"ce234931-ec3b-4b7e-bd95-058641fd8487","subClass":"test","actualUse":"ce234931-ec3b-4b7e-bd95-058641fd8487","actualUse_fake":"IND","area":"132.00","baseMarketValue":"165,000.00"}]',
                'otherImprovements' => '[{"kind":"test only","totalNumber":"10","unitValue":"56,778.00","baseMarketValue":"567,780.00"}]',
                'marketValue' => '[{"baseMarketValue":"245,446.00","adjustmentFactor":"test","adjustmentFactorPercentage":"10%","valueAdjustment":"24,544.60","marketValue":"269,990.60"}]',
                'propertyAssessment' => '[{"actualUse":"ce234931-ec3b-4b7e-bd95-058641fd8487","actualUse_fake":"IND","marketValue":"1,002,770.60","assessmentLevel":"25%","assessmentValue":"250,692.65"}]',
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
                'TDNo'=>'TD-LAND'.'-'.str_pad((2), 6, "0", STR_PAD_LEFT),
                'totalPropertyAssessmentMarketValue' => '1,002,770.60',
                'totalPropertyAssessmentAssessmentValue' => '250,692.65',
                'totalLandAppraisalBaseMarketValue' => '165,000.00',
                'totalOtherImprovementsBaseMarketValue' => '567,780.00',
                'totalMarketValueMarketValue'=>'269,990.60',
                'isApproved'=>'1',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('rpt_lands')->insert($rptLands);

    }
}
