<?php

namespace App\Http\Controllers\Admin;
use App\Models\FaasMachinery;
use Illuminate\Http\Request;

/**
 * Class RPTController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RPTController
{

    /**
     * Display rpt assessment requests.
     *
     * @return \Illuminate\View\View
     */
    public function assessmentRequests()
    {
        $this->data['faasMachineries'] = FaasMachinery::all();

        return view('rpt.assessment-requests', $this->data);
    }

    /**
     * New RPT Assessment Request Creation Page.
     *
     * @return \Illuminate\View\View
     */
    public function newAssessmentRequest()
    {
        $this->data['creationOptions'] = [
            [
                'value' => 'BuildingProfile',
                'text' => 'Building'
            ],
            [
                'value' => 'FaasMachinery',
                'text' => 'Machinery'
            ],
            [
                'value' => 'FaasLand',
                'text' => 'Land'
            ],
            [
                'value' => 'FaasLandIdle',
                'text' => 'Idle Land'
            ],
            [
                'value' => 'FaasOther',
                'text' => 'Other'
            ]
        ];

        return view('rpt.new-assessment-request', $this->data);
    }

    /**
     * Define what happens when the api - /api/rpt/machineries/search - has been called
     *
     * @return void
     */
    public function machineriesSearch(Request $request)
    {
       
    }

    

}
