<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\CitizenProfile;
use Illuminate\Support\Facades\DB;

class RptLands extends Model
{
    use CrudTrait;
    use HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'rpt_lands';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
        'propertyAssessment' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getFaas(){
        $faasId = $this->faasId;

        $citizenProfiles = FaasLand::select('faas_lands.*', 'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'))
        ->join('citizen_profiles', 'faas_lands.primaryOwnerId', '=', 'citizen_profiles.id')
        ->with('citizen_profile')
        ->where('faas_lands.isActive', '=', '1')
        ->where('faas_lands.id', '=', $faasId)
        ->get();

        $nameProfiles = FaasLand::select('faas_lands.*', 'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'))
        ->join('name_profiles', 'faas_lands.primaryOwnerId', '=', 'name_profiles.id')
        ->with('name_profile')
        ->where('faas_lands.isActive', '=', '1')
        ->where('faas_lands.id', '=', $faasId)
        ->get();

        $results = $citizenProfiles->merge($nameProfiles);
        
        return $results;
    }

    public function getPrimaryOwner(){
        $results = $this->getFaas();

        $faas = json_decode($results);
    
        $primaryOwner = '';
        if($faas[0]->citizen_profile) {
            $citizen_profile = $faas[0]->citizen_profile;
            $primaryOwner = $citizen_profile->fName.' '.$citizen_profile->mName.' '.$citizen_profile->lName;
        }
        else {
            $name_profile = $faas[0]->name_profile;
            $primaryOwner = $name_profile->first_name.' '.$name_profile->middle_name.' '.$name_profile->last_name;
        }

        return $primaryOwner;
    }

    public function getAddress(){
        $results = $this->getFaas();

        $faas = json_decode($results);
        $address = '';
        if($faas[0]->ownerAddress) {
            $address = $faas[0]->ownerAddress;
        }

        return $address;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'primaryOwnerId','id');
    }

    public function old_owner_citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'oldOwnerId','id');
    }

    public function land_owner(){
        return $this->belongsToMany(CitizenProfile::class,'faas_land_secondary_owners','land_profile_id','citizen_profile_id');
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
