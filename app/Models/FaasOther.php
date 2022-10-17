<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class FaasOther extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'faas_others';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $fillable = [
        'ARPNo',
        'pin',
        'transactionCode',
        'octTctNo',
        'lotNo',
        'blkNo',
        'previousOwner',
        'primaryOwner',
        'secondaryOwners',
        'ownerAddress',
        'ownerTelephoneNo',
        'administrator',
        'administratorAddress',
        'administratorTelephoneNo',
        'noOfStreet',
        'barangay',
        'city',
        'province',
        'propertyBoundaries',
        'landSketch',
        'landAppraisal',
        'otherImprovements',
        'marketValue',
        'propertyAssessment',
        'assessmentType',
        'assessmentEffectivity',
        'assessedBy',
        'assessedDate',
        'recommendingPersonel',
        'recommendingApprovalDate',
        'approvedBy',
        'approvedDate',
        'memoranda',
        'recordOfAssesmentEntryDate',
        'recordingPersonel',
        'TDNo'
    ];

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
}
