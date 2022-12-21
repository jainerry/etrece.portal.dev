<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FaasMachinerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faasMachineries = [
            [
                'id' => '98079b17-58e5-4479-816b-ae988ed2b82a',
                'ARPNo' => 'ARP-MCHN'.'-'.str_pad((0), 5, "0", STR_PAD_LEFT),
                'refID' => 'MACHINERY'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'pin' => '0000-000020',
                'primaryOwnerId' => '4fa638ad-0ae0-400d-ac9c-5c7af12f4949',
                'ownerAddress'=>'Lot 16 Blk. 1, KPHOMES I, Brgy. Cabezas, Trece Martires',
                'ownerTelephoneNo' => '456-0001',
                'ownerTin' => '000-111-111-111',
                'administrator' => 'KPHOMES I Admistrator',
                'administratorAddress' => 'KPHOMES I, Brgy. Cabezas, Trece Martires',
                'administratorTelephoneNo'=>'456-0002',
                'administratorTin' => '000-222-222-222',
                'landProfileId' => '98074d71-16bf-4b19-a9ad-d91786682ab1',
                'buildingProfileId' => '980767a1-d3a1-4c6c-8977-10871af0da63',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98079bcc-7b12-42b2-99ee-8a6a0c6dea7d',
                'ARPNo' => 'ARP-MCHN'.'-'.str_pad((1), 5, "0", STR_PAD_LEFT),
                'refID' => 'MACHINERY'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'pin' => '0000-000021',
                'primaryOwnerId' => '7c6c6272-0dd2-49f8-8dc5-f9fd43da78d2',
                'ownerAddress'=>'Lot 11 Blk. 5, KPHOMES III, Brgy. De Ocampo, Trece Martires',
                'ownerTelephoneNo' => '456-0013',
                'ownerTin' => '000-111-111-122',
                'administrator' => 'KPHOMES III Admistrator',
                'administratorAddress' => 'KPHOMES III, Brgy. De Ocampo, Trece Martires',
                'administratorTelephoneNo'=>'456-0014',
                'administratorTin' => '000-111-111-132',
                'landProfileId' => '980760e0-6d4f-467c-b8fb-4ee39466a3ce',
                'buildingProfileId' => '980768bb-f2c7-4cae-97f7-401b1dfb97f1',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('faas_machineries')->insert($faasMachineries);

    }
}
