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
        'additionalItems' => 'array',
        'propertyAssessment' => 'array'
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
    public function barangay(){
        return $this->belongsTo(Barangay::class, 'barangay_id','id');
    }

    public function citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'primary_owner','id');
    }

    public function building_owner(){
        return $this->belongsToMany(CitizenProfile::class,'faas_building_profile_secondary_owners','citizen_profile_id','building_profile_id');
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
    public function flooring(){
        return $this->belongsTo(StructuralFlooring::class);
    }
    public function walling(){
        return $this->belongsTo(StructuralWalling::class);
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