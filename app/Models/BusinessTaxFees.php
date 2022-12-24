<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class BusinessTaxFees extends Model
{
    use CrudTrait;
    use HasUlids;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'business_tax_fees';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'range_box' => 'array',
    ];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot(){
        parent::boot();

        BusinessTaxFees::creating(function($model){
            $count = BusinessTaxFees::count();
            $refID = 'BUSS-TAX-FEES'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $model->refID = $refID;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function business_fees(){
        return $this->belongsTo(BusinessFees::class, "business_fees_id", "id");
    }
    public function account_name(){
        return $this->belongsTo(ChartOfAccountLvl4::class, "chart_of_accounts_lvl4_id", "id");
    }
    public function business_categories(){
        return $this->belongsTo(BusinessCategory::class, "business_categories_id", "id");
    }
    public function getFeesDropdownAttribute(){
        return $this->business_fees->name;
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
