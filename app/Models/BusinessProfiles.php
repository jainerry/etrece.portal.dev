<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
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
            $refID = 'BUS-ID'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $model->buss_id = $refID;
        });
    }

    public function owner(){
        $citizenProfile = $this->belongsTo(CitizenProfile::class,'owner_id','id');
        return $citizenProfile;
       
    }
    public function lessor_owner(){
        $citizenProfile = $this->belongsTo(CitizenProfile::class,'lessor_name','id');
        return $citizenProfile;
       
    }
    public function  names(){
            return $this->belongsTo(NameProfiles::class,'owner_id','id');
    }
    public function main_land(){
        return $this->belongsTo(FaasLand::class,'main_land_id','id');
    }
    public function municipality(){
        return $this->belongsTo(Municipality::class,"city","id");
    }
    public function category(){
        return $this->belongsTo(BusinessCategory::class,'category_id',"id");
    }
    public function bus_type(){
        return $this->belongsTo(BusinessType::class,"buss_type","id");
    }
    public function bus_activity(){
        return $this->belongsTo(BusinessActivity::class,"business_activity_id","id");
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
