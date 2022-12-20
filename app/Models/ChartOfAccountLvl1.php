<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\TransactionLogs;

class ChartOfAccountLvl1 extends Model
{
    use CrudTrait;
    use HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'chart_of_accounts_lvl1';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['code_name'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot(){
        parent::boot();
        ChartOfAccountLvl1::creating(function($model){
            $count = ChartOfAccountLvl1::count();
            $refID = 'CHART-ACC-LVL1'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $model->refID = $refID;

            TransactionLogs::create([
                'transId' =>$refID,
                'category' =>'chart_of_accounts_lvl1',
                'type' =>'create',
            ]);
        });

        ChartOfAccountLvl1::updating(function($entry) {
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'chart_of_accounts_lvl1',
                'type' =>'update',
            ]);
        });
    }

    public function getCodeNameAttribute(){
        $code_name = $this->code."-".$this->name;
        return "{$code_name}";
    }

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
    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>( $value == 'Y'? 'Active':'Inactive'),
        );
    }
}
