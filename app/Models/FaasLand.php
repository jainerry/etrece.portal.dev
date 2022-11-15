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
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FaasLand extends Model
{
    use CrudTrait;
    use HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'faas_lands';
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

    public function getLandAppraisal(){
        if(!empty($this->landAppraisal && is_array($this->landAppraisal))){
            $html = '<div class="row">';
            // "classification" => "RES"
            // "subClass" => null
            // "actualUse" => "1"
            // "area" => "46.00"
            // "unitValue" => "900.00"
            // "baseMarketValue" => "41,400.00"
            foreach($this->landAppraisal as $landAppraisal) {
                $html .= '<div class="col-md-6">Kind Of Machinery</div>';
                $html .= '<div class="col-md-6">'.$landAppraisal['classification'].'</div>';
                
            }
            $html .= '</div>';
            $html = '';
            return $html;
        }
        else {
            return "-";
        }
    }

    public function getOtherImprovements(){
        if(!empty($this->otherImprovements) && is_array($this->otherImprovements)){
            $html = '<div class="row">';
            // "kind" => null
            // "totalNumber" => null
            // "unitValue" => "0.00"
            // "baseMarketValue" => "0.00"
            foreach($this->otherImprovements as $otherImprovements) {
                $html .= '<div class="col-md-6">Kind Of Machinery</div>';
                $html .= '<div class="col-md-6">'.$otherImprovements['kind'].'</div>';
                
            }
            $html .= '</div>';
            $html = '';
            return $html;
        }
        else {
            return "-";
        }
    }

    public function getMarketValue(){
        if(!empty($this->marketValue) && is_array($this->marketValue)){
            $html = '<div class="row">';
            // "baseMarketValue" => "0.00"
            // "adjustmentFactor" => null
            // "adjustmentFactorPercentage" => null
            // "valueAdjustment" => "0.00"
            // "marketValue" => "0.00"
            foreach($this->marketValue as $marketValue) {
                $html .= '<div class="col-md-6">Kind Of Machinery</div>';
                $html .= '<div class="col-md-6">'.$marketValue['baseMarketValue'].'</div>';
                
            }
            $html .= '</div>';
            $html = '';
            return $html;
        }
        else {
            return "-";
        }
    }

    public function getPropertyAssessment(){
        if(!empty($this->propertyAssessment) && is_array($this->propertyAssessment)){
            $html = '<div class="row">';
            // "actualUse" => "1"
            // "marketValue" => "41,400.00"
            // "assessmentLevel" => "20%"
            // "assessmentValue" => "8,280.00"
            foreach($this->propertyAssessment as $propertyAssessment) {
                $html .= '<div class="col-md-6">Kind Of Machinery</div>';
                $html .= '<div class="col-md-6">'.$propertyAssessment['actualUse'].'</div>';
                
            }
            $html .= '</div>';
            $html = '';
            return $html;
        }
        else {
            return "-";
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

    public function land_owner(){
        return $this->belongsToMany(CitizenProfile::class,'faas_land_secondary_owners','citizen_profile_id','land_profile_id');
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
        $destination_path = "public/uploads/images/faas_land/land-sketches";

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
