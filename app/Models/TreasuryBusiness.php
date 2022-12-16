<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\TransactionLogs;

class TreasuryBusiness extends Model
{
    use CrudTrait;
    use HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'treasury_businesses';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
        'otherFees' => 'array',
        'details' => 'array',
    ];

    protected static function boot(){
        parent::boot();
        TreasuryBusiness::creating(function($model){
            $count = TreasuryBusiness::count();
            $refID = 'TRS-BUSS'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $model->refID = $refID;

            TransactionLogs::create([
                'transId' =>$refID,
                'category' =>'treasury_business',
                'type' =>'create',
            ]);
        });

        TreasuryBusiness::updating(function($entry) {
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'treasury_business',
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
