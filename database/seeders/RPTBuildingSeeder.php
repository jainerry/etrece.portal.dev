<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RPTBuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rptBuildings = [
            [
                'id' => '980912f8-e555-4f7a-9e20-8bee25bd551d',
                'refID' => 'RPT-BLDG'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'faasId' => '980767a1-d3a1-4c6c-8977-10871af0da63',
                'unitConstructionCost' => '150,000.00',
                'unitConstructionSubTotal' => '1,900,345.00',
                'costOfAdditionalItemsSubTotal' => '20,456.00',
                'totalConstructionCost' => '2,070,801.00',
                'depreciationRate'=>'5%',
                'depreciationCost' => '212,213.20',
                'totalPercentDepreciation' => '5%',
                'marketValue' => '2,069,608.00',
                'transactionCode' => '',
                'propertyAssessment'=>'[{"actualUse":"68b4f189-7cd1-4215-ae3b-b87af9e674c0","actualUse_fake":"RES","marketValue":"2,069,608.00","assessmentLevel":"50%","assessmentValue":"1,034,804.00","yearOfEffectivity":"2023"}]',
                'totalPropertyAssessmentMarketValue' => '2,069,608.00',
                'totalPropertyAssessmentAssessmentValue' => '1,034,804.00',
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
                'TDNo'=>'TD-BLDG'.'-'.str_pad((0), 6, "0", STR_PAD_LEFT),
                'isApproved'=>'1',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98091960-5133-46bd-b623-db986860ec7f',
                'refID' => 'RPT-BLDG'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'faasId' => '980768bb-f2c7-4cae-97f7-401b1dfb97f1',
                'unitConstructionCost' => '165,000.00',
                'unitConstructionSubTotal' => '1,355,678.00',
                'costOfAdditionalItemsSubTotal' => '356,578.00',
                'totalConstructionCost' => '1,877,256.00',
                'depreciationRate'=>'6%',
                'depreciationCost' => '34,546.00',
                'totalPercentDepreciation' => '6%',
                'marketValue' => '1,842,710.00',
                'transactionCode' => '',
                'propertyAssessment'=>'[{"actualUse":"68b4f189-7cd1-4215-ae3b-b87af9e674c0","actualUse_fake":"RES","marketValue":"1,842,710.00","assessmentLevel":"50%","assessmentValue":"921,355.00","yearOfEffectivity":"2023"}]',
                'totalPropertyAssessmentMarketValue' => '1,842,710.00',
                'totalPropertyAssessmentAssessmentValue' => '921,355.00',
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
                'TDNo'=>'TD-BLDG'.'-'.str_pad((1), 6, "0", STR_PAD_LEFT),
                'isApproved'=>'1',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('rpt_buildings')->insert($rptBuildings);

    }
}
