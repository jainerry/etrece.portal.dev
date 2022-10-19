<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Office;
use App\Models\Section;
use App\Models\Position;
use App\Models\Appointment;
use App\Models\Street;
use App\Models\Barangay;
use Intervention\Image\ImageManagerStatic as Image;

class Employee extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employees';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $fillable = [
        'employeeId',
        'IDNo',
        'lastName',
        'firstName',
        'middleName',
        'nickName',
        'birthDate',
        'bloodType',
        'tinNo',
        'gsisNo',
        'emergencyContactPerson',
        'emergencyContactRelationship',
        'emergencyContactNo',
        'emergencyContactAddress1',
        'emergencyContactAddress2',
        'oldIDNo',
        'isActive',
        'sectionId',
        'positionId',
        'idPicture',
        'halfPicture',
        'signature',
        'appointmentId',
        'remarks',
        'cellphoneNo',
        'suffix',
        'birthPlace',
        'civilStatus',
        'citizenShip',
        'citizenShipAcquisition',
        'dualCitizenCountry',
        'sex',
        'height',
        'weight',
        'pagibigNo',
        'philhealthNo',
        'sssNo',
        'telephoneNo',
        'email',
        'residentialAddress',
        'permanentAddress',
        'residentialBarangayId',
        'permanentBarangayId',
        'residentialStreetId',
        'permanentStreetId'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getFullName(){
        $firstName = ucfirst($this->firstName);
        $lastName = ucfirst($this->lastName);
        return "{$firstName} {$lastName}";
    }

    public function getStatus(){
        if($this->isActive === 'Y'){
            return "Active";
        }
        else {
            return "InActive";
        }
    }

    public function getSection(){
        return Section::find(1)->name;
    }

    public function getPosition(){
        return Position::find(1)->name;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function sectionHeads(){
        return $this->hasMany(Section::class);
    }

    public function officeHeads(){
        return $this->hasMany(Office::class);
    }

    public function section(){
        return $this->belongsTo(Section::class);
    }

    public function position(){
        return $this->belongsTo(Position::class);
    }

    public function office(){
        return $this->belongsTo(Office::class);
    }

    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }

    public function residentialStreet(){
        return $this->belongsTo(Street::class);
    }

    public function permanentStreet(){
        return $this->belongsTo(Street::class);
    }

    public function residentialBarangay(){
        return $this->belongsTo(Barangay::class);
    }

    public function permanentBarangay(){
        return $this->belongsTo(Barangay::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // public function setIdPictureAttribute($value)
    // {
    //     $attribute_name = "idPicture";
    //     $disk = "local";
    //     $destination_path = "/uploads/idPictures";       
    //     $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    // }

    public function setHalfPictureAttribute($value)
    {
        $attribute_name = "halfPicture";
        $disk = "local";
        $destination_path = "/uploads/halfPictures";       
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setSignatureAttribute($value)
    {
        $attribute_name = "signature";
        $disk = "local";
        $destination_path = "/uploads/signatures";       
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setIdPictureAttribute($value)
    {
        $attribute_name = "image";
        // or use your own disk, defined in config/filesystems.php
        $disk = config('backpack.base.root_disk_name');
        // destination path relative to the disk above
        $destination_path = "public/uploads/folder_1/folder_2";

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
