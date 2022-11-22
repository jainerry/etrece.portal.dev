<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class BusinessProfiles extends Model
{
    use CrudTrait;
    use HasUuids;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'business_profiles';
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
        return "{$this->business_name}";
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot(){
        parent::boot();

        BusinessProfiles::creating(function($model){
            $count = BusinessProfiles::count();
            $refID = 'BUSID'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $model->buss_id = $refID;
        });
    }

    public function owner(){
        return $this->belongsTo(CitizenProfile::class,'owner_cid','id');
    }
    public function main_land(){
        return $this->belongsTo(FaasLand::class,'main_land_id','id');
    }
    public function municipality(){
        return $this->belongsTo(Municipality::class,"city","id");
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
