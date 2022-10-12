<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\Building;

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
        'buildingId',
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

    public function getBuilding(){
        return Building::find(1)->name;
    }

    public function getHead(){
        return Employee::find(1)->firstName . ' ' . Employee::find(1)->lastName;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function head(){
        return $this->belongsTo(Employee::class);
    }

    public function building(){
        return $this->belongsTo(Building::class);
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
