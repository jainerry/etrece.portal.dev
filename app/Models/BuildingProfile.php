<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\CitizenProfile;
use App\Models\FassBuildingProfileSecondaryOwners;
use Backpack\CRUD\app\Models\Traits\HasIdentifiableAttribute;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BuildingProfile extends Model
{
    use CrudTrait;
    use HasIdentifiableAttribute;
    use HasUuids;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'faas_building_profiles';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = ['roof'];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
        'floorsArea' => 'array',
        'flooring' => 'array',
        'walling' => 'array',
        'additionalItems' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getPrimaryOwner()
    {
        $ownerExist = CitizenProfile::where("id", $this->primary_owner)->count();
        if ($ownerExist == 0) {
            $primaryOwner = NameProfiles::where("id", $this->primary_owner)->first();
            $first_name = $primaryOwner->first_name;
            $middle_name = $primaryOwner->middle_name;
            $last_name = $primaryOwner->last_name;
            $suffix = $primaryOwner->suffix;
        }
        else {
            $primaryOwner = CitizenProfile::where("id", $this->primary_owner)->first();
            $first_name = $primaryOwner->fName;
            $middle_name = $primaryOwner->mName;
            $last_name = $primaryOwner->lName;
            $suffix = $primaryOwner->suffix;
        }

        $fName = ucfirst($first_name)." ";
        $mName = ($middle_name == null? "":" ").ucfirst($middle_name)." ";
        $lName = ucfirst($last_name);
        $suffix = ($suffix == null || $suffix == ""? "":" ").ucfirst($suffix);
        
        return "{$fName}{$mName}{$lName}{$suffix}";
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function barangay(){
        return $this->belongsTo(Barangay::class, 'barangay_id','id');
    }

    public function citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'primary_owner','id');
    }

    public function faas_land_profile(){
        return $this->belongsTo(FaasLand::class,'landProfileId','id');
    }

    public function name_profile(){
        return $this->belongsTo(NameProfiles::class,'primary_owner','id');
    }

    public function building_owner(){
        return $this->belongsToMany(CitizenProfile::class,'faas_building_profile_secondary_owners','building_profile_id','citizen_profile_id');
    }

    public function municipality(){
        return $this->belongsTo(Municipality::class, 'municipality_id', 'id');
    }
    public function province(){
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }
    
    public function structural_type(){
        return $this->belongsTo(StructuralType::class, 'structural_type_id', 'id');
    }

    public function building_classification(){
        return $this->belongsTo(FaasBuildingClassifications::class, 'kind_of_building_id', 'id');
    }

    public function land_profile(){
        return $this->belongsTo(FaasLand::class,'landProfileId','id');
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