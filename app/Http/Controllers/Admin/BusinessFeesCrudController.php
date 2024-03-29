<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BusinessFeesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BusinessFeesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BusinessFeesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:Business > Business Fees', ['only' => ['index','show','create','store','edit','update','destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\BusinessFees::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/business-fees');
        $this->crud->setEntityNameStrings('Fees', 'Fees');
        $this->crud->removeButton('delete');
        $this->crud->addClause('where', 'isActive', '=', 'Y');
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
                    return route('business-fees.edit',$entry->id);
                },
            ],
            'searchLogic' => function ($query, $column, $searchTerm) {
                return $query->orWhere('refID', 'like', '%'.$searchTerm.'%');
 
             }
          ]);

        $this->crud->addColumn([
            // select_from_array
            'name'    => 'category',
            'label'   => 'Cateogry',
            'type'    => 'select_from_array',
            'options' => ['01' => 'Business Tax', 
            "02" => 'Mayors Permit',
            '03' => 'Occupational Tax',
            "04" => "Delivery Truck",
           "05"=>"Regulatory"],
        ]);
        $this->crud->column('name');
       

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']); 
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
        $this->crud->setValidation(BusinessFeesRequest::class);

        $this->crud->addField([  // Select
            "name"=>"category",
            'label'     => "Category",
            'type'      => 'select_from_array',
            'options'         => ['01' => 'Business Tax', 
                                 "02" => 'Mayors Permit',
                                 '03' => 'Occupational Tax',
                                 "04" => "Delivery Truck",
                                "05"=>"Regulatory"],
         ]);
        $this->crud->field('name');
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
         * - $this->crud->field('price')->type('number');
         * - $this->crud->addField(['name' => 'price', 'type' => 'number'])); 
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
}
