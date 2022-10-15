<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\OfficeLocation;

class Office extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'offices';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];


    protected $fillable = [
        'name',
        'code',
        'officeLocationId',
        'contactNo',
        'headId',
        'isActive'
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

    public function getOfficeLocation(){
        return OfficeLocation::find($this->officeLocationId)->name;
    }

    public function getOfficeHead(){
        if(!empty($this->headId)) {
            return Employee::find($this->headId)->firstName . ' ' . Employee::find(1)->lastName;
        }
        else {
            return $this->headId;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function officeHead(){
        return $this->belongsTo(Employee::class);
    }

    public function officeLocation(){
        return $this->belongsTo(OfficeLocation::class);
    }

    public function employess(){
        return $this->hasMany(Employee::class);
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
