<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StreetRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Yajra\Address\Entities\Barangay;

/**
 * Class StreetCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StreetCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Street::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/street');
        CRUD::setEntityNameStrings('street', 'streets');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        
        CRUD::column('name');
        CRUD::addColumn([
            'label'=>'Barangay',
            'type'  => 'model_function',
            'function_name' => 'getBarangay',
        ]);
        CRUD::addColumn([
            'label'=>'Status',
            'type'  => 'model_function',
            'function_name' => 'getStatus',
        ]);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
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
        CRUD::setValidation(StreetRequest::class);

        $barangays = Barangay::select('id','name')->where('city_id','042122')->get();
        $barangayOptions = [];
        foreach($barangays as $barangay){
            $barangayOptions += [$barangay->id => $barangay->name];
        }

        $this->crud->addField(
            [
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
                    'class' => 'form-group col-12 col-md-4'
                ],
            ]
        );
        $this->crud->addField(
            [
                'name'=>'name',
                'label'=>'Name',
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-12'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'barangayId',
                'label'=>'Barangay',
                'type' => 'select_from_array',
                'options' => $barangayOptions,
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
               ]
            ]
        );

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
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