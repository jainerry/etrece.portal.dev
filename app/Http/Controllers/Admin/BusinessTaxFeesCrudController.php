<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BusinessTaxFeesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;
use Backpack\CRUD\app\Library\Widget;
use App\Models\BusinessTaxFees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
/**
 * Class BusinessTaxFeesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BusinessTaxFeesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:Business > Business Tax Fees', ['only' => ['index','show','create','store','edit','update','destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
       $this->crud->setModel(\App\Models\BusinessTaxFees::class);
       $this->crud->setRoute(config('backpack.base.route_prefix') . '/business-tax-fees');
       $this->crud->setEntityNameStrings('business tax fees', 'business tax fees');
       $this->crud->removeButton('delete');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    
    protected function setupListOperation()
    {
        $this->crud->removeButton('delete');  
        $this->crud->removeButton('show');  
        $this->crud->removeButton('update');  
        $this->crud->orderBy('refID','desc');
      
        $this->crud->addColumn([
            'label'     => 'Reference ID',
            'type'      => 'text',
            'name'      => 'refID',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('business-tax-fees.edit',$entry->id);
                },
            ],
            'searchLogic' => function ($query, $column, $searchTerm) {
                return $query->orWhere('refID', 'like', '%'.$searchTerm.'%');
 
             }
          ]);
       $this->crud->column('business_fees_id');
       $this->crud->column('effective_date');
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * -$this->crud->column('price')->type('number');
         * -$this->crud->addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
       $this->crud->setValidation(BusinessTaxFeesRequest::class);
       Widget::add([
            'type'     => 'script',
            'name'      => 'custom_script',
            'content'  => '/assets/js/business-tax-fees/tax-fees-create.js',
        ]);
        
       $this->crud->addField([
        "name"=>"business_fees_id",
        "label"=>"Fees",
        'type'      => 'select',
        'entity'    => 'business_fees',
        'attribute' => 'name', 
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-6'
        ],
        'options'   => (function ($query) {
            return $query->orderBy('name', 'ASC')->get();
        }), 
       ]);
       $this->crud->addField([
        'name'  => 'effective_date',
        'type'  => 'date_picker',
        'label' => 'Effective Date',
        'date_picker_options' => [
            'todayBtn' => 'linked',
            'format'   => 'yyyy-mm-dd',
            'language' => 'fr',
            'endDate' => '0d',
            'startDate' => Carbon::now()->subYears(130)->format('Y-m-d'),
        ],
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-6'
        ]
     ]);
  
     $this->crud->addField([
        "name"=>"chart_of_accounts_lvl4_id",
        "label"=>"Account Name",
        'type'      => 'select',
        'entity'    => 'account_name',
        'attribute' => 'code_name', 
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-6'
        ],
        'options'   => (function ($query) {
            return $query->orderBy('name', 'ASC')->get();
        }), 
       ]);
       $this->crud->addField([  // Select
        "name"=>"business_categories_id",
        'label'     => "Category",
        'type'      => 'select',
        "entity"    => "business_categories",
        "attribute" => "name",
        "allow_null"=>true,
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-6'
        ],
     ]);
     $this->crud->addField([  // Select
        "name"=>"basis",
        'label'     => "Basis",
        'type'      => 'select_from_array',
        'options'         => ['01' => 'Capital/Net Profit', 
                             "02" => 'Business Area',
                             '03' => 'No of Employee',
                             "04" => "Weight & Measure",
                             "05"=>"No & Type of Vehicle"],
        "allow_null"=>true,
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-6'
        ],
     ]);
     $this->crud->addField([  // Select
        "name"=>"type",
        'label'     => "Type",
        'type'      => 'select_from_array',
        'options'         => ['01' => 'Regular', 
                             "02" => 'Range'],
        "allows_null"=>false,
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-6'
        ],
     ]);
   
     $this->crud->addField([
        "name"=>"vehicle_type",
        "label"=>"Vehicle Type",
        'type'      => 'select',
        'entity'    => 'vehicle',
        'attribute' => 'name', 
        'hint'       => 'select type of vehicle ',
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-12'
        ],
        'options'   => (function ($query) {
            return $query->orderBy('name', 'ASC')->get();
        }), 
       ]);
       $this->crud->addField([   // repeatable
        'name'  => 'range_box',
        'label' => 'Range Box',
        'type'  => 'repeatable',
        'subfields' => [ // also works as: "fields"
            [   // CustomHTML
                'name'  => 'separator',
                'type'  => 'custom_html',
                'value' => '<div class=""></div>',
                'tab' => 'Details',
                'wrapperAttributes' => [
                    'class' => 'form-group col-6 d-none d-md-block  mb-0'
                ]
                ],
            [
                'name'    => 'infinite',
                'type'    => 'checkbox',
                'label'   => 'Infinite',
                'wrapper' => ['class' => 'form-group form-group col-12 col-md-6  mb-0 text-right '],
            ],
            [
                'name'    => 'from',
                'type'    => 'text',
                'label'   => 'From',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name'    => 'to',
                'type'    => 'text',
                'label'   => 'To',
                'wrapper' => ['class' => 'form-group col-md-6 position-relative'],
            ],
          
            [
                'name'    => 'pp1',
                'type'    => 'text',
                'label'   => 'PP1',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [   // CustomHTML
                'name'  => 'separator',
                'type'  => 'custom_html',
               
                'value' => '<div class="form-group "><label class="d-block">&nbsp;</label><span>% of</spam></div>',
                'wrapper' => ['class' => 'form-group col-md-1'],
            ],
            [
                'name'    => 'pp2',
                'type'    => 'text',
                'label'   => 'PP2',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [   // CustomHTML
                'name'  => 'separator',
                'type'  => 'custom_html',
               
                'value' => '<div class="form-group "><label class="d-block">&nbsp;</label><span>in excess of</spam></div>',
                'wrapper' => ['class' => 'form-group col-md-1 p-0 text-center'],
            ],
            [
                'name'    => 'PAmount',
                'type'    => 'text',
                'label'   => 'PAmount',
                'wrapper' => ['class' => 'form-group col-md-4'],
            ],
        ],
    
        // optional
        'new_item_label'  => 'Add Group', // customize the text of the button
        'init_rows' => 1, // number of empty rows to be initialized, by default 1
        "min_rows"=>1,
        'max_rows' => 1, // maximum rows allowed, when reached the "new item" button will be hidden
        // allow reordering?
        'reorder' => false, // hide up&down arrows next to each row (no reordering)
    ]);
       $this->crud->addField([  // Select
        "name"=>"computation",
        'label'     => "Computation",
        'type'      => 'select_from_array',
        'options'         => ['01' => 'Amount', 
                             "02" => 'Percentage'],
        "allow_null"=>true,
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-6'
        ],
     ]);
       $this->crud->addField([
        'name'=>"amount_value",
        "type"=>"number",
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-6'
        ],
        ]);
        $this->crud->addField([
            'name'=>'isActive',
            'label'=>'Status',
            'type' => 'select_from_array',
            'options' => [
                'Y' => 'Active', 
                'N' => 'Inactive'
            ],
            'allows_null' => false,
            'default'     => 'Y',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
            ],
        ]);
       
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * -$this->crud->field('price')->type('number');
         * -$this->crud->addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function getDetails(Request $request){
        $id = $request->input('id');
        
        $results = [];
        if (!empty($id))
        {
            $results = BusinessTaxFees::where('isActive', '=', 'Y')
                ->where('id', '=', $id)
                ->get();
        }

        return $results;
    }
}
