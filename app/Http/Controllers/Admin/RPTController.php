<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Models\FaasMachinery;
use App\Models\BuildingProfile;
use App\Models\FaasLand;
use App\Models\FaasLandIdle;
use App\Models\FaasOther;
use App\Models\FaasAssessmentStatus;

/**
 * Class RPTController
 * @package App\Http\Controllers\Admin
 * 
 */
class RPTController
{

    public function __construct()
    {
        $this->assessmentStatuses = FaasAssessmentStatus::all();
    }

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
     * Open rpt machinery assessment requests.
     *
     * @return \Illuminate\View\View
     */
    public function viewMachinery($id)
    {
        $this->data['requestData'] = FaasMachinery::where('id','=',$id)->get();
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

    /**
     * Open rpt idle-land assessment requests.
     *
     * @return \Illuminate\View\View
     */
    public function viewIdleLand($id)
    {
        $this->data['requestData'] = FaasLandIdle::find($id);
        $this->data['assessmentStatuses'] = $this->assessmentStatuses;

        return view('rpt.view-assessment-request', $this->data);
    }

    /**
     * Open rpt other assessment requests.
     *
     * @return \Illuminate\View\View
     */
    public function viewOther($id)
    {
        $this->data['requestData'] = FaasOther::find($id);
        $this->data['assessmentStatuses'] = $this->assessmentStatuses;

        return view('rpt.view-assessment-request', $this->data);
    }

}
