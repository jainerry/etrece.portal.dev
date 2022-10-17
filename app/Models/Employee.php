<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Office;
use App\Models\Section;
use App\Models\Position;
use App\Models\Appointment;
use App\Models\Street;
use Yajra\Address\Entities\Barangay;
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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function setIdPictureAttribute($value)
    {
        $attribute_name = "idPicture";
        $disk = "local";
        $destination_path = "/uploads/idPictures";       
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

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
