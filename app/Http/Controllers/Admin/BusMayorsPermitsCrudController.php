<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BusMayorsPermitsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BusMayorsPermitsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BusMayorsPermitsCrudController extends CrudController
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
        $this->crud->setModel(\App\Models\BusMayorsPermits::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/bus-mayors-permits');
        $this->crud->setEntityNameStrings('bus mayors permits', 'bus mayors permits');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->column('refID');
        $this->crud->column('category');
        $this->crud->column('from');
        $this->crud->column('to');
        $this->crud->column('isActive');
        $this->crud->column('created_at');
        $this->crud->column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crudcolumn('price')->type('number');
         * - $this->crudaddColumn(['name' => 'price', 'type' => 'number']); 
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
        $this->crud->setValidation(BusMayorsPermitsRequest::class);

        // $this->crud->field('refID');
        $this->crud->field('category');
        $this->crud->addField([
            'name'=>'from',
            'label'=>'From',
            'type'=>'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 '
            ],
        ]);
        $this->crud->addField([
            'name'=>'to',
            'label'=>'To',
            'type'=>'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 '
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
                'class' => 'form-group col-12 '
            ],
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - $this->crudfield('price')->type('number');
         * - $this->crudaddField(['name' => 'price', 'type' => 'number'])); 
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
        $this->crud->setupCreateOperation();
    }
}
