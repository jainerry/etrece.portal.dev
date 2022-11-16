<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\CitizenProfile;
use App\Models\BuildingOwner;
use Backpack\CRUD\app\Models\Traits\HasIdentifiableAttribute;
use GuzzleHttp\Psr7\Request;


class BuildingProfile extends Model
{
    use CrudTrait;
    use HasIdentifiableAttribute;
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

    public function building_owner(){
        return $this->belongsToMany(CitizenProfile::class,'building_owners','citizen_profile_id','building_profile_id');
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
    public function kind_of_building(){
        return $this->belongsTo(KindOfBuilding::class, 'kind_of_building_id', 'id');
    }
    public function roof(){
        return $this->belongsToMany(StructuralRoofs::class,HasRoofs::class,'faas_building_profiles_id','structural_roofs_id');
    }
    public function flooring(){
        return $this->belongsToMany(StructuralFlooring::class,HasFlooring::class,'faas_building_profiles_id','structural_flooring_id');
    }
    public function walling(){
        return $this->belongsToMany(StructuralWalling::class,HasWalling::class,'faas_building_profiles_id','structural_walling_id');
    }
    public function additional_items(){
        return $this->hasMany(StructuralAdditionalItems::class);
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