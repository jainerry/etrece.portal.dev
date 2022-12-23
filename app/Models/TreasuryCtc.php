<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\TransactionLogs;

class TreasuryCtc extends Model
{
    use CrudTrait;
    use HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'treasury_ctcs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
        'fees' => 'array',
    ];

    protected static function boot(){
        parent::boot();
        TreasuryCtc::creating(function($model){
            $count = TreasuryCtc::count();

            $refID = 'TRS-CTC'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $orNo = 'CTC-OR'.'-'.str_pad(($count), 6, "0", STR_PAD_LEFT);

            $model->refID = $refID;
            $model->orNo = $orNo;

            TransactionLogs::create([
                'transId' =>$refID,
                'category' =>'treasury_ctc',
                'type' =>'create',
            ]);
        });

        TreasuryCtc::updating(function($entry) {
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'treasury_ctc',
                'type' =>'update',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getNameProfile()
    {
        $ownerExist = CitizenProfile::where("id", $this->individualProfileId)->count();
        if ($ownerExist == 0) {
            $primaryOwner = NameProfiles::where("id", $this->individualProfileId)->first();
            $first_name = $primaryOwner->first_name;
            $middle_name = $primaryOwner->middle_name;
            $last_name = $primaryOwner->last_name;
            $suffix = $primaryOwner->suffix;
        }
        else {
            $primaryOwner = CitizenProfile::where("id", $this->individualProfileId)->first();
            $first_name = $primaryOwner->fName;
            $middle_name = $primaryOwner->mName;
            $last_name = $primaryOwner->lName;
            $suffix = $primaryOwner->suffix;
        }

        $fName = ucfirst($first_name)." ";
        $mName = ($middle_name == null? "":" ").ucfirst($middle_name)." ";
        $lName = ucfirst($last_name);
        $suffix = ($suffix == null || $suffix == ""? "":" ").ucfirst($suffix);
        
        return "{$fName}{$mName}{$lName}{$suffix}";
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'individualProfileId','id');
    }

    public function business_profile(){
        return $this->belongsTo(BusinessProfiles::class,'businessProfileId','id');
    }

    public function name_profile(){
        return $this->belongsTo(NameProfiles::class,'individualProfileId','id');
    }

    public function ctc_type(){
        return $this->belongsTo(CtcType::class,'ctcType','id');
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

    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>( $value == '1'? 'Active':'Inactive'),
        );
    }
}
