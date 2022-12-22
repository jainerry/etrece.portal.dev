<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BusinessProfilesRequest;
use App\Models\BusinessCategory;
use App\Models\BusinessJobCategories;
use App\Models\BusinessProfiles;
use App\Models\BusinessTaxCode;
use App\Models\CitizenProfile;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
/**
 * Class BusinessProfilesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BusinessProfilesCrudController extends CrudController
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
        $this->crud->setModel(\App\Models\BusinessProfiles::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/business-profiles');
        $this->crud->setEntityNameStrings('business profiles', 'business profiles');
        $this->crud->setCreateView('business/profiles/crud/create');
        $this->crud->setEditView('business/profiles/crud/edit');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->enableExportButtons();
        $this->crud->removeButton('delete');  
        $this->crud->removeButton('show');  
        $this->crud->removeButton('update');  

        $this->crud->addColumn([
            'label'     => 'Reference ID',
            'type'      => 'text',
            'name'      => 'refID',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('business-profiles.edit',$entry->id);
                },
            ],
          ]);
        
       
        $this->crud->column('business_name')->label('Business Name');
        $this->crud->addColumn([
            'name'  => 'main_office_address',
            'label' => 'Address', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getFullAddress', 
        ]);
        $this->crud->addColumn([
            'name'  => 'owner_id',
            'label' => 'Owner', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getOwner', 
         ]);

        $this->crud->column('sec_reg_date');
        $this->crud->column('isActive')->label('Status');

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
        $this->crud->setValidation(BusinessProfilesRequest::class);
        Widget::add([
            'type'     => 'script',
            'name'      => 'custom_script',
            'content'  => '/assets/js/business.js',
        ]);


        $this->crud->addField([
            'name' => 'business_name',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12 mt-3'
            ],
            'tab' => 'Details',
        ]);
       
        $id = $this->crud->getCurrentEntryId();
        if ($id != false) {
            $data = BusinessProfiles::where('id', $id)->first();
            $owner_id = $data->owner_id;
            $ownerExist  = CitizenProfile::where("id", $owner_id)->count();
            if ($ownerExist == 0) {
                $this->crud->addField([
                    'label' => 'Owner/Pres/OIC',
                    'type' => 'business_owner',
                    'name' => 'owner_id',
                    'entity' => 'names',
                    'attribute' => 'full_name',
                    'data_source' => url('/admin/api/citizen-profile/search-business-owner'),
                    'minimum_input_length' => 1,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-12 ',
                    ],
                    'tab' => 'Details',
                ]);
            } else {
                $this->crud->addField([
                    'label' => 'Owner/Pres/OIC',
                    'type' => 'business_owner',
                    'name' => 'owner_id',
                    'entity' => 'owner',
                    'attribute' => 'full_name',
                    'data_source' => url('/admin/api/citizen-profile/search-business-owner'),
                    'minimum_input_length' => 1,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-12 ',
                    ],
                    'tab' => 'Details',
                ]);
            }
        } else {
            $this->crud->addField([
                'label' => 'Owner/Pres/OIC',
                'type' => 'business_owner',
                'name' => 'owner_id',
                'entity' => 'owner',
                'attribute' => 'full_name',
                'data_source' => url('/admin/api/citizen-profile/search-business-owner'),
                'minimum_input_length' => 1,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-12 ',
                ],
                'tab' => 'Details',
            ]);
        }



        $this->crud->addField([

            'label' => 'Main Office Business Address',
            'type' => 'business_main_office',
            'name' => 'main_office_address',
            'entity' => 'main_office',

            'attribute' => 'full_name',
            'data_source' => url('/admin/api/faas-land/search'),
            'minimum_input_length' => 2,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12 ',
            ],
            'tab' => 'Details',

        ]);

        $this->crud->addField([
            'name'        => 'property_owner',
            'label'       => "Property Owner (Y/N)",
            'type'        => 'select_from_array',
            'options'     => ['Y' => 'Yes', 'N' => 'No'],
            'allows_null' => false,
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ]
        ]);

        if ($id != false) {
            $data = BusinessProfiles::where('id', $id)->first();
            $owner_id = $data->owner_id;
            $ownerExist  = CitizenProfile::where("id", $owner_id)->count();
            if ($ownerExist == 0) {
                $this->crud->addField([
                    'label' => 'Lessor Name',
                    'type' => 'lessor_name',
                    'name' => 'lessor_name',
                    'entity' => 'lessor_owner',
                    'attribute' => 'full_name',
                    'data_source' => url('/admin/api/citizen-profile/search-business-owner'),
                    'minimum_input_length' => 1,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-6 ',
                    ],
                    'tab' => 'Details',
                ]);
            } else {
                $this->crud->addField([
                    'label' => 'Lessor Name',
                    'type' => 'lessor_name',
                    'name' => 'lessor_name',
                    'entity' => 'lessor_owner',
                    'attribute' => 'full_name',
                    'data_source' => url('/admin/api/citizen-profile/search-business-owner'),
                    'minimum_input_length' => 1,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-6 ',
                    ],
                    'tab' => 'Details',
                ]);
            }
        } else {
            $this->crud->addField([
                'label' => 'Lessor Name',
                'type' => 'lessor_name',
                'name' => 'lessor_name',
                'entity' => 'lessor_owner',
                'attribute' => 'full_name',
                'data_source' => url('/admin/api/citizen-profile/search-business-owner'),
                'minimum_input_length' => 1,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6 ',
                ],
                'tab' => 'Details',
            ]);
        }
        $this->crud->addField([   // phone
            'name'  => 'tel', // db column for phone
            'label' => 'Telephone Phone',
            'type'  => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 ',
            ],
            'tab' => 'Details',

        ]);
        $this->crud->addField([   // phone
            'name'  => 'mobile', // db column for phone
            'label' => 'Mobile No',
            'type'  => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 ',
            ],
            'tab' => 'Details',

        ]);
        $this->crud->addField([   // phone
            'name'  => 'email', // db column for phone
            'label' => 'Email',
            'type'  => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 ',
            ],
            'tab' => 'Details',

        ]);
        $this->crud->addField([   // phone
            'name'  => 'tin', // db column for phone
            'label' => 'TIN',
            'type'  => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 ',
            ],
            'tab' => 'Details',

        ]);
        $this->crud->addField([
            'label'     => "Business Type",
            'type'      => 'select2',
            'name'      => 'bus_type',
            'entity'    => 'bus_type',
            'model'     => "App\Models\BusinessType",
            'attribute' => 'name',
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }),
        ]);
        $this->crud->addField([
            'name'        => 'corp_type',
            'label'       => "Corp. Type(Fil/Foreign)",
            'type'        => 'select_from_array',
            'options'     => ['0' => 'Fil', '1' => 'Foreign'],
            'allows_null' => true,
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ]
        ]);
        $this->crud->addField([
            'name'        => 'trade_name_franchise',
            'label'       => "Trade Name/Franchise",
            'type'        => 'text',
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ]
        ]);
        $this->crud->addField([   // CustomHTML
            'name'  => 'separator',
            'type'  => 'custom_html',
            'value' => '<div class=""></div>',
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-8 d-none d-md-block  mb-0'
            ]
        ]);
        $this->crud->addField([

            'label' => 'Same as head office',
            'type'  => 'checkbox',
            'name'  => 'same_as_head_office',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 same_head_office text-right order-1 order-sm-0 mb-0',
            ],
            'tab' => 'Details',
        ]);
        $this->crud->addField([
            'label'     => "Business Activity",
            'type'      => 'select2',
            'name'      => 'business_activity_id',
            'entity'    => 'bus_activity',
            'model'     => "App\Models\BusinessActivity",
            'attribute' => 'name',
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }),
        ]);
        $this->crud->addField([
            'name'        => 'other_buss_type',
            'label'       => "Other Buss. Type",
            'type'        => 'text',
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ]
        ]);
      

       
        $this->crud->addField([

            'label' => 'Business Activity Address (Land Profile)',
            'type' => 'business_main_office',
            'name' => 'buss_activity_address_id',
            'entity' => 'bus_act_address',

            'attribute' => 'full_name',
            'data_source' => url('/admin/api/faas-land/search'),
            'minimum_input_length' => 2,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 buss_act_add position-relative order-2 order-sm-0 ',
            ],
            'tab' => 'Details',
        ]);
        $this->crud->addField([   // phone
            'name'  => 'sec_no', // db column for phone
            'label' => 'Sec No',
            'type'  => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 order-last',
            ],
            'tab' => 'Details',

        ]);
        $this->crud->addField([
            'name'  => 'sec_reg_date',
            'type'  => 'date_picker',
            'label' => 'Sec Reg Date',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'yyyy-mm-dd',
                'language' => 'fr',
                'endDate' => '0d',
                'startDate' => Carbon::now()->subYears(130)->format('Y-m-d'),
            ],
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 order-last'
            ]
         ]);
         $this->crud->addField([   // phone
            'name'  => 'dti_no', // db column for phone
            'label' => 'DTI No',
            'type'  => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 order-last',
            ],
            'tab' => 'Details',

        ]);
        
        $this->crud->addField([
            'name'  => 'dti_reg_date',
            'type'  => 'date_picker',
            'label' => 'DTI Reg Date',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'yyyy-mm-dd',
                'language' => 'fr',
                'endDate' => '0d',
                'startDate' => Carbon::now()->subYears(130)->format('Y-m-d'),
            ],
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 order-last'
            ]
         ]);
         $this->crud->addField([
            'name'        => 'weight_and_measure',
            'label'       => "Weight & Measure (Y/N)",
            'type'        => 'select_from_array',
            'options'     => ['N' => 'No', 'Y' => 'Yes'],
            'allows_null' => FALSE,
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 order-last'
            ]
        ]);
        $this->crud->addField([
            'name'        => 'unit_of_measurement',
            'label'       => "UOM(liter/kilogram)",
            'type'        => 'select_from_array',
            'options'     => ['L' => 'Liter', 'K' => 'Kilogram'],
            'allows_null' => true,
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 order-last'
            ]
        ]);
        $this->crud->addField([
            'name'        => 'weight_and_measure_value',
            'label'       => "Weight & Measure Value*",
            'type'        => 'text',
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 order-last'
            ]
        ]);

         $this->crud->addField([
            'name'        => 'tax_incentives',
            'label'       => "Tax Incentives (Y/N)",
            'type'        => 'select_from_array',
            'options'     => ['Y' => 'Yes', 'N' => 'No'],
            'allows_null' => false,
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 order-last'
            ]
        ]);
        $this->crud->addField([   // Upload
            'name'      => 'certificate',
            'label'     => 'Upload Certificate',
            'type'      => 'upload',
            'upload'    => true,
            'tab' => 'Details',
            'disk' =>'public',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 order-last'
            ]
        ]);
        $buscat = BusinessCategory::all();
        $cat = [];
        foreach($buscat as $busc){
            $cat += [$busc->id => $busc->name];
        }

        $this->crud->addField([   // repeatable
            'name'  => 'line_of_business',
            'label' => '',
            'type'  => 'repeatable',
            'tab' => 'Details',
            'wrapper' => [
                'class' => 'form-group col-12 col-md-12 p-0'
            ],
            'subfields' => [ // also works as: "fields"
                [
                    'name'    => 'particulars',
                    'type'        => 'select',
                    'entity'    => 'businessCategory',
                    'attribute' => 'name',
                    'allows_null' => false,
                   
                    'wrapper' => [
                        'class' => 'form-group col-12 col-md-8 '
                    ]
                ],
                [
                    'name'    => 'capital',
                    'type'    => 'text',
                    'label'   => 'Capital',
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ]
            ],
        
            // optional
            'new_item_label'  => 'Add', // customize the text of the button
            'init_rows' => 1, // number of empty rows to be initialized, by default 1
            'min_rows' =>1, // minimum rows allowed, when reached the "delete" buttons will be hidden
            'max_rows' => 10, // maximum rows allowed, when reached the "new item" button will be hidden
            // allow reordering?
            'reorder' => false, // hide up&down arrows next to each row (no reordering)
        ]);

        $jobcats = BusinessJobCategories::all();
        $jcats = [];
        foreach($jobcats as $jcat){
            $jcats += [$jcat->id => $jcat->name];
        }

        $this->crud->addField([   // repeatable
            'name'  => 'number_of_employees',
            'label' => '',
            'type'  => 'repeatable',
            'tab' => 'Details',
            'wrapper' => [
                'class' => 'form-group col-12 col-md-12 p-0 '
            ],
            'subfields' => [ // also works as: "fields"
                [ 
                    'name'        => 'sex',
                    'label'       => "Sex",
                    'type'        => 'select_from_array',
                    'options'     => ['1' => 'Male', '0' => 'Female'],
                    'allows_null' => false,
                   
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-7'
                    ]
                ],
                [
                    'name'    => 'number',
                    'type'    => 'number',
                    'label'   => 'No',
                    'wrapper' => ['class' => 'form-group col-md-5'],
                ]
            ],
        
            // optional
            'new_item_label'  => 'Add', // customize the text of the button
            'init_rows' => 1, // number of empty rows to be initialized, by default 1
            'min_rows' =>1, // minimum rows allowed, when reached the "delete" buttons will be hidden
            // allow reordering?
            'max_rows' =>2,
            'reorder' => false, // hide up&down arrows next to each row (no reordering)
        ]);
        $this->crud->addField([   // repeatable
            'name'  => 'vehicles',
            'label' => '',
            'type'  => 'repeatable',
            'tab' => 'Details',
            'wrapper' => [
                'class' => 'form-group col-12 col-md-12 p-0 '
            ],
            'subfields' => [ // also works as: "fields"
                [
                    'name'    => 'type',
                    'type'        => 'select',
                    'entity'     => "vehicleType",
                    'attribute' => "name",
                    'wrapper' => [
                        'class' => 'form-group col-12 col-md-8 '
                    ]
                    ],
                [
                    'name'    => 'number',
                    'type'    => 'number',
                    'label'   => 'No',
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ]
            ],
        
            // optional
            'new_item_label'  => 'Add', // customize the text of the button
            'init_rows' => 1, // number of empty rows to be initialized, by default 1
            'min_rows' =>1, // minimum rows allowed, when reached the "delete" buttons will be hidden
            // allow reordering?
            'reorder' => false, // hide up&down arrows next to each row (no reordering)
        ]);

        
      
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

    public function selectionSearch(Request $request){ // This is the function which I want to call from ajax
        //do something awesome with that post data 

        $search_term = $request->input('q');

        if ($search_term)
        {
            $results = BusinessProfiles::select(DB::raw('id, refID, business_name'))
                ->orWhere('refID', 'like', '%'.$search_term.'%')
                ->orWhere('business_name', 'like', '%'.$search_term.'%')
                ->where('isActive', '=', 'Y') 
                ->orderBy('business_name','ASC')
                ->get();
        }
        else
        {
            $results = BusinessProfiles::select(DB::raw('id, refID, business_name'))
                ->where('isActive', '=', 'Y')
                ->orderBy('business_name','ASC')->paginate(10);
        }

        return $results;
    }
}
