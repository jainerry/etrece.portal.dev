<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class FaasAssessmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $assessmentStatuses = [
            'Request for Assessment',
            'Preparing Requirements',
            'Verifying Requirements',
            'Field Inspection',
            'FAAS Creation',
            'Preparing Tax Declaration & Notice of Assessment',
            'Preparing Assessment Role',
            'Assessment Received',
        ];
        $inputs = [];
        
        foreach($assessmentStatuses as $index =>$assessmentStatus){
            array_push($inputs,[
                'name'=>$assessmentStatus,
                'created_at'=>Carbon::now()
            ]);
        }

        DB::table('faas_assessment_statuses')->insert($inputs);

    }


}
