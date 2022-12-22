<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TreasuryRPTSeeder extends Seeder
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
                'id' => '980a9ea5-8d99-4984-aa59-cd639239bda0',
                'refID' => 'TRS-RPT'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'orNo' => 'RPT-OR'.'-'.str_pad((0), 6, "0", STR_PAD_LEFT),
                'rptId' => '98090daf-750c-4ce6-bec3-6e5e5f118a32',
                'rptType'=>'Land',
                'year' => '2023',
                'periodCovered' => 'Quarterly',
                'basic_amount' => '3,183.20',
                'basicPenalty_amount' => '0.00',
                'basicDiscount_amount'=>'0.00',
                'totalBasic_amount' => '3,183.20',
                'sef_amount' => '2,122.13',
                'sefPenalty_amount' => '0.00',
                'sefDiscount_amount' => '0.00',
                'totalSef_amount'=>'2,122.13',
                'totalSummaryAmount' => '5,305.33',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980abbed-0529-480f-8d21-d45514a31ca3',
                'refID' => 'TRS-RPT'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'orNo' => 'RPT-OR'.'-'.str_pad((1), 6, "0", STR_PAD_LEFT),
                'rptId' => '98091960-5133-46bd-b623-db986860ec7f',
                'rptType'=>'Building',
                'year' => '2023',
                'periodCovered' => 'Quarterly',
                'basic_amount' => '27,640.65',
                'basicPenalty_amount' => '0.00',
                'basicDiscount_amount'=>'0.00',
                'totalBasic_amount' => '27,640.65',
                'sef_amount' => '18,427.10',
                'sefPenalty_amount' => '0.00',
                'sefDiscount_amount' => '0.00',
                'totalSef_amount'=>'18,427.10',
                'totalSummaryAmount' => '46,067.75',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980abf9a-5bb4-4fb9-b77f-646e03a81c4d',
                'refID' => 'TRS-RPT'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'orNo' => 'RPT-OR'.'-'.str_pad((2), 6, "0", STR_PAD_LEFT),
                'rptId' => '980919f5-eb48-455f-911a-0891505639c1',
                'rptType'=>'Machinery',
                'year' => '2023',
                'periodCovered' => 'Quarterly',
                'basic_amount' => '26,322.97',
                'basicPenalty_amount' => '0.00',
                'basicDiscount_amount'=>'0.00',
                'totalBasic_amount' => '26,322.97',
                'sef_amount' => '17,548.64',
                'sefPenalty_amount' => '0.00',
                'sefDiscount_amount' => '0.00',
                'totalSef_amount'=>'17,548.64',
                'totalSummaryAmount' => '43,871.61',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('treasury_rpts')->insert($treasury);

    }
}
