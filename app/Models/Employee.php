<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Office;
use App\Models\Section;
use App\Models\Position;

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
        'bpNo',
        'emergencyContactPerson',
        'emergencyContactRelationship',
        'emergencyContactAddress1',
        'emergencyContactAddress2',
        'oldIDNo',
        'isActive',
        'sectionId',
        'positionId',
        'picName',
        'halfPicName',
        'signName',
        'empPrint',
        'workStatus',
        'remarks',
        'encryptCode',
        'contactNo',
        'smallPrint',
        'suffix',
        'birthPlace',
        'civilStatus',
        'citizenShip',
        'citizenShipAcquisition',
        'country',
        'sex',
        'height',
        'weight',
        'pagibigNo',
        'philhealthNo',
        'sssNo',
        'landlineNo',
        'email',
        'residentialAddress',
        'permanentAddress',
        'residentialSitio',
        'permanentSitio'
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

    // public function workStatus(){
    //     return WorkStatuses::find(1)->name;
    // }

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
