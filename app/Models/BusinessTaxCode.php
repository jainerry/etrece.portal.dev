<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BusinessTaxCode extends Model
{
    use CrudTrait;
    USE HasUuids;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'business_tax_codes';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected static function boot(){
        parent::boot();

        BusinessTaxCode::creating(function($model){
            $count = BusinessTaxCode::count();
            $refID = 'BUS-TAX-CODE'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
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
    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>( $value == 'Y'? 'Active':'Inactive'),
        );
    }
}
