<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\TransactionLogs;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TreasuryRpt extends Model
{
    use CrudTrait;
    use HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'treasury_rpts';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
        'otherFees' => 'array',
        'summary' => 'array',
    ];

    protected static function boot(){
        parent::boot();
        TreasuryRpt::creating(function($model){
            $count = TreasuryRpt::count();
            
            $refID = 'TRS-RPT'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $orNo = 'RPT-OR'.'-'.str_pad(($count), 6, "0", STR_PAD_LEFT);
            
            $model->refID = $refID;
            $model->orNo = $orNo;

            TransactionLogs::create([
                'transId' =>$refID,
                'category' =>'treasury_rpt',
                'type' =>'create',
            ]);
        });

        TreasuryRpt::updating(function($entry) {
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'treasury_rpt',
                'type' =>'update',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getRPT(){
        $rptId = $this->rptId;
        $rptType = $this->rptType;

        $results = null;

        if($rptType === 'Land'){
            $results = RptLands::select('id', 'refID', 'faasId', 'TDNo')
                ->with('faas_land_profile')
                ->where('isActive', '=', '1')
                ->where('id', '=', $rptId)
                ->get();
        }
        else if($rptType === 'Building'){
            $results = RptBuildings::select('id', 'refID', 'faasId', 'TDNo')
                ->with('faas_building_profile')
                ->where('isActive', '=', '1')
                ->where('id', '=', $rptId)
                ->get();
        }
        else if($rptType === 'Machinery'){
            $results = RptMachineries::select('id', 'refID', 'faasId', 'TDNo')
                ->with('faas_machinery_profile')
                ->where('isActive', '=', '1')
                ->where('id', '=', $rptId)
                ->get();
        }
        
        return $results;
    }

    public function getOwnerDetails($primaryOwnerId){
        $citizenProfile = CitizenProfile::select('id', 'fName', 'mName', 'lName', 'suffix', DB::raw('"CitizenProfile" as ownerType'))->where('id', '=', $primaryOwnerId)->get();
        $nameProfile = NameProfiles::select('id', 'first_name', 'middle_name', 'last_name', 'suffix', DB::raw('"NameProfile" as ownerType'))->where('id', '=', $primaryOwnerId)->get();
        $results = $citizenProfile->merge($nameProfile);

        return $results;
    }

    public function getPrimaryOwner(){
        $primaryOwner = '';
        $rptId = $this->rptId;
        $rptType = $this->rptType;

        $rptResults = $this->getRPT();
        $rptProfile = json_decode($rptResults);

        $primaryOwnerId = '';

        if($rptType === 'Land'){
            if(isset($rptProfile[0]->faas_land_profile)) {
                $faas_land_profile = $rptProfile[0]->faas_land_profile;
                $primaryOwnerId = $faas_land_profile->primaryOwnerId;
            }
        }
        else if($rptType === 'Building'){
            if(isset($rptProfile[0]->faas_building_profile)) {
                $faas_building_profile = $rptProfile[0]->faas_building_profile;
                $primaryOwnerId = $faas_building_profile->primary_owner;
            }
        }
        else if($rptType === 'Machinery'){
            if(isset($rptProfile[0]->faas_machinery_profile)) {
                $faas_machinery_profile = $rptProfile[0]->faas_machinery_profile;
                $primaryOwnerId = $faas_machinery_profile->primaryOwnerId;
            }
        }

        if($primaryOwnerId !== '') {
            $ownerDetails = $this->getOwnerDetails($primaryOwnerId);

            if(isset($ownerDetails[0])) {
                if($ownerDetails[0]->ownerType === 'CitizenProfile') {
                    $primaryOwner = $ownerDetails[0]->fName." ".$ownerDetails[0]->mName." ".$ownerDetails[0]->lName." ".$ownerDetails[0]->suffix;
                }
                else if($ownerDetails[0]->ownerType === 'NameProfile') {
                    $primaryOwner = $ownerDetails[0]->first_name." ".$ownerDetails[0]->middle_name." ".$ownerDetails[0]->last_name." ".$ownerDetails[0]->suffix;
                }
            }
        }

        return $primaryOwner;
    }

    public function getAddress(){
        $address = '';
        $rptId = $this->rptId;
        $rptType = $this->rptType;

        $rptResults = $this->getRPT();
        $rptProfile = json_decode($rptResults);

        if($rptType === 'Land'){
            if(isset($rptProfile[0]->faas_land_profile)) {
                $faas_land_profile = $rptProfile[0]->faas_land_profile;
                $address = $faas_land_profile->ownerAddress;
            }
        }
        else if($rptType === 'Building'){
            if(isset($rptProfile[0]->faas_building_profile)) {
                $faas_building_profile = $rptProfile[0]->faas_building_profile;
                $address = $faas_building_profile->ownerAddress;
            }
        }
        else if($rptType === 'Machinery'){
            if(isset($rptProfile[0]->faas_machinery_profile)) {
                $faas_machinery_profile = $rptProfile[0]->faas_machinery_profile;
                $address = $faas_machinery_profile->ownerAddress;
            }
        }

        return $address;
    }

    public function getRPTTDNo(){
        $TDNo = '';
        $rptId = $this->rptId;
        $rptType = $this->rptType;

        $rptResults = $this->getRPT();
        $rptProfile = json_decode($rptResults);

        if(isset($rptProfile[0])) {
            $TDNo = $rptProfile[0]->TDNo;
        }

        return $TDNo;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function rpt_land_assessment(){
        return $this->belongsTo(RptLands::class,'rptId','id');
    }

    public function rpt_building_assessment(){
        return $this->belongsTo(RptBuildings::class,'rptId','id');
    }

    public function rpt_machinery_assessment(){
        return $this->belongsTo(RptMachineries::class,'rptId','id');
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
