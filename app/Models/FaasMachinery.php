<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\CitizenProfile;
use App\Models\Employee;
use App\Models\FaasAssessmentStatus;

class FaasMachinery extends Model
{
    use CrudTrait;
    //use HasIdentifiableAttribute;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'faas_machineries';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
        'propertyAppraisal' => 'array',
        'propertyAssessment' => 'array'
    ];

    protected $fillable = [
        'ARPNo',
        'pin',
        'transactionCode',
        'primaryOwnerId',
        'ownerAddress',
        'ownerTelephoneNo',
        'ownerTin',
        'administrator',
        'administratorAddress',
        'administratorTelephoneNo',
        'administratorTin',
        'streetId',
        'barangayId',
        'cityId',
        'provinceId',
        'landOwnerId',
        'buildingOwnerId',
        'landOwnerPin',
        'buildingOwnerPin',
        'propertyAppraisal',
        'propertyAssessment',
        'assessmentType',
        'assessmentEffectivity',
        'assessmentEffectivityValue',
        'assessedBy',
        'assessedDate',
        'recommendingPersonel',
        'recommendingApprovalDate',
        'approvedBy',
        'approvedDate',
        'memoranda',
        'recordOfAssesmentEntryDate',
        'recordingPersonel',
        'TDNo',
        'assessmentStatusId',
    ];

    

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getStatus(){
        if($this->isActive === 'Y'){
            return "Active";
        }
        else {
            return "InActive";
        }
    }

    public function getPropertyAppraisal(){
        if(!empty($this->propertyAppraisal && is_array($this->propertyAppraisal))){
            $html = '<div class="row">';
            foreach($this->propertyAppraisal as $propertyAppraisal) {
                $html .= '<div class="col-md-6">Kind Of Machinery</div>';
                $html .= '<div class="col-md-6">'.$propertyAppraisal['kindOfMachinery'].'</div>';
                
            }
            $html .= '</div>';
            $html = '';
            return $html;
        }
        else {
            return "-";
        }
    }

    public function getPropertyAssessment(){
        if(!empty($this->propertyAssessment) && is_array($this->propertyAssessment)){
            $html = '<div class="row">';
            foreach($this->propertyAppraisal as $propertyAppraisal) {
                $html .= '<div class="col-md-6">Kind Of Machinery</div>';
                $html .= '<div class="col-md-6">'.$propertyAppraisal['kindOfMachinery'].'</div>';
                
            }
            $html .= '</div>';
            $html = '';
            return $html;
        }
        else {
            return "-";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'primaryOwnerId','id');
    }

    public function land_owner_citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'landOwnerId','id');
    }

    public function building_owner_citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'buildingOwnerId','id');
    }

    public function machinery_owner(){
        return $this->belongsToMany(CitizenProfile::class,'faas_machinery_secondary_owners','citizen_profile_id','machinery_profile_id');
    }

    public function street(){
        return $this->belongsTo(Street::class, 'streetId','id');
    }

    public function barangay(){
        return $this->belongsTo(Barangay::class, 'barangayId','id');
    }

    public function municipality(){
        return $this->belongsTo(Municipality::class, 'cityId', 'id');
    }

    public function province(){
        return $this->belongsTo(Province::class, 'provinceId', 'id');
    }

    public function administrator_profile(){
        return $this->belongsTo(Employee::class, 'administratorId', 'id');
    }

    public function assessment_status(){
        return $this->belongsTo(FaasAssessmentStatus::class, 'assessmentStatusId', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
