<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class NameProfiles extends Model
{
    use CrudTrait;
    use HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'name_profiles';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $appends = ['full_name'];

    
    protected static function boot(){
        parent::boot();

        NameProfiles::creating(function($model){
            $count = NameProfiles::count();
            $refID = 'BUSSNAME'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $model->refID = $refID;
        });
    }


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
    public function municipality(){
        return $this->belongsTo(Municipality::class,"municipality_id","id");
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
    public function getFullNameAttribute(){
        $fName = ucfirst($this->first_name)." ";
        $mName = ($this->middle_name == null? "":" ").ucfirst($this->middle_name)." ";
        $lName = ucfirst($this->last_name);
        $suffix = ($this->suffix == null || $this->suffix == ""? "":" ").ucfirst($this->suffix);
        return "{$fName}{$mName}{$lName}{$suffix}";
    }

    protected function sex(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>( $value == 1? 'Male':'Female'),
        );
    }
    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>( $value == 'Y'? 'Active':'Inactive'),
        );
    }
}
