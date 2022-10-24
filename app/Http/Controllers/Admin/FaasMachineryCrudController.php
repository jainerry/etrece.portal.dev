<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FaasMachineryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use App\Models\FaasMachinery;
use App\Models\CitizenProfile;
use Backpack\CRUD\app\Library\Widget;

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
        //Widget::add()->type('style')->content('assets/css/faas/machinery/styles.css');
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');

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
        //CRUD::column('secondaryOwners');
        //CRUD::column('ownerAddress');
        //CRUD::column('ownerTelephoneNo');

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

        /*$this->crud->addField([
            'name'=>'transactionCode',
            'label'=>'Transaction Code',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);*/

        /*$this->crud->addField([
            'name'=>'ARPNo',
            'label'=>'ARP No.',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);*/

        /*$this->crud->addField([
            'name'=>'pin',
            'label'=>'PIN',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);*/

        // $this->crud->addField([   // n-n relationship
        //     'label'       => "Primary Owner", // Table column heading
        //     'type'        => "select2_from_ajax",
        //     'name'        => 'primaryOwner', // a unique identifier (usually the method that defines the relationship in your Model)
        //     'entity'      => 'citizen_profile', // the method that defines the relationship in your Model
        //     'attribute'   => "citizenProfileData", // foreign key attribute that is shown to user
        //     'data_source' => url("/admin/api/citizen-profile/ajaxsearch"), // url to controller search function (with /{id} should return model)
        //     'pivot'       => true, // on create&update, do you need to add/delete pivot table entries?
        
        //     // OPTIONAL
        //     'delay'                      => 500, // the minimum amount of time between ajax requests when searching in the field
        //     'model'                      => "App\Models\CitizenProfile", // foreign key model
        //     'placeholder'                => "Select Primary Owner", // placeholder for the select
        //     'minimum_input_length'       => 2, // minimum characters to type before querying results
        //     // 'method'                  => 'POST', // optional - HTTP method to use for the AJAX call (GET, POST)
        //     // 'include_all_form_fields' => false, // optional - only send the current field through AJAX (for a smaller payload if you're not using multiple chained select2s)
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-6'
        //     ],
        //     'tab' => 'Main Information',
        // ]);

        // $this->crud->addField([   // n-n relationship
        //     'label' => 'Primary Owner',
        //     'type' => 'select2_from_ajax',
        //     'name' => 'primaryOwner',
        //     'entity' => 'citizen_profile',
        //     'attribute' => 'fullname',
        //     'data_source' => url('/admin/api/citizen-profile/ajaxsearch'),
        //     'minimum_input_length' => 2,
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-6'
        //     ],
        //     'tab' => 'Main Information',
        // ]);

        

        $this->crud->addField([   // n-n relationship
            'label' => 'Primary Owner',
            'type' => 'primary_owner_input',
            'name' => 'primaryOwner',
            'entity' => 'citizen_profile',
            'attribute' => 'full_name',
            'data_source' => url('/admin/api/citizen-profile/ajaxsearch'),
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6'
            ],
            'tab' => 'Main Information',
        ]);

        $this->crud->addField([   // n-n relationship
            'name' => 'machinery_owner', // JSON variable name
            'label' => 'Secondary Owner', // human-readable label for the input
            'type' => 'secondary_owner',
            'entity' => 'machinery_owner',
            'data_source' => url('/admin/api/citizen-profile/ajaxsearch'),
            'attribute' => 'full_name',
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6'
            ],
            'tab' => 'Main Information',
        ]);

        // $this->crud->addField([   // n-n relationship
        //     'name' => 'machinery_owner', // JSON variable name
        //     'label' => 'Secondary Owners', // human-readable label for the input
        //     'type' => 'select2_from_ajax_multiple',
        //     'entity' => 'machinery_owner',
        //     'data_source' => url('/admin/api/citizen-profile/ajaxsearch'),
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-6'
        //     ],
        //     'tab' => 'Main Information',
        // ]);

        

        // $this->crud->addField([   // n-n relationship
        //     'label'       => "Secondary Owners", // Table column heading
        //     'type'        => "select2_from_ajax_multiple",
        //     'name'        => 'secondaryOwners', // a unique identifier (usually the method that defines the relationship in your Model)
        //     'entity'      => 'machinery_owners', // the method that defines the relationship in your Model
        //     'attribute'   => "citizenProfileData", // foreign key attribute that is shown to user
        //     'data_source' => url("/admin/api/citizen-profile/ajaxsearch"), // url to controller search function (with /{id} should return model)
        //     'pivot'       => false, // on create&update, do you need to add/delete pivot table entries?
        
        //     // OPTIONAL
        //     'delay'                      => 500, // the minimum amount of time between ajax requests when searching in the field
        //     'model'                      => "App\Models\CitizenProfile", // foreign key model
        //     'placeholder'                => "Select Secondary Owners", // placeholder for the select
        //     'minimum_input_length'       => 2, // minimum characters to type before querying results
        //     // 'method'                  => 'POST', // optional - HTTP method to use for the AJAX call (GET, POST)
        //     // 'include_all_form_fields' => false, // optional - only send the current field through AJAX (for a smaller payload if you're not using multiple chained select2s)
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-6'
        //     ],
        //     'tab' => 'Main Information',
        // ]);

        // $this->crud->addField([
        //     'name'=>'ownerAddress',
        //     'label'=>'Address',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-9'
        //     ],
        //     'tab' => 'Main Information',
        // ]);

        // $this->crud->addField([
        //     'name'=>'ownerTin',
        //     'label'=>'TIN',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Main Information',
        // ]);
        
        // $this->crud->addField([
        //     'name'=>'ownerTelephoneNo',
        //     'label'=>'Telephone No.',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Main Information',
        // ]);

        /*$this->crud->addField([
            'name'=>'administrator',
            'label'=>'Administrator',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-9'
            ],
            'tab' => 'Main Information',
        ]);*/

        /*$this->crud->addField([
            'name'=>'administratorTin',
            'label'=>'TIN',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);*/

        /*$this->crud->addField([
            'name'=>'administratorAddress',
            'label'=>'Address',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-9'
            ],
            'tab' => 'Main Information',
        ]);*/

        /*$this->crud->addField([
            'name'=>'administratorTelephoneNo',
            'label'=>'Telephone No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);*/

        // $this->crud->addField([
        //     'name'=>'noOfStreet',
        //     'label'=>'No. of Street',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-6'
        //     ],
        //     'tab' => 'Property Location',
        // ]);
        // $this->crud->addField([
        //     'name'=>'barangay',
        //     'label'=>'Bgry./District',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-6'
        //     ],
        //     'tab' => 'Property Location',
        // ]);
        // $this->crud->addField([
        //     'name'=>'city',
        //     'label'=>'Municipality',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-6'
        //     ],
        //     'tab' => 'Property Location',
        // ]);
        // $this->crud->addField([
        //     'name'=>'province',
        //     'label'=>'Province/City',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-6'
        //     ],
        //     'tab' => 'Property Location',
        // ]);
        // $this->crud->addField([
        //     'name'=>'landOwner',
        //     'label'=>'Land Owner',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-9'
        //     ],
        //     'tab' => 'Property Location',
        // ]);
        // $this->crud->addField([
        //     'name'=>'landOwnerPin',
        //     'label'=>'PIN',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Property Location',
        // ]);
        // $this->crud->addField([
        //     'name'=>'buildingOwner',
        //     'label'=>'Building Owner',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-9'
        //     ],
        //     'tab' => 'Property Location',
        // ]);
        // $this->crud->addField([
        //     'name'=>'buildingOwnerPin',
        //     'label'=>'PIN',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Property Location',
        // ]);

        // $this->crud->addField([
        //     'name'=>'buildingOwnerPin',
        //     'label'=>'PIN',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-12'
        //     ],
        //     'tab' => 'Property Appraisal',
        // ]);

        // propertyAppraisal repeatable
        $this->crud->addField([   
            'name'  => 'propertyAppraisal',
            'label' => 'Property Appraisal',
            'type'  => 'repeatable',
            'subfields' => [ // also works as: "fields"
                [
                    'name'    => 'kindOfMachinery',
                    'type'    => 'text',
                    'label'   => 'Kind of Machinery',
                    'hint'    => '(Use additional sheets if necessary)',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'brandModel',
                    'type'    => 'text',
                    'label'   => 'Brand & Model',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'capacity',
                    'type'    => 'text',
                    'label'   => 'Capacity/HP',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'dateAcquired',
                    'type'  => 'date',
                    'label' => 'Date Acquired',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'conditionWhenAcquired',
                    'type'  => 'select_from_array',
                    'label' => 'Condition When Acquired',
                    'options' => [
                        'New' => 'New',
                        'Second Hand' => 'Second Hand'
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'economicLifeEstimated',
                    'type'    => 'text',
                    'label'   => 'Economic Life - Estimated',
                    'hint'    => '(No. of Years)',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'economicLifeRemain',
                    'type'    => 'text',
                    'label'   => 'Economic Life - Remain',
                    'hint'    => '(No. of Years)',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'yearInstalled',
                    'type'    => 'text',
                    'label'   => 'Year Installed',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'yearOfInitialOperation',
                    'type'  => 'text',
                    'label' => 'Year of Initial Operation',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'originalCost',
                    'type'  => 'text',
                    'label' => 'Original Cost',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ]
            ],
        
            // optional
            'new_item_label'  => 'New Item', // customize the text of the button
            'init_rows' => 1, // number of empty rows to be initialized, by default 1
            'min_rows' => 1, // minimum rows allowed, when reached the "delete" buttons will be hidden
            'max_rows' => 10, // maximum rows allowed, when reached the "new item" button will be hidden
            // allow reordering?
            //'reorder' => false, // hide up&down arrows next to each row (no reordering)
            'reorder' => true, // show up&down arrows next to each row
            // 'reorder' => 'order', // show arrows AND add a hidden subfield with that name (value gets updated when rows move)
            // 'reorder' => ['name' => 'order', 'type' => 'number', 'attributes' => ['data-reorder-input' => true]], // show arrows AND add a visible number subfield

            'tab' => 'Property Appraisal',
        ]);
        

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */


        FaasMachinery::creating(function($entry) {
            $count = FaasMachinery::select(DB::raw('count(*) as count'))->where('ARPNo','like',"%".Date('mdY')."%")->first();
            $ARPNo = 'ARP'.Date('mdY').'-'.str_pad(($count->count), 4, "0", STR_PAD_LEFT);
            $entry->ARPNo = $ARPNo;

            // $request = app(FaasMachinery::class);

            // if(!empty($request->input('propertyAppraisal')))
        });
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

    /**
     * Show the form for creating inserting a new row.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        // Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');
        // Widget::add()->type('script')->content('assets/js/faas/machinery/owner-selection.js');

        $this->crud->hasAccessOrFail('create');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        //return view($this->crud->getCreateView(), $this->data);
        return view('faas_machinery.create', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        // Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');
        // Widget::add()->type('script')->content('assets/js//faas/machinery/owner-selection.js');

        $this->crud->hasAccessOrFail('update');
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        // get the info for that entry

        $this->data['entry'] = $this->crud->getEntryWithLocale($id);
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        //return view($this->crud->getEditView(), $this->data);
        return view('faas_machinery.edit', $this->data);
    }
}
