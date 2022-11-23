<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Models\FaasMachinery;
use App\Models\BuildingProfile;
use App\Models\FaasLand;
use App\Models\FaasAssessmentStatus;

/**
 * Class RPTController
 * @package App\Http\Controllers\Admin
 * 
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
        return view('rpt.assessment-requests');
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
            ]
        ];

        return view('rpt.new-assessment-request', $this->data);
    }

    /**
     * Open rpt machinery assessment requests.
     *
     * @return \Illuminate\View\View
     */
    public function viewMachinery($id)
    {
        $this->data['requestData'] = FaasMachinery::where('id','=',$id)
            ->with('citizen_profile', function ($query) {
                $query->select('id','fName','mName','lName');
            })
            ->with('assessment_status', function ($query) {
                $query->select('id','name');
            })
            ->get();
        $this->data['assessmentStatuses'] = $this->assessmentStatuses;

        return view('rpt.view-assessment-request', $this->data);
    }

    /**
     * Open rpt building assessment requests.
     *
     * @return \Illuminate\View\View
     */
    public function viewBuilding($id)
    {
        $this->data['requestData'] = BuildingProfile::find($id);
        $this->data['assessmentStatuses'] = $this->assessmentStatuses;

        return view('rpt.view-assessment-request', $this->data);
    }

    /**
     * Open rpt land assessment requests.
     *
     * @return \Illuminate\View\View
     */
    public function viewLand($id)
    {
        $this->data['requestData'] = FaasLand::find($id);
        $this->data['assessmentStatuses'] = $this->assessmentStatuses;

        return view('rpt.view-assessment-request', $this->data);
    }

}
