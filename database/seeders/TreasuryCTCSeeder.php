<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TreasuryCTCSeeder extends Seeder
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
                'id' => '980c465c-0f0b-46bd-9834-bc5b86e45a4a',
                'refID' => 'TRS-CTC'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'orNo' => 'CTC-OR'.'-'.str_pad((0), 6, "0", STR_PAD_LEFT),
                'ctcNumber' => '9795-5666-55',
                'ctcType'=>'4a89ee8c-0aae-426e-83ec-998e25692724',
                'dateOfIssue' => '2022-12-23',
                'individualProfileId' => '7c6c6272-0dd2-49f8-8dc5-f9fd43da78d2',
                'businessProfileId' => '980ae821-b775-4f24-b658-1061b82432fb',
                'employmentStatus' => 'Employed',
                'annualIncome' => '489,000.00',
                'profession' => 'Teacher',
                'fees' => '[{"particulars":"980b1076-c365-4d66-8cbc-2a4bf31ef013","amount":"2,890.00"},{"particulars":"980b1089-8a4d-49cc-bf94-1cfc0f0632fd","amount":"1,480.00"}]',
                'totalFeesAmount' => '4,370.00',
                'remarks' => 'Test',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980c4700-da8e-41be-8f14-ff1d08157981',
                'refID' => 'TRS-CTC'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'orNo' => 'CTC-OR'.'-'.str_pad((1), 6, "0", STR_PAD_LEFT),
                'ctcNumber' => '9795-5666-56',
                'ctcType'=>'be9ba3b9-e6e8-46fa-828b-b57efd92a83a',
                'dateOfIssue' => '2022-12-23',
                'individualProfileId' => '55e25338-a90b-466d-b942-4121caab9687',
                'businessProfileId' => '980ae678-1d57-4a0f-8724-cdc752c54a81',
                'employmentStatus' => 'Employed',
                'annualIncome' => '905,000.00',
                'profession' => 'Developer',
                'fees' => '[{"particulars":"980b1076-c365-4d66-8cbc-2a4bf31ef013","amount":"2,300.00"},{"particulars":"980b1089-8a4d-49cc-bf94-1cfc0f0632fd","amount":"1,400.00"},{"particulars":"980b10d5-ff42-46ed-9f8a-6a17e544d053","amount":"1,250.00"}]',
                'totalFeesAmount' => '4,950.00',
                'remarks' => 'Test',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('treasury_ctcs')->insert($treasury);

    }
}
