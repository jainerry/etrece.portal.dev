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

    protected $table = 'building_profiles';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

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
        return $this->belongsToMany(CitizenProfile::class,'building_owners','citizen_profile_id','building_profile_id');
    }
    public function municipality(){
        return $this->belongsTo(Municipality::class, 'municipality_id', 'id');
    }
    public function province(){
        return $this->belongsTo(Province::class, 'province_id', 'id');
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
