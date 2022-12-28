<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Barangay;
use App\Models\BuildingProfile;
use App\Models\FaasMachinery;
use Searchab;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected static function boot(){
        parent::boot();

        CitizenProfile::creating(function($model){
            $count = CitizenProfile::count();
            $refID = 'CITIZEN'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $model->refID = $refID;
        });
    }


    public function getFullNameAttribute(){
        $fName = ucfirst($this->fName)." ";
        $mName = ($this->mName == null? "":ucfirst($this->mName)." ");
        $lName = ucfirst($this->lName);
        $suffix = ($this->suffix == null || $this->suffix == ""? "":" ").ucfirst($this->suffix);
        return "{$fName}{$mName}{$lName}{$suffix}";
    }

    public function getAddressWithBaranggay(){
        return trim($this->address." ".$this->address." ".$this->barangay->name);
    }
   
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    
    public function barangay(){
        return $this->belongsTo(Barangay::class, 'brgyID', 'id');
    }
    
    public function building_profile(){
        return $this->hasMany(Building::class);
    }

    public function machinery_profile(){
        return $this->hasMany(FaasMachinery::class);
    }
    public function street(){
        return $this->belongsTo(Street::class);
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

    // protected function sex(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) =>( $value == 1? 'Male':'Female'),
    //     );
    // }

    // protected function isActive(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) =>( $value == 'Y'? 'Active':'Inactive'),
    //     );
    // }
}
