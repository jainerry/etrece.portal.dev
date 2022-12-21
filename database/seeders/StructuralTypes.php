<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StructuralType;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class StructuralTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StructuralType::insert([
            [
            'id' => '9e5e6b7b-9d7f-4fcb-a917-bd1d5dc31b89',
            'refID' => 
            'STRUC-TYPE-'.str_pad((0), 4, "0", STR_PAD_LEFT),
            'name'=>'Residential Bldg',
            'created_at' => Carbon::now()]
        ]);
    }
}
