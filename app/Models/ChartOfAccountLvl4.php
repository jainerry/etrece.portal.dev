<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\TransactionLogs;

class ChartOfAccountLvl4 extends Model
{
    use CrudTrait;
    use HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'chart_of_accounts_lvl4';
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
        ChartOfAccountLvl4::creating(function($model){
            $count = ChartOfAccountLvl4::count();
            $refID = 'CHART-ACC-LVL4'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $model->refID = $refID;

            TransactionLogs::create([
                'transId' =>$refID,
                'category' =>'chart_of_accounts_lvl4',
                'type' =>'create',
            ]);
        });

        ChartOfAccountLvl4::updating(function($entry) {
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'chart_of_accounts_lvl4',
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

    public function parentLevel3(){
        return $this->belongsTo(ChartOfAccountLvl3::class, 'lvl3ID', 'id');
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
    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>( $value == 'Y'? 'Active':'Inactive'),
        );
    }
}
