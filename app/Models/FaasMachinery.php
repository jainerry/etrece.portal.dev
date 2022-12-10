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

    // protected $casts = [
    //     'propertyAppraisal' => 'array',
    //     'propertyAssessment' => 'array'
    // ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getPrimaryOwner()
    {
        $ownerExist = CitizenProfile::where("id", $this->primaryOwnerId)->count();
        if ($ownerExist == 0) {
            $primaryOwner = NameProfiles::where("id", $this->primaryOwnerId)->first();
            $first_name = $primaryOwner->first_name;
            $middle_name = $primaryOwner->middle_name;
            $last_name = $primaryOwner->last_name;
            $suffix = $primaryOwner->suffix;
        }
        else {
            $primaryOwner = CitizenProfile::where("id", $this->primaryOwnerId)->first();
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

    public function citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'primaryOwnerId','id');
    }

    public function name_profile(){
        return $this->belongsTo(NameProfiles::class,'primaryOwnerId','id');
    }

    public function faas_land_profile(){
        return $this->belongsTo(FaasLand::class,'landProfileId','id');
    }

    public function faas_building_profile(){
        return $this->belongsTo(BuildingProfile::class,'buildingProfileId','id');
    }
    
    public function land_owner_citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'landOwnerId','id');
    }

    public function building_owner_citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'buildingOwnerId','id');
    }

    public function machinery_owner(){
        return $this->belongsToMany(CitizenProfile::class,'faas_machinery_secondary_owners','machinery_profile_id','citizen_profile_id');
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

    public function building_profile(){
        return $this->belongsTo(BuildingProfile::class,'buildingProfileId','id');
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
