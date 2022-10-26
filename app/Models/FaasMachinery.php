<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\CitizenProfile;
use App\Models\Employee;
//use App\Models\FaasMachinerySecondaryOwners;
//use Backpack\CRUD\app\Models\Traits\HasIdentifiableAttribute;
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
        'assessedBy',
        'assessedDate',
        'recommendingPersonel',
        'recommendingApprovalDate',
        'approvedBy',
        'approvedDate',
        'memoranda',
        'recordOfAssesmentEntryDate',
        'recordingPersonel',
        'TDNo'
    ];

    

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'primaryOwnerId','id');
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

    public function administrator(){
        return $this->belongsTo(Employee::class, 'administratorId', 'id');
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
