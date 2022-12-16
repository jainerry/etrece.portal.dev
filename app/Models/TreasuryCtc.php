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
        'details' => 'array',
    ];

    protected static function boot(){
        parent::boot();
        TreasuryCtc::creating(function($model){
            $count = TreasuryCtc::count();
            $refID = 'TRS-CTC'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $model->refID = $refID;

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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'citizenId','id');
    }

    public function business_profile(){
        return $this->belongsTo(BusinessProfiles::class,'businessId','id');
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
