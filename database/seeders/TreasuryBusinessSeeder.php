<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TreasuryBusinessSeeder extends Seeder
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
                'id' => '980bf097-473b-44df-8093-d774a1a111fc',
                'refID' => 'TRS-BUSS'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'orNo' => 'BUSS-OR'.'-'.str_pad((0), 6, "0", STR_PAD_LEFT),
                'businessTaxAssessmentId' => '980b0800-6b68-4ce6-8b80-d44f46c5f471',
                'otherFees'=>'[{"particulars":"980b10d5-ff42-46ed-9f8a-6a17e544d053","amount":"5,444.00"},{"particulars":"980b11dc-67f1-4824-b8bf-a52b0848576a","amount":"200.00"}]',
                'totalOtherFeesAmount' => '5,644.00',
                'totalSummaryAmount' => '40,894.00',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980bf31e-1e13-4537-a45b-7c9374fbbf6e',
                'refID' => 'TRS-BUSS'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'orNo' => 'BUSS-OR'.'-'.str_pad((1), 6, "0", STR_PAD_LEFT),
                'businessTaxAssessmentId' => '980b0670-857e-4bd5-aa22-e5cb895adb49',
                'otherFees'=>'[{"particulars":"980b10d5-ff42-46ed-9f8a-6a17e544d053","amount":"3,890.00"},{"particulars":"980b1156-1914-4258-9b07-bc2d85560fc0","amount":"1,000.00"}]',
                'totalOtherFeesAmount' => '4,890.00',
                'totalSummaryAmount' => '45,490.00',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('treasury_businesses')->insert($treasury);

    }
}
