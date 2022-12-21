<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FaasBuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faasBuildings = [
            [
                'id' => '980767a1-d3a1-4c6c-8977-10871af0da63',
                'ARPNo' => 'ARP-BLDG'.'-'.str_pad((0), 5, "0", STR_PAD_LEFT),
                'refID' => 'BUILDING'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'primary_owner' => '4fa638ad-0ae0-400d-ac9c-5c7af12f4949',
                'ownerAddress'=>'Lot 16 Blk. 1, KPHOMES I, Brgy. Cabezas, Trece Martires',
                'tel_no' => '456-0001',
                'owner_tin_no' => '000-111-111-111',
                'administrator' => 'KPHOMES I Admistrator',
                'admin_address' => 'KPHOMES I, Brgy. Cabezas, Trece Martires',
                'admin_tel_no'=>'456-0002',
                'admin_tin_no' => '000-222-222-222',
                'landProfileId' => '98074d71-16bf-4b19-a9ad-d91786682ab1',
                'kind_of_building_id' => '68b4f189-7cd1-4215-ae3b-b87af9e674c0',
                'buildingAge' => '2 years',
                'structural_type_id'=>'9e5e6b7b-9d7f-4fcb-a917-bd1d5dc31b89',
                'building_permit_no' => '0000-1111-01',
                'building_permit_date_issued' => '2022-10-30',
                'condominium_certificate_of_title' => 'Test Title Only',
                'certificate_of_completion_issued_on' => '2022-10-31',
                'certificate_of_occupancy_issued_on'=>'2022-11-01',
                'date_constructed' => '2022-11-02',
                'date_occupied' => '2022-11-03',
                'no_of_storeys' => '2',
                'floorsArea'=>'[{"floorNo_fake":"Floor 1","floorNo":"1","area":"60.00"},{"floorNo_fake":"Floor 2","floorNo":"2","area":"60.00"}]',
                'totalFloorArea' => '120.00',
                'roof' => '59a04888-8fae-4b9d-bc6a-3e0ec8bfd8bb',
                'flooring' => '[{"floorNo_fake":"Floor 1","floorNo":"1","type":"8afb8b4b-cbaa-4c00-bb40-be34aaa08787","others":null},{"floorNo_fake":"Floor 2","floorNo":"2","type":"ac013c6a-6c57-41f3-8be9-07782e0cd4ff","others":null}]',
                'walling'=>'[{"floorNo_fake":"Floor 1","floorNo":"1","type":"61804b4b-a76d-4537-bcf9-4a2da54820d1","others":null},{"floorNo_fake":"Floor 2","floorNo":"2","type":"61804b4b-a76d-4537-bcf9-4a2da54820d1","others":null}]',
                'additionalItems'=>'[{"additionalItem1":"item1","additionalItem2":"desc1","additionalItem3":"desc2","additionalItem4":"desc3"}]',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980768bb-f2c7-4cae-97f7-401b1dfb97f1',
                'ARPNo' => 'ARP-BLDG'.'-'.str_pad((1), 5, "0", STR_PAD_LEFT),
                'refID' => 'BUILDING'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'primary_owner' => '7c6c6272-0dd2-49f8-8dc5-f9fd43da78d2',
                'ownerAddress'=>'Lot 11 Blk. 5, KPHOMES III, Brgy. De Ocampo, Trece Martires',
                'tel_no' => '456-0013',
                'owner_tin_no' => '000-111-111-122',
                'administrator' => 'KPHOMES III Admistrator',
                'admin_address' => 'KPHOMES III, Brgy. De Ocampo, Trece Martires',
                'admin_tel_no'=>'456-0014',
                'admin_tin_no' => '000-111-111-132',
                'landProfileId' => '980760e0-6d4f-467c-b8fb-4ee39466a3ce',
                'kind_of_building_id' => '68b4f189-7cd1-4215-ae3b-b87af9e674c0',
                'buildingAge' => '1 year',
                'structural_type_id'=>'9e5e6b7b-9d7f-4fcb-a917-bd1d5dc31b89',
                'building_permit_no' => '0000-1111-02',
                'building_permit_date_issued' => '2022-11-07',
                'condominium_certificate_of_title' => 'Test Title Only',
                'certificate_of_completion_issued_on' => '2022-11-08',
                'certificate_of_occupancy_issued_on'=>'2022-11-09',
                'date_constructed' => '2022-11-10',
                'date_occupied' => '2022-11-11',
                'no_of_storeys' => '2',
                'floorsArea'=>'[{"floorNo_fake":"Floor 1","floorNo":"1","area":"66.00"},{"floorNo_fake":"Floor 2","floorNo":"2","area":"66.00"}]',
                'totalFloorArea' => '132.00',
                'roof' => 'cae6ad7e-7ffe-4a36-8e37-29975d707bd1',
                'flooring' => '[{"floorNo_fake":"Floor 1","floorNo":"1","type":"af30a517-986a-47da-9f8d-83d644eee1e0","others":null},{"floorNo_fake":"Floor 2","floorNo":"2","type":"ca67ea97-3115-4cf2-ad76-bb9c4424bf61","others":null}]',
                'walling'=>'[{"floorNo_fake":"Floor 1","floorNo":"1","type":"4d3b65ab-8a24-469d-ae30-2a63638b4161","others":null},{"floorNo_fake":"Floor 2","floorNo":"2","type":"61804b4b-a76d-4537-bcf9-4a2da54820d1","others":null}]',
                'additionalItems'=>'[{"additionalItem1":"item1","additionalItem2":"desc1","additionalItem3":"desc2","additionalItem4":"desc3"}]',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('faas_building_profiles')->insert($faasBuildings);

    }
}
