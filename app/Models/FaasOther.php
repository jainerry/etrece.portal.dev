<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\CitizenProfile;
use App\Models\Employee;
use App\Models\Street;
use App\Models\Barangay;
use Illuminate\Support\Str;
use App\Models\FaasAssessmentStatus;

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

    protected $casts = [
        'landAppraisal' => 'array',
        'otherImprovements' => 'array',
        'marketValue' => 'array',
        'propertyAssessment' => 'array'
    ];

    protected $fillable = [
        'ARPNo',
        'pin',
        'transactionCode',
        'octTctNo',
        'lotNo',
        'blkNo',
        'previousOwnerId',
        'primaryOwnerId',
        'ownerAddress',
        'ownerTelephoneNo',
        'administrator',
        'administratorAddress',
        'administratorTelephoneNo',
        'noOfStreet',
        'barangayId',
        'cityId',
        'provinceId',
        'propertyBoundaryNorth',
        'propertyBoundaryEast',
        'propertyBoundarySouth',
        'propertyBoundaryWest',
        'landSketch',
        'landAppraisal',
        'otherImprovements',
        'marketValue',
        'propertyAssessment',
        'assessmentType',
        'assessmentEffectivity',
        'assessmentEffectivityValue',
        'assessedBy',
        'assessedDate',
        'recommendingPersonel',
        'recommendingApprovalDate',
        'approvedBy',
        'approvedDate',
        'memoranda',
        'recordOfAssesmentEntryDate',
        'recordingPersonel',
        'TDNo',
        'assessmentStatusId'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getStatus(){
        if($this->isActive === 'Y'){
            return "Active";
        }
        else {
            return "InActive";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'primaryOwnerId','id');
    }

    public function old_owner_citizen_profile(){
        return $this->belongsTo(CitizenProfile::class,'oldOwnerId','id');
    }

    public function other_owner(){
        return $this->belongsToMany(CitizenProfile::class,'faas_other_secondary_owners','citizen_profile_id','other_profile_id');
    }

    public function barangay(){
        return $this->belongsTo(Barangay::class, 'barangayId','id');
    }

    public function municipality(){
        return $this->belongsTo(Municipality::class, 'cityId', 'id');
    }

    public function province(){
        return $this->belongsTo(Province::class, 'provinceId', 'id');
    }

    public function assessment_status(){
        return $this->belongsTo(FaasAssessmentStatus::class, 'assessmentStatusId', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function setLandSketchAttribute($value)
    {
        $attribute_name = "landSketch";
        // or use your own disk, defined in config/filesystems.php
        $disk = config('backpack.base.root_disk_name');
        // destination path relative to the disk above
        $destination_path = "public/uploads/images/faas_other/land-sketches";

        // if the image was erased
        if (empty($value)) {
            // delete the image from disk
            if (isset($this->{$attribute_name}) && !empty($this->{$attribute_name})) {
                \Storage::disk($disk)->delete($this->{$attribute_name});  
            }
            // set null on database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (Str::startsWith($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);

            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';

            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());

            // 3. Delete the previous image, if there was one.
            if (isset($this->{$attribute_name}) && !empty($this->{$attribute_name})) {
                \Storage::disk($disk)->delete($this->{$attribute_name});
            }

            // 4. Save the public path to the database
            // but first, remove "public/" from the path, since we're pointing to it
            // from the root folder; that way, what gets saved in the db
            // is the public URL (everything that comes after the domain name)
            $public_destination_path = Str::replaceFirst('public/', '', $destination_path);
            $this->attributes[$attribute_name] = $public_destination_path.'/'.$filename;
        } elseif (!empty($value)) {
            // if value isn't empty, but it's not an image, assume it's the model value for that attribute.
            $this->attributes[$attribute_name] = $this->{$attribute_name};
        }
    }

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
