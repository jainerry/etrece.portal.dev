<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BarangayRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BarangayCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BarangayCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Barangay::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/barangay');
        CRUD::setEntityNameStrings('barangay', 'barangays');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('referenceCode');
        CRUD::column('name');
        // CRUD::column('code');
        // CRUD::column('region_id');
        // CRUD::column('province_id');
        // CRUD::column('city_id');


        $this->crud->addFilter([ 
            'type'  => 'simple',
            'name'  => 'city_id',
            'label' => 'Show Only Barangays in Trece Martires'
          ],
          false, // the simple filter has no values, just the "Draft" label specified above
          function() { // if the filter is active (the GET parameter "draft" exits)
            $this->crud->addClause('where', 'city_id', '042122'); 
            // we've added a clause to the CRUD so that only elements with draft=1 are shown in the table
            // an alternative syntax to this would have been
            // $this->crud->query = $this->crud->query->where('draft', '1'); 
            // another alternative syntax, in case you had a scopeDraft() on your model:
            // $this->crud->addClause('draft'); 
          });

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
        CRUD::setValidation(BarangayRequest::class);


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
                'name'=>'referenceCode',
                'label'=>'Reference Code',
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4'
                ]
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
        

        // CRUD::field('code');
        // CRUD::field('name');
        // CRUD::field('region_id');
        // CRUD::field('province_id');
        // CRUD::field('city_id');

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
