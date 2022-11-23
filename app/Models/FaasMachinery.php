<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\CitizenProfile;
use App\Models\Employee;
use App\Models\FaasAssessmentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\BusinessProfiles;

class FaasMachinery extends Model
{
    use CrudTrait;
    use HasUuids;
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

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getPrimaryOwner(){
        $citizen = CitizenProfile::find($this->primaryOwnerId);
        $business = BusinessProfiles::where("id",$this->primaryOwnerId)->first();
        if(!empty($citizen)){
            return "{$citizen->fName} {$citizen->mName} {$citizen->lName}";
        }
        else {
            return "{$business->business_name}";
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
