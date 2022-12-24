<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessTaxFees;


class BusTaxFeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        BusinessTaxFees::insert([
            ["id"=>"01gn25k8rvemaxbzk7yxjdm526","refID"=>"BUSS-TAX-FEES-0000","business_fees_id"=>"980b1076-c365-4d66-8cbc-2a4bf31ef013",
            "effective_date"=>"2022-12-24","chart_of_accounts_lvl4_id"=>"12e8ad86-bb40-4285-9c4a-a1b0b9becfad","business_categories_id"=>"e223e31d-984a-4147-a6bc-ade3a5a61984","basis"=>"Capital/Net Profit",
            "type"=>"Range","range_box"=>'[{"to": "1000", "pp1": null, "pp2": null, "from": "0.1", "PAmount": null, "infinite": "0"}]',
            "computation"=>"Amount","amount_value"=>"500","isActive"=>"Y"]
        ]);
    }
}
