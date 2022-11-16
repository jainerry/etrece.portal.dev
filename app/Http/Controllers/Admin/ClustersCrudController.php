<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ClustersRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ClustersCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ClustersCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Clusters::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/clusters');
        CRUD::setEntityNameStrings('clusters', 'clusters');
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
        CRUD::column('barangay_id');
        CRUD::column('isActive');
        CRUD::column('created_at');
        CRUD::column('updated_at');

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
        CRUD::setValidation(ClustersRequest::class);

        CRUD::addField([
            'name' => 'name',
            'type' =>'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6'
            ]
        ]);
        CRUD::addField([  // Select
            'label'     => "Baranggay",
            'type'      => 'select',
            'name'      => 'barangay', // the db column for the foreign key
         
            // optional
            // 'entity' should point to the method that defines the relationship in your Model
            // defining entity will make Backpack guess 'model' and 'attribute'
            'entity'    => 'barangay',
         
            // optional - manually specify the related model and attribute
            'model'     => "App\Models\Barangay", // related model
            'attribute' => 'name', // foreign key attribute that is shown to user
         
            // optional - force the related options to be a custom query, instead of all();
            'options'   => (function ($query) {
                 return $query->orderBy('name', 'ASC')->get();
             }), //  you can use this to filter the results show in the select.
             'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6'
            ]
         ]);
        
         $this->crud->addField([   
            'name'        => 'isActive',
            'label'       => "isActive",
            'type'        => 'select_from_array',
            'options'     => ['y'=>'TRUE','n'=>'FALSE'],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
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
