<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MunicipalityRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MunicipalityCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MunicipalityCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view-municipalities', ['only' => ['index','show']]);
        $this->middleware('can:create-municipalities', ['only' => ['create','store']]);
        $this->middleware('can:edit-municipalities', ['only' => ['edit','update']]);
        $this->middleware('can:delete-municipalities', ['only' => ['destroy']]);
    }


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Municipality::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/municipality');
        CRUD::setEntityNameStrings('municipality', 'municipalities');
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
        CRUD::setValidation(MunicipalityRequest::class);
        CRUD::addField([
            'name' => 'name',
            'label' => 'Municipality Name',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ]
        ]);
        CRUD::addField([
            'name'=>'province',
            'label' => "Province",
            'type'=>'select',
            'entity' => 'province',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ]
        ]);
        

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
