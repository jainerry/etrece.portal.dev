<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
    protected $casts = [
        'line_of_business' => 'array',
        'number_of_employee' => 'array',
    ];



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
            $model->refID = $refID;
        });
        BusinessProfiles::deleting(function ($obj) {
            Storage::disk('public')->delete($obj->certificate);
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
    public function  main_office(){
        return $this->BelongsTo(FaasLand::class, "main_office_address", "id");
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
    public function bus_act_address(){
       return  $this->belongsTo(FaasLand::class, "buss_activity_address_id", "id");
    }
    public function businessCategory(){
        return  $this->belongsTo(BusinessCategory::class, "line_of_business->particulars", "id");
     }
     public function vehicleType(){
        return  $this->belongsTo(BusinessVehicles::class, "line_of_business->vehicles", "id");
     }
    public function setCertificateAttribute($value)
    {
        $attribute_name = "certificate";
        $disk = "public";
        $destination_path = "bussprofile";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);

    // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }
    public function getOwner(){
        if($this->names()->get()->first() == null){
            return $this->owner()->get()->first()->full_name;
        }else{
            return $this->names()->get()->first()->full_name;
        }   
    }
    public function getFullAddress(){
        $mainOffice = $this->main_office()->get()->first();
        if($mainOffice != null ){
            return trim($mainOffice->lotNo." ".$mainOffice->noOfStreet." ".$mainOffice->barangay->name);
        }
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
