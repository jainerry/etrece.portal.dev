<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\CitizenProfile;
use Illuminate\Support\Facades\DB;

class RptBuildings extends Model
{
    use CrudTrait;
    use HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'rpt_buildings';
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

        $citizenProfiles = BuildingProfile::select('faas_building_profiles.*', 'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'))
        ->join('citizen_profiles', 'faas_building_profiles.primary_owner', '=', 'citizen_profiles.id')
        ->with('citizen_profile')
        ->where('faas_building_profiles.isActive', '=', '1')
        ->where('faas_building_profiles.id', '=', $faasId)
        ->get();

        $nameProfiles = BuildingProfile::select('faas_building_profiles.*', 'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'))
        ->join('name_profiles', 'faas_building_profiles.primary_owner', '=', 'name_profiles.id')
        ->with('name_profile')
        ->where('faas_building_profiles.isActive', '=', '1')
        ->where('faas_building_profiles.id', '=', $faasId)
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
        return $this->belongsTo(CitizenProfile::class,'primary_owner','id');
    }

    public function building_owner(){
        return $this->belongsToMany(CitizenProfile::class,'faas_building_profile_secondary_owners','citizen_profile_id','building_profile_id');
    }

    public function barangay(){
        return $this->belongsTo(Barangay::class, 'barangay_id','id');
    }

    public function structural_type(){
        return $this->belongsTo(StructuralType::class, 'structural_type_id', 'id');
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
