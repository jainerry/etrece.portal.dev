<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BusinessProfileSeeder extends Seeder
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
                'id' => '980ae678-1d57-4a0f-8724-cdc752c54a81',
                'refID' => 'BUS-ID'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'business_name' => 'SM Prime Holdings Incorporated',
                'owner_id' => '55e25338-a90b-466d-b942-4121caab9687',
                'main_office_address' => '98074d71-16bf-4b19-a9ad-d91786682ab1',
                'property_owner' => 'Y',
                'lessor_name' => '55e25338-a90b-466d-b942-4121caab9687',
                'tel' => '046-8954-565',
                'mobile' => '09554231445',
                'email' => 'john_doe@gmail.com',
                'tin' => '4938-4985-666',
                'buss_type' => '980ae37d-5ad7-455f-8e69-d949ba8319b8',
                'corp_type' => '0',
                'trade_name_franchise' => 'SM Prime Holdings INC.',
                'business_activity_id' => '980ae411-d83b-4379-935a-e70898172055',
                'other_buss_type' => 'Others',
                'buss_activity_address_id' => '98074d71-16bf-4b19-a9ad-d91786682ab1',
                'same_as_head_office' => '1',
                'sec_no' => '345354-34694',
                'sec_reg_date' => '2022-11-27',
                'dti_no' => '324-56464674-5',
                'dti_reg_date' => '2022-11-28',
                'tax_incentives' => 'Y',
                'certificate' => 'bussprofile/39ed618a60ae0fc6080e5d8d50880aef.pdf',
                'line_of_business' => '[{"capital": "459,458.00", "particulars": "063d4f5f-2c9d-4a0d-8c74-96a7a820521a"}]',
                'number_of_employees' => '[{"sex": "1", "number": "100"}]',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980ae821-b775-4f24-b658-1061b82432fb',
                'refID' => 'BUS-ID'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'business_name' => 'Juan Sterling Corporation',
                'owner_id' => '7c6c6272-0dd2-49f8-8dc5-f9fd43da78d2',
                'main_office_address' => '98074d71-16bf-4b19-a9ad-d91786682ab1',
                'property_owner' => 'Y',
                'lessor_name' => '7c6c6272-0dd2-49f8-8dc5-f9fd43da78d2',
                'tel' => '0568-5495',
                'mobile' => '09478595855',
                'email' => 'john_doe2@gmail.com',
                'tin' => '3434-3434-34343',
                'buss_type' => '980ae350-6e48-4700-ac4d-8c4f3be9a9b0',
                'corp_type' => '0',
                'trade_name_franchise' => 'Juan Sterling Corporation',
                'business_activity_id' => '980ae453-de4e-4222-9922-34a8fd37413f',
                'other_buss_type' => 'Others',
                'buss_activity_address_id' => '98074d71-16bf-4b19-a9ad-d91786682ab1',
                'same_as_head_office' => '1',
                'sec_no' => '34-494056-44',
                'sec_reg_date' => '2022-11-27',
                'dti_no' => '454-456965-556',
                'dti_reg_date' => '2022-11-28',
                'tax_incentives' => 'Y',
                'certificate' => 'bussprofile/850c5def6e0a6b8cfba8e1c158cf1e83.pdf',
                'line_of_business' => '[{"capital": "1393049", "particulars": "e223e31d-984a-4147-a6bc-ade3a5a61984"}]',
                'number_of_employees' => '[{"sex": "1", "number": "200"}, {"sex": "0", "number": "10"}]',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('business_profiles')->insert($items);

    }


}
