<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BusinessTaxAssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $items = [
            [
                'id' => '980b0670-857e-4bd5-aa22-e5cb895adb49',
                'refID' => 'BUS-TAX-ASSESSMENT'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'application_type' => 'New',
                'business_profiles_id' => '980ae821-b775-4f24-b658-1061b82432fb',
                'assessment_date' => '2022-11-27',
                //'assessment_year' => '2023',
                'assessment_year' => 'Renewal',
                'payment_type' => 'Annual',
                'net_profit' => '[{"net_profit": "1393049"}]',
                'fees_and_delinquency' => '[{"business_tax_fees":"01gn25k8rvemaxbzk7yxjdm526","taxFeesID":null,"amount":"500"}]',
                'tax_withheld_discount' => '[{"tax_withheld_discount":"Tax Withheld","remarks":"tax withheld","amount":null},{"tax_withheld_discount":"Discount","remarks":"discount","amount":null}]',
                'remarks' => 'Test Only',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980b0800-6b68-4ce6-8b80-d44f46c5f471',
                'refID' => 'BUS-TAX-ASSESSMENT'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'application_type' => 'New',
                'business_profiles_id' => '980ae678-1d57-4a0f-8724-cdc752c54a81',
                'assessment_date' => '2022-11-27',
                'assessment_year' => 'Renewal',
                'payment_type' => 'Annual',
                'net_profit' => '[{"net_profit": null}]',
                'fees_and_delinquency' => '',
                'tax_withheld_discount' => '[{"tax_withheld_discount":"Tax Withheld","remarks":"tax withheld","amount":null},{"tax_withheld_discount":"Discount","remarks":"discount","amount":null}]',
                'remarks' => 'Test Only',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('buss_tax_assessments')->insert($items);

    }


}
