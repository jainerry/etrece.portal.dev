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
        'octTctNo',
        'transactionCode',
        'primaryOwnerId',
        'ownerAddress',
        'ownerTelephoneNo',
        'ownerTin',
        'administrator',
        'administratorAddress',
        'administratorTelephoneNo',
        'administratorTin',
        'noOfStreet',
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
            // "kindOfMachinery" => "Mixed machineries"
            // "brandModel" => "test only"
            // "capacity" => "test only"
            // "dateAcquired" => "2019"
            // "conditionWhenAcquired" => "New"
            // "economicLifeEstimated" => "12"
            // "economicLifeRemain" => "11"
            // "yearInstalled" => "2019"
            // "yearOfInitialOperation" => "2019"
            // "originalCost" => "454,567.00"
            // "conversionFactor" => "test only"
            // "rcn" => "test only"
            // "noOfYearsUsed" => "2"
            // "rateOfDepreciation" => "10%"
            // "totalDepreciationPercentage" => "10%"
            // "totalDepreciationValue" => "10,000.00"
            // "depreciatedValue" => "14,566.00"
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
            // "actualUse" => "1"
            // "marketValue" => "35,345.00"
            // "assessmentLevel" => "34%"
            // "assessedValue" => "12,345.00"
            // "yearOfEffectivity" => "2023"
            foreach($this->propertyAssessment as $propertyAssessment) {
                $html .= '<div class="col-md-6">Kind Of Machinery</div>';
                $html .= '<div class="col-md-6">'.$propertyAssessment['actualUse'].'</div>';
                
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

    public function barangay(){
        return $this->belongsTo(Barangay::class, 'barangayId','id');
    }

    public function municipality(){
        return $this->belongsTo(Municipality::class, 'cityId', 'id');
    }

    public function province(){
        return $this->belongsTo(Province::class, 'provinceId', 'id');
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
