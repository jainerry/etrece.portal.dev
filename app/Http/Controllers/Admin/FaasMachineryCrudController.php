<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FaasMachineryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class FaasMachineryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FaasMachineryCrudController extends CrudController
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
        CRUD::setModel(\App\Models\FaasMachinery::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/faas-machinery');
        //CRUD::setEntityNameStrings('faas machinery', 'faas machineries');
        CRUD::setEntityNameStrings('machinery', 'machineries');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('ARPNo');
        CRUD::column('primaryOwner');
        CRUD::column('ownerAddress');
        CRUD::column('ownerTelephoneNo');

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
        CRUD::setValidation(FaasMachineryRequest::class);

        $this->crud->addField([
            'name'=>'transactionCode',
            'label'=>'Transaction Code',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ARPNo',
            'label'=>'ARP No.',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'pin',
            'label'=>'PIN',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'primaryOwner',
            'label'=>'Owner',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-9'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerTin',
            'label'=>'TIN',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerAddress',
            'label'=>'Address',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-9'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerTelephoneNo',
            'label'=>'Telephone No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'secondaryOwners',
            'label'=>'Secondary Owner/s',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administrator',
            'label'=>'Administrator',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-9'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorTin',
            'label'=>'TIN',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorAddress',
            'label'=>'Address',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-9'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorTelephoneNo',
            'label'=>'Telephone No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);

        $this->crud->addField([
            'name'=>'administratorTelephoneNo',
            'label'=>'Telephone No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);

        $this->crud->addField([
            'name'=>'noOfStreet',
            'label'=>'No. of Street',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'=>'barangay',
            'label'=>'Bgry./District',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'=>'city',
            'label'=>'Municipality',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'=>'province',
            'label'=>'Province/City',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'=>'landOwner',
            'label'=>'Land Owner',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-9'
            ],
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'=>'landOwnerPin',
            'label'=>'PIN',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'=>'buildingOwner',
            'label'=>'Building Owner',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-9'
            ],
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'=>'buildingOwnerPin',
            'label'=>'PIN',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Location',
        ]);

        $this->crud->addField([
            'name'=>'buildingOwnerPin',
            'label'=>'PIN',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
            ],
            'tab' => 'Property Appraisal',
        ]);

        // propertyAppraisal repeatable
        // $this->crud->addField([   
        //     'name'  => 'propertyAppraisal',
        //     'label' => 'Property Appraisal',
        //     'type'  => 'repeatable',
        //     'subfields' => [ // also works as: "fields"
        //         [
        //             'name'    => 'kindOfMachinery',
        //             'type'    => 'text',
        //             'label'   => 'Kind of Machinery',
        //             'hint'    => '(Use additional sheets if necessary)',
        //             'wrapper' => ['class' => 'form-group col-md-3'],
        //         ],
        //         [
        //             'name'    => 'brandModel',
        //             'type'    => 'text',
        //             'label'   => 'Brand & Model',
        //             'wrapper' => ['class' => 'form-group col-md-3'],
        //         ],
        //         [
        //             'name'    => 'capacity',
        //             'type'    => 'text',
        //             'label'   => 'Capacity/HP',
        //             'wrapper' => ['class' => 'form-group col-md-3'],
        //         ],
        //         [
        //             'name'  => 'dateAcquired',
        //             'type'  => 'date',
        //             'label' => 'Date Acquired',
        //             'wrapper' => ['class' => 'form-group col-md-3'],
        //         ],
        //         [
        //             'name'  => 'conditionWhenAcquired',
        //             'type'  => 'select_from_array',
        //             'label' => 'Condition When Acquired',
        //             'options' => [
        //                 'New' => 'New',
        //                 'Second Hand' => 'Second Hand'
        //             ],
        //             'wrapper' => ['class' => 'form-group col-md-3'],
        //         ],
        //         [
        //             'name'    => 'economicLifeEstimated',
        //             'type'    => 'text',
        //             'label'   => 'Economic Life - Estimated',
        //             'hint'    => '(No. of Years)',
        //             'wrapper' => ['class' => 'form-group col-md-3'],
        //         ],
        //         [
        //             'name'    => 'economicLifeRemain',
        //             'type'    => 'text',
        //             'label'   => 'Economic Life - Remain',
        //             'hint'    => '(No. of Years)',
        //             'wrapper' => ['class' => 'form-group col-md-3'],
        //         ],
        //         [
        //             'name'    => 'yearInstalled',
        //             'type'    => 'text',
        //             'label'   => 'Year Installed',
        //             'wrapper' => ['class' => 'form-group col-md-3'],
        //         ],
        //         [
        //             'name'  => 'yearOfInitialOperation',
        //             'type'  => 'text',
        //             'label' => 'Year of Initial Operation',
        //             'wrapper' => ['class' => 'form-group col-md-3'],
        //         ],
        //         [
        //             'name'  => 'originalCost',
        //             'type'  => 'text',
        //             'label' => 'Original Cost',
        //             'wrapper' => ['class' => 'form-group col-md-3'],
        //         ]
        //     ],
        
        //     // optional
        //     'new_item_label'  => '+ New Item', // customize the text of the button
        //     'init_rows' => 2, // number of empty rows to be initialized, by default 1
        //     'min_rows' => 2, // minimum rows allowed, when reached the "delete" buttons will be hidden
        //     'max_rows' => 2, // maximum rows allowed, when reached the "new item" button will be hidden
        //     // allow reordering?
        //     'reorder' => false, // hide up&down arrows next to each row (no reordering)
        //     'reorder' => true, // show up&down arrows next to each row
        //     'reorder' => 'order', // show arrows AND add a hidden subfield with that name (value gets updated when rows move)
        //     'reorder' => ['name' => 'order', 'type' => 'number', 'attributes' => ['data-reorder-input' => true]], // show arrows AND add a visible number subfield
        // ]);
        //

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
