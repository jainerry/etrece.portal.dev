<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\CitizenProfile;
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
        'primaryOwner',
        'ownerAddress',
        'ownerTelephoneNo',
        'ownerTin',
        'administrator',
        'administratorAddress',
        'administratorTelephoneNo',
        'administratorTin',
        'noOfStreet',
        'barangay',
        'city',
        'province',
        'landOwner',
        'buildingOwner',
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

    // public function citizen_profile(){
    //     return $this->belongsTo(CitizenProfile::class,'primaryOwner','id');
    // }

    // public function machinery_owner(){
    //     return $this->belongsToMany(CitizenProfile::class,'faas_machinery_secondary_owners','citizen_profile_id','machinery_profile_id');
    // }

    public function citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'primaryOwner','id');
    }

    public function machinery_owner(){
        return $this->belongsToMany(CitizenProfile::class,'faas_machinery_secondary_owners','citizen_profile_id','machinery_profile_id');
    }

    // public function property_appraisal(){
    //     return $this->hasMany(FaasMachineryPropertyAppraisals::class);
    // }

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
