<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Yajra\Address\Entities\Barangay;
use App\Models\BuildingProfile;
use App\Models\BuildingOwner;

class CitizenProfile extends Model
{
    use CrudTrait;

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
 
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute(){
        $fName = ucfirst($this->fName);
        $mName = ucfirst($this->mName);
        $lName = ucfirst($this->lName);
        return "{$fName}  {$mName} {$lName}";
    }
    public function getFullNameWithIdAttribute(){
        $fName = ucfirst($this->fName);
        $mName = ucfirst($this->mName);
        $lName = ucfirst($this->lName);
        return "{$fName}  {$mName} {$lName} - {$this->refID}";
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function barangay(){
        return $this->hasOne(Barangay::class, 'id', 'brgyID');
    }
    
    public function building_owner(){
        return $this->belongsTo(BuildingOwner::class);
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
