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
use Illuminate\Support\Str;

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

    protected $appends = ['entry_data','full_name'];

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
        'permanentStreetId',
        'officeId'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute(){
        $firstName = ucfirst($this->firstName);
        $middleName = ucfirst($this->middleName);
        $lastName = ucfirst($this->lastName);
        return "{$firstName} {$middleName} {$lastName}";
    }

    public function getEntryDataAttribute(){
        $firstName = ucfirst($this->firstName);
        $middleName = ucfirst($this->middleName);
        $lastName = ucfirst($this->lastName);
        $birthDate = $this->birthDate;
        $residentialBarangay = ($this->residentialBarangay == null) ? $this->residentialBarangay:$this->residentialBarangay->name;
        return "{$firstName}  ".($middleName == null ?"": $middleName." ")."{$lastName} - {$this->refID} - {$residentialBarangay} - BDATE({$birthDate})";
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
        return Section::find($this->sectionId)->name;
    }

    public function getPosition(){
        return Position::find($this->positionId)->name;
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
        return $this->belongsTo(Section::class, 'sectionId', 'id');
    }

    public function position(){
        return $this->belongsTo(Position::class, 'positionId', 'id');
    }

    public function office(){
        return $this->belongsTo(Office::class, 'officeId', 'id');
    }

    public function appointment(){
        return $this->belongsTo(Appointment::class, 'appointmentId', 'id');
    }

    public function residentialStreet(){
        return $this->belongsTo(Street::class, 'residentialStreetId','id');
    }

    public function permanentStreet(){
        return $this->belongsTo(Street::class, 'permanentStreetId','id');
    }

    public function residentialBarangay(){
        return $this->belongsTo(Barangay::class, 'residentialBarangayId','id');
    }

    public function permanentBarangay(){
        return $this->belongsTo(Barangay::class, 'permanentBarangayId','id');
    }

    public function machinery_profile(){
        return $this->hasMany(FaasMachinery::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function setIdPictureAttribute($value)
    {
        $attribute_name = "idPicture";
        // or use your own disk, defined in config/filesystems.php
        $disk = config('backpack.base.root_disk_name');
        // destination path relative to the disk above
        $destination_path = "public/uploads/images/employee/id-pictures";

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

    public function setHalfPictureAttribute($value)
    {
        $attribute_name = "halfPicture";
        // or use your own disk, defined in config/filesystems.php
        $disk = config('backpack.base.root_disk_name');
        // destination path relative to the disk above
        $destination_path = "public/uploads/images/employee/half-pictures";

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

    public function setSignatureAttribute($value)
    {
        $attribute_name = "signature";
        // or use your own disk, defined in config/filesystems.php
        $disk = config('backpack.base.root_disk_name');
        // destination path relative to the disk above
        $destination_path = "public/uploads/images/employee/signatures";

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
