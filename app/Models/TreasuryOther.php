<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\TransactionLogs;

class TreasuryOther extends Model
{
    use CrudTrait;
    use HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'treasury_others';
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
        TreasuryOther::creating(function($model){
            $count = TreasuryOther::count();

            $refID = 'TRS-OTHR'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $orNo = 'OTHR-OR'.'-'.str_pad(($count), 6, "0", STR_PAD_LEFT);

            $model->refID = $refID;
            $model->orNo = $orNo;

            TransactionLogs::create([
                'transId' =>$refID,
                'category' =>'treasury_other',
                'type' =>'create',
            ]);
        });

        TreasuryOther::updating(function($entry) {
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'treasury_other',
                'type' =>'update',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getPayee(){
        $payee = "";
        
        $first_name = '';
        $middle_name = '';
        $last_name = '';
        $suffix = '';

        if($this->citizenProfileId !== '') {
            $payeeData = CitizenProfile::where("id", $this->citizenProfileId)->first();
            $first_name = $payeeData->fName;
            $middle_name = $payeeData->mName;
            $last_name = $payeeData->lName;
            $suffix = $payeeData->suffix;
            $payee = $first_name." ".$middle_name." ".$last_name." ".$suffix;
        }
        else if($this->nameProfileId !== '') {
            $payeeData = NameProfiles::where("id", $this->nameProfileId)->first();
            $first_name = $payeeData->first_name;
            $middle_name = $payeeData->middle_name;
            $last_name = $payeeData->last_name;
            $suffix = $payeeData->suffix;
            $payee = $first_name." ".$middle_name." ".$last_name." ".$suffix;
        }
        else if($this->businessAssessmentId !== '') {
            $payeeData = BussTaxAssessments::where("id", $this->businessAssessmentId)->first();
            $businessData = BusinessProfiles::where("id", $payeeData->business_profiles_id)->first();
            $payee = $businessData->business_name;
        }

        return $payee;
    }

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
