<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BusinessTaxFeesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('business_tax_fees')->delete();
        
        \DB::table('business_tax_fees')->insert(array (
            0 => 
            array (
                'amount_value' => '500',
                'basis' => '05',
                'business_categories_id' => 'e223e31d-984a-4147-a6bc-ade3a5a61984',
                'business_fees_id' => '98150643-c44b-4cf1-b634-b502bf646c85',
                'chart_of_accounts_lvl4_id' => '85ff7d7c-af68-42fe-9767-19d3b01b4757',
                'computation' => '01',
                'created_at' => '2022-12-28 01:09:08',
                'effective_date' => '2022-12-28',
                'id' => '01gna8ybx6q65ey2kdxt5r1cht',
                'isActive' => 'Y',
                'range_box' => '[{"to": null, "pp1": null, "pp2": null, "from": null, "PAmount": null, "infinite": "0"}]',
                'refID' => 'BUSS-TAX-FEES-0000',
                'type' => '01',
                'updated_at' => '2022-12-28 01:09:08',
                'vehicle_type' => '980edebd-bdae-4679-8f2c-295c8449ae48',
            ),
            1 => 
            array (
                'amount_value' => '100',
                'basis' => '03',
                'business_categories_id' => 'e223e31d-984a-4147-a6bc-ade3a5a61984',
                'business_fees_id' => '98150602-2d7a-477f-ba1d-556c3149cec7',
                'chart_of_accounts_lvl4_id' => '85ff7d7c-af68-42fe-9767-19d3b01b4757',
                'computation' => '01',
                'created_at' => '2022-12-28 01:09:41',
                'effective_date' => '2022-12-28',
                'id' => '01gna8zcqsgcxezr9cwy5mgc78',
                'isActive' => 'Y',
                'range_box' => '[{"to": null, "pp1": null, "pp2": null, "from": null, "PAmount": null, "infinite": "0"}]',
                'refID' => 'BUSS-TAX-FEES-0001',
                'type' => '01',
                'updated_at' => '2022-12-28 01:09:41',
                'vehicle_type' => NULL,
            ),
            2 => 
            array (
                'amount_value' => '500',
                'basis' => '01',
                'business_categories_id' => 'e223e31d-984a-4147-a6bc-ade3a5a61984',
                'business_fees_id' => '9815052f-16a0-4ae5-8efa-90aa40973cb0',
                'chart_of_accounts_lvl4_id' => '85ff7d7c-af68-42fe-9767-19d3b01b4757',
                'computation' => '02',
                'created_at' => '2022-12-28 01:10:23',
                'effective_date' => '2022-12-28',
                'id' => '01gna90nrtr42970ccr0dfw4w8',
                'isActive' => 'Y',
                'range_box' => '[{"to": "1000", "pp1": "84", "pp2": "1", "from": "0.1", "PAmount": "45000", "infinite": "0"}]',
                'refID' => 'BUSS-TAX-FEES-0002',
                'type' => '02',
                'updated_at' => '2022-12-28 01:10:23',
                'vehicle_type' => NULL,
            ),
            3 => 
            array (
                'amount_value' => '600',
                'basis' => '05',
                'business_categories_id' => 'c886e9ab-06e6-4498-9f03-d62152dfa47e',
                'business_fees_id' => '98150643-c44b-4cf1-b634-b502bf646c85',
                'chart_of_accounts_lvl4_id' => '85ff7d7c-af68-42fe-9767-19d3b01b4757',
                'computation' => '01',
                'created_at' => '2022-12-28 01:11:09',
                'effective_date' => '2022-12-28',
                'id' => '01gna9227xhb25cwdvg2npr79r',
                'isActive' => 'Y',
                'range_box' => '[{"to": null, "pp1": null, "pp2": null, "from": null, "PAmount": null, "infinite": "0"}]',
                'refID' => 'BUSS-TAX-FEES-0003',
                'type' => '01',
                'updated_at' => '2022-12-28 01:11:09',
                'vehicle_type' => '980edebd-bdae-4679-8f2c-295c8449ae48',
            ),
            4 => 
            array (
                'amount_value' => '500',
                'basis' => '03',
                'business_categories_id' => 'c886e9ab-06e6-4498-9f03-d62152dfa47e',
                'business_fees_id' => '98150602-2d7a-477f-ba1d-556c3149cec7',
                'chart_of_accounts_lvl4_id' => '85ff7d7c-af68-42fe-9767-19d3b01b4757',
                'computation' => '01',
                'created_at' => '2022-12-28 01:11:31',
                'effective_date' => '2022-12-28',
                'id' => '01gna92qj311tm9z2vchyg8f3d',
                'isActive' => 'Y',
                'range_box' => '[{"to": null, "pp1": null, "pp2": null, "from": null, "PAmount": null, "infinite": "0"}]',
                'refID' => 'BUSS-TAX-FEES-0004',
                'type' => '01',
                'updated_at' => '2022-12-28 01:11:31',
                'vehicle_type' => NULL,
            ),
        ));
        
        
    }
}