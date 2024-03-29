<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class TransactionLogs extends Model
{
    use CrudTrait;
    use HasUuids;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'transaction_logs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $attributes = [
        'refID' => ''
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
    protected static function boot(){
        parent::boot();
        $count = TransactionLogs::count();
        $refID = 'TRANS-LOG'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);

        TransactionLogs::creating(function($model) use($refID){
            $model->refID = $refID;
        });
    }
    
    // protected function refId(): Attribute
    // {
    //     return Attribute::make(
    //         set: function ($value){
    //            $count =  $this->select(DB::raw('count(*) as count'))->where('refID','like',"%".Date('mdY')."%")->first();
    //            $refId = 'TRANSID'.Date('mdY').'-'.str_pad(($count->count), 4, "0", STR_PAD_LEFT);
    //            return $refId;
    //         },
    //     );
    // }
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
