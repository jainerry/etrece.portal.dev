<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Barangay;
use App\Models\BuildingProfile;
use App\Models\BuildingOwner;
use App\Models\FaasMachinery;
use Searchab;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CitizenProfile extends Model
{
    use CrudTrait;
    use HasUuids;
    
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'citizen_profiles';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['full_name'];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute(){
        $fName = ucfirst($this->fName)." ";
        $mName = ($this->mName == null? "":" ").ucfirst($this->mName)." ";
        $lName = ucfirst($this->lName);
        $suffix = ($this->suffix == null || $this->suffix == ""? "":" ").ucfirst($this->suffix);
        return "{$fName}{$mName}{$lName}{$suffix}";
    }
   
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function barangay(){
        return $this->hasOne(Barangay::class, 'id', 'brgyID');
    }
    
    public function building_profile(){
        return $this->hasMany(Building::class);
    }

    public function machinery_profile(){
        return $this->hasMany(FaasMachinery::class);
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
