<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FaasLandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faasLands = [
            [
                'id' => '98074d71-16bf-4b19-a9ad-d91786682ab1',
                'ARPNo' => 'ARP-LAND'.'-'.str_pad((0), 5, "0", STR_PAD_LEFT),
                'refID' => 'LAND'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'pin' => '0000-000001',
                'octTctNo'=>'0000-000011',
                'survey_no' => '0000-000111',
                'lotNo' => '16',
                'blkNo' => '1',
                'primaryOwnerId' => '4fa638ad-0ae0-400d-ac9c-5c7af12f4949',
                'ownerAddress'=>'Lot 16 Blk. 1, KPHOMES I, Brgy. Cabezas, Trece Martires',
                'ownerTelephoneNo' => '456-0001',
                'ownerTinNo' => '000-111-111-111',
                'administrator' => 'KPHOMES I Admistrator',
                'administratorAddress' => 'KPHOMES I, Brgy. Cabezas, Trece Martires',
                'administratorTelephoneNo'=>'456-0002',
                'administratorTinNo' => '000-222-222-222',
                'noOfStreet' => 'KPHOME PH I',
                'barangayId' => '04c43c7a-1329-40eb-9420-acd72b13fc0b',
                'cityId' => 'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'provinceId'=>'eb9e8c56-957b-4084-b5ae-904054d2a1b3',
                'propertyBoundaryNorth' => 'N- Road Lot 16',
                'propertyBoundaryEast' => 'E- Lot 16, Blk. 1',
                'propertyBoundarySouth' => 'S- Road Lot 16',
                'propertyBoundaryWest' => 'W- Lot 16, Blk. 1',
                'totalArea'=>'120.00',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '9807530e-d573-4d12-b918-c1f70d952a82',
                'ARPNo' => 'ARP-LAND'.'-'.str_pad((1), 5, "0", STR_PAD_LEFT),
                'refID' => 'LAND'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'pin' => '0000-000002',
                'octTctNo'=>'0000-000012',
                'survey_no' => '0000-000112',
                'lotNo' => '21',
                'blkNo' => '2',
                'primaryOwnerId' => 'b969444e-9e09-4945-949a-69f1a6278ceb',
                'ownerAddress'=>'Lot 21 Blk. 2, KPHOMES II, Brgy. Lapidario, Trece Martires',
                'ownerTelephoneNo' => '456-0003',
                'ownerTinNo' => '000-111-111-112',
                'administrator' => 'KPHOMES II Admistrator',
                'administratorAddress' => 'KPHOMES II, Brgy. Lapidario, Trece Martires',
                'administratorTelephoneNo'=>'456-0004',
                'administratorTinNo' => '000-222-222-223',
                'noOfStreet' => 'KPHOME PH II',
                'barangayId' => '0eaaf54b-8ab2-47a9-8590-35fabd240421',
                'cityId' => 'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'provinceId'=>'eb9e8c56-957b-4084-b5ae-904054d2a1b3',
                'propertyBoundaryNorth' => 'N- Road Lot 21',
                'propertyBoundaryEast' => 'E- Lot 21, Blk. 2',
                'propertyBoundarySouth' => 'S- Road Lot 21',
                'propertyBoundaryWest' => 'W- Lot 21, Blk. 2',
                'totalArea'=>'125.00',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980760e0-6d4f-467c-b8fb-4ee39466a3ce',
                'ARPNo' => 'ARP-LAND'.'-'.str_pad((2), 5, "0", STR_PAD_LEFT),
                'refID' => 'LAND'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'pin' => '0000-000003',
                'octTctNo'=>'0000-000013',
                'survey_no' => '0000-000113',
                'lotNo' => '11',
                'blkNo' => '5',
                'primaryOwnerId' => '7c6c6272-0dd2-49f8-8dc5-f9fd43da78d2',
                'ownerAddress'=>'Lot 11 Blk. 5, KPHOMES III, Brgy. De Ocampo, Trece Martires',
                'ownerTelephoneNo' => '456-0013',
                'ownerTinNo' => '000-111-111-122',
                'administrator' => 'KPHOMES III Admistrator',
                'administratorAddress' => 'KPHOMES III, Brgy. De Ocampo, Trece Martires',
                'administratorTelephoneNo'=>'456-0014',
                'administratorTinNo' => '000-111-111-132',
                'noOfStreet' => 'KPHOME PH III',
                'barangayId' => '1e5c14c8-d0fe-49f2-81a8-99395768290f',
                'cityId' => 'db3510e6-3add-4d81-8809-effafbbaa6fd',
                'provinceId'=>'eb9e8c56-957b-4084-b5ae-904054d2a1b3',
                'propertyBoundaryNorth' => 'N- Road Lot 11',
                'propertyBoundaryEast' => 'E- Lot 11, Blk. 5',
                'propertyBoundarySouth' => 'S- Road Lot 11',
                'propertyBoundaryWest' => 'W- Lot 11, Blk. 5',
                'totalArea'=>'132.00',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('faas_lands')->insert($faasLands);

    }
}
