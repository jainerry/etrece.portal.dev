<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TreasuryOtherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $treasury = [
            [
                'id' => '980c86af-c5f1-4906-97ec-645a5b0fc6dc',
                'refID' => 'TRS-OTHR'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'orNo' => 'OTHR-OR'.'-'.str_pad((0), 6, "0", STR_PAD_LEFT),
                'businessAssessmentId' => '',
                'citizenProfileId'=>'b969444e-9e09-4945-949a-69f1a6278ceb',
                'nameProfileId' => '',
                'type' => 'Citizen or Name',
                'fees' => '[{"particulars":"980b1089-8a4d-49cc-bf94-1cfc0f0632fd","amount":"1,689.00"},{"particulars":"980b1156-1914-4258-9b07-bc2d85560fc0","amount":"4,790.00"}]',
                'totalFeesAmount' => '6,479.00',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980c8708-8707-49a5-85b2-dbf295424c63',
                'refID' => 'TRS-OTHR'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'orNo' => 'OTHR-OR'.'-'.str_pad((1), 6, "0", STR_PAD_LEFT),
                'businessAssessmentId' => '',
                'citizenProfileId'=>'',
                'nameProfileId' => '2e71d819-b487-4e2b-a020-6ee6dbb629dd',
                'type' => 'Citizen or Name',
                'fees' => '[{"particulars":"980b1076-c365-4d66-8cbc-2a4bf31ef013","amount":"4,666.00"},{"particulars":"980b1156-1914-4258-9b07-bc2d85560fc0","amount":"4,334.00"}]',
                'totalFeesAmount' => '9,000.00',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980c874a-4b40-4a9e-addd-efdffd14d302',
                'refID' => 'TRS-OTHR'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'orNo' => 'OTHR-OR'.'-'.str_pad((2), 6, "0", STR_PAD_LEFT),
                'businessAssessmentId' => '980b0670-857e-4bd5-aa22-e5cb895adb49',
                'citizenProfileId'=>'',
                'nameProfileId' => '',
                'type' => 'Business',
                'fees' => '[{"particulars":"980b11dc-67f1-4824-b8bf-a52b0848576a","amount":"2,540.00"},{"particulars":"980b125b-2b83-4ad9-bf0e-48bd4afe4290","amount":"1,235.00"}]',
                'totalFeesAmount' => '3,775.00',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('treasury_others')->insert($treasury);

    }
}
