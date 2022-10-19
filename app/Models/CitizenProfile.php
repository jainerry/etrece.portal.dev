<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Barangay;
use App\Models\BuildingProfile;
use App\Models\BuildingOwner;
use App\Models\FaasMachinery;

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
    public function getEntryDataAttribute(){
        $fName = ucfirst($this->fName);
        $mName = ucfirst($this->mName);
        $lName = ucfirst($this->lName);
       
        $baranggay = ($this->barangay == null) ? $this->barangay:$this->barangay->name;
        return "{$fName}  {$mName} {$lName} - {$this->refID} - {$baranggay}";
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
        return $this->hasMany(BuildingProfile::class);
    }

    public function machineryPrimaryOwner(){
        return $this->belongsTo(FaasMachinery::class, 'id', 'primaryOwner');
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
