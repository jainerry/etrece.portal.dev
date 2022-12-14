<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class BussTaxAssessments extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'buss_tax_assessments';
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
    protected static function boot(){
        parent::boot();

        BussTaxAssessments::creating(function($model){
            $count = BussTaxAssessments::count();
            $refID = 'BUSS-TAX-ASSESSMENT'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $model->refID = $refID;
        });
    }



    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function bussType(){
        return $this->belongsTo(BusinessType::class, "application_type", "id");
    }
    public function bussProf(){
        return $this->belongsTo(BusinessProfiles::class, "business_profiles_id", "id");
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
