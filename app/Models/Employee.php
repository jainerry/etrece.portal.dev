<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

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
        'departmentId',
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
        $middleName = ucfirst($this->middleName);
        $lastName = ucfirst($this->lastName);
        return "{$firstName}  {$middleName} {$lastName}";
    }

    public function getStatus(){
        if($this->isActive === 'Y'){
            return "Active";
        }
        else {
            return "InActive";
        }
    }

    public function getDepartment(){
        return Department::find(1)->name;
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

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function section(){
        return $this->belongsTo(Section::class);
    }

    public function position(){
        return $this->belongsTo(Position::class);
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
