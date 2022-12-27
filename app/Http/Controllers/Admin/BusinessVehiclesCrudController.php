<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BusinessVehiclesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


/**
 * Class BusinessVehiclesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BusinessVehiclesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:Business > Business Vehicles', ['only' => ['index','show','create','store','edit','update','destroy']]);
    }
    

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\BusinessVehicles::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/business-vehicles');
        $this->crud->setEntityNameStrings('business vehicles', 'business vehicles');
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
                    return route('business-vehicles.edit',$entry->id);
                },
            ],
            'searchLogic' => function ($query, $column, $searchTerm) {
                return $query->orWhere('refID', 'like', '%'.$searchTerm.'%');
 
             }
          ]);
          $this->crud->column('name');
          $this->crud->column('business_fees_id');
        

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
        $this->crud->setValidation(BusinessVehiclesRequest::class);

         $this->crud->addField([  // Select
            'label'     => "Name",
            'type'      => 'text',
            'name'      => 'name'
         ]);
         $this->crud->addField([  // Select
            'label'     => "Fee",
            'type'      => 'select',
            'name'      => 'business_fees_id',
            "entity" =>"business_fees"
         ]);
         $this->crud->addField([  // Select
            'label'     => "Description",
            'type'      => 'textarea',
            'name'      => 'description'
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
