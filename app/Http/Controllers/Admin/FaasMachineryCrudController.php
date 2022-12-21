<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FaasMachineryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use App\Models\FaasMachinery;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionLogs;
use Illuminate\Http\Request;
use App\Models\CitizenProfile;

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
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view-faas-machineries', ['only' => ['index','show']]);
        $this->middleware('can:create-faas-machineries', ['only' => ['create','store']]);
        $this->middleware('can:edit-faas-machineries', ['only' => ['edit','update']]);
        $this->middleware('can:delete-faas-machineries', ['only' => ['destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\FaasMachinery::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/faas-machinery');
        $this->crud->setEntityNameStrings('machinery', 'machineries');
        $this->crud->removeButton('delete');

        Widget::add()->type('style')->content('assets/css/faas/styles.css');
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');
        Widget::add()->type('script')->content('assets/js/jquery.inputmask.bundle.min.js');
        Widget::add()->type('script')->content('assets/js/backpack/crud/inputmask.js');
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
            'name'      => 'refID', // the db column for the foreign key
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('faas-machinery.edit',$entry->id);
                },
            ],
        ]);
        $this->crud->addColumn([
            'label'     => 'Land Reference ID',
            'type'      => 'text',
            'name'      => 'land_profile.refID',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('faas-land.edit',$entry->landProfileId);
                },
            ]
        ]);
        $this->crud->addColumn([
            'label'     => 'Building Reference ID',
            'type'      => 'text',
            'name'      => 'building_profile.refID',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('building-profile.edit',$entry->buildingProfileId);
                },
            ]
        ]);
        CRUD::column('model_function')
        ->type('model_function')
        ->label('Primary Owner')
        ->function_name('getPrimaryOwner')
        ->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhereHas('citizen_profile', function ($q) use ($column, $searchTerm) {
                $q->where('fName', 'like', '%'.$searchTerm.'%');
                $q->orWhere('mName', 'like', '%'.$searchTerm.'%');
                $q->orWhere('lName', 'like', '%'.$searchTerm.'%');
            })
            ->orWhereHas('name_profile', function ($q) use ($column, $searchTerm) {
                $q->where('first_name', 'like', '%'.$searchTerm.'%');
                $q->orWhere('middle_name', 'like', '%'.$searchTerm.'%');
                $q->orWhere('last_name', 'like', '%'.$searchTerm.'%');
            });
        });
        $this->crud->column('ownerAddress')->limit(255)->label('Owner Address');
        $this->crud->addColumn([
            'name'  => 'isActive',
            'label' => 'Status',
            'type'  => 'boolean',
            'options' => [0 => 'Inactive', 1 => 'Active'],
        ]);
        $this->crud->column('created_at')->label('Date Created');
        $this->crud->orderBy('refID','ASC');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(FaasMachineryRequest::class);

        /*Main Information*/
        $this->crud->addField([
            'name'=>'pin',
            'type'=>'text',
            'label'=>'PIN',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);

        $this->crud->addField([
            'name'  => 'separator00',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'  => 'Main Information',
        ]);

        $this->crud->addField([
            'label' => 'Land Profile',
            'type' => 'land_profile_selection',
            'name' => 'landProfileId',
            'entity' => 'land_profile',
            'attribute' => 'refID',
            'data_source' => url('/admin/api/faas-land/search-land-profile'),
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator5a',
            'type'  => 'custom_html',
            'value' => '<label class="selectedLandProfileLabel">View Selected Land Profile</label><div class="selectedLandProfile" id="selectedLandProfile"></div>',
            'tab' => 'Main Information',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 hidden selectedLandProfileWrapper',
            ],
        ]);
        $this->crud->addField([
            'label' => 'Building Profile',
            'type' => 'building_profile_selection',
            'name' => 'buildingProfileId',
            'entity' => 'building_profile',
            'attribute' => 'refID',
            'data_source' => url('/admin/api/faas-building/search-building-profile'),
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator5b',
            'type'  => 'custom_html',
            'value' => '<label class="selectedBuildingProfileLabel">View Selected Building Profile</label><div class="selectedBuildingProfile" id="selectedBuildingProfile"></div>',
            'tab' => 'Main Information',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 hidden selectedBuildingProfileWrapper',
            ],
        ]);
        
        $this->crud->addField([
            'name'  => 'separator0',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'  => 'Main Information',
        ]);
        /*$this->crud->addField([
            'label' => 'Primary Owner',
            'type' => 'primary_owner_union',
            'name' => 'primaryOwnerId',
            'entity' => 'citizen_profile',
            'attribute' => 'full_name',
            'data_source' => url('/admin/api/citizen-profile/search-primary-owner'),
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Main Information',
        ]);*/
        $id = $this->crud->getCurrentEntryId();
        if ($id != false) {
            $data = FaasMachinery::where('id', $id)->first();
            $primaryOwnerId = $data->primaryOwnerId;
            $ownerExist  = CitizenProfile::where("id", $primaryOwnerId)->count();
            if ($ownerExist == 0) {
                $this->crud->addField([
                    'label' => 'Primary Owner',
                    'type' => 'primary_owner_union',
                    'name' => 'primaryOwnerId',
                    'entity' => 'name_profile',
                    'attribute' => 'full_name',
                    'data_source' => url('/admin/api/citizen-profile/search-primary-owner'),
                    'minimum_input_length' => 1,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-6 primaryOwnerId_select'
                    ],
                    'tab' => 'Main Information',
                ]);
            }
            else {
                $this->crud->addField([
                    'label' => 'Primary Owner',
                    'type' => 'primary_owner_union',
                    'name' => 'primaryOwnerId',
                    'entity' => 'citizen_profile',
                    'attribute' => 'full_name',
                    'data_source' => url('/admin/api/citizen-profile/search-primary-owner'),
                    'minimum_input_length' => 1,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-6 primaryOwnerId_select'
                    ],
                    'tab' => 'Main Information',
                ]);
            }
        }
        else {
            $this->crud->addField([
                'label' => 'Primary Owner',
                'type' => 'primary_owner_union',
                'name' => 'primaryOwnerId',
                'entity' => 'citizen_profile',
                'attribute' => 'full_name',
                'data_source' => url('/admin/api/citizen-profile/search-primary-owner'),
                'minimum_input_length' => 1,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6 primaryOwnerId_select'
                ],
                'tab' => 'Main Information',
            ]);
        }
        $this->crud->addField([
            'name' => 'machinery_owner',
            'label' => 'Secondary Owner/s',
            'type' => 'secondary_owner',
            'entity' => 'machinery_owner',
            'data_source' => url('/admin/api/citizen-profile/search-secondary-owners'),
            'attribute' => 'full_name',
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerAddress',
            'label'=>'Address',
            'type'=>'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerTelephoneNo',
            'label'=>'Telephone No.',
            'type'=>'text',
            'attributes' => [
                'class' => 'form-control',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerTin',
            'label'=>'TIN No.',
            'attributes' => [
                'class' => 'form-control text_input_mask_tin',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator1',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'  => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administrator',
            'label'=>'Administrator/Occupant',
            'type'=>'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorAddress',
            'label'=>'Address',
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorTelephoneNo',
            'label'=>'Telephone No.',
            'type'=>'text',
            'attributes' => [
                'class' => 'form-control',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorTin',
            'label'=>'TIN No.',
            'attributes' => [
                'class' => 'form-control text_input_mask_tin',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator2a',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'isActive',
            'label'=>'Status',
            'type' => 'select_from_array',
            'options' => [
                1 => 'Active', 
                0 => 'Inactive'
            ],
            'allows_null' => false,
            'default'     => 'Y',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);

        /*Property Location*/
        /*$this->crud->addField([
            'name'=>'noOfStreet',
            'label'=>'No. of Street',
            'type'=>'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'=>'barangayId',
            'label'=>'Barangay/District',
            'type'=>'select',
            'entity' => 'barangay',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'=>'cityId_fake',
            'label' => "Municipality",
            'type'=>'text',
            'value' => 'Trece Martires City',
            'fake' => true,
            'attributes' => [
                'readonly' => 'readonly',
            ], 
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'  => 'cityId',
            'type'  => 'hidden',
            'value' => 'db3510e6-3add-4d81-8809-effafbbaa6fd',
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'=>'provinceId_fake',
            'label' => "Province",
            'type'=>'text',
            'value' => 'Cavite',
            'fake' => true,
            'attributes' => [
                'readonly' => 'readonly',
            ], 
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'  => 'provinceId',
            'type'  => 'hidden',
            'value' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3',
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'label' => "Barangay/District",
            'type'=>'select',
            'name'=>'barangay_code',
            'entity' => 'barangay',
            'attribute' => 'code',
            'attributes' => [
                'class' => 'form-control',
                'readonly' => 'readonly',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden',
            ],
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'  => 'barangay_code_text',
            'type'  => 'hidden',
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'name'  => 'separator2',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Property Location',
        ]);
        $this->crud->addField([
            'label' => 'Land Owner',
            'type' => 'primary_owner_input',
            'name' => 'landOwnerId',
            'entity' => 'land_owner_citizen_profile',
            'attribute' => 'full_name',
            'data_source' => url('/admin/api/citizen-profile/search-secondary-owners'),
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
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
            'label' => 'Building Owner',
            'type' => 'primary_owner_input',
            'name' => 'buildingOwnerId',
            'entity' => 'building_owner_citizen_profile',
            'attribute' => 'full_name',
            'data_source' => url('/admin/api/citizen-profile/search-secondary-owners'),
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
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
        ]);*/

        /*Property Appraisal*/
        /*$this->crud->addField([   
            'name'  => 'propertyAppraisal',
            'label' => 'Property Appraisal',
            'type'  => 'repeatable',
            'subfields' => [
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
                    'type'  => 'text',
                    'label' => 'Date Acquired',
                    'hint' => '(Year)',
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
                    'type'    => 'number',
                    'label'   => 'Economic Life - Estimated',
                    'hint'    => '(No. of Years)',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'economicLifeRemain',
                    'type'    => 'number',
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
                    'name'  => 'separator2a',
                    'type'  => 'custom_html',
                    'value' => '<hr>',
                ],
                [
                    'name'  => 'originalCost',
                    'type'  => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency originalCost',
                    ],
                    'label' => 'Original Cost',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'conversionFactor',
                    'type'  => 'text',
                    'label' => 'Conversion Factor',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'rcn',
                    'type'  => 'text',
                    'label' => 'RCN',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'noOfYearsUsed',
                    'type'  => 'number',
                    'label' => 'No. of Years Used',
                    'attributes' => [
                        'class' => 'form-control noOfYearsUsed',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'rateOfDepreciation',
                    'type'  => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_percent rateOfDepreciation',
                    ],
                    'label' => 'Rate of Depreciation',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'totalDepreciationPercentage',
                    'type'  => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_percent totalDepreciationPercentage',
                        'readonly' => 'readonly'
                    ],
                    'label' => 'Total Depreciation - %',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'totalDepreciationValue',
                    'type'  => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency totalDepreciationValue',
                        'readonly' => 'readonly'
                    ],
                    'label' => 'Total Depreciation - Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'depreciatedValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency depreciatedValue',
                        'readonly' => 'readonly'
                    ],
                    'label' => 'Depreciated Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ]
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => true,
            'tab' => 'Property Appraisal',
        ]);
        $this->crud->addField([
            'name'  => 'separator2b',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Property Appraisal',
        ]);
        $this->crud->addField([
            'name'=>'totalOriginalCost',
            'label'=>'TOTAL (Original Cost)',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Appraisal',
        ]);
        $this->crud->addField([
            'name'=>'totalTotalDepreciationValue',
            'label'=>'TOTAL (Total Depreciation Value)',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Appraisal',
        ]);
        $this->crud->addField([
            'name'=>'totalDepreciatedValue',
            'label'=>'TOTAL (Depreciated Value)',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Appraisal',
        ]);*/

        FaasMachinery::creating(function($entry) {
            $count = FaasMachinery::count();
            $refID = 'MACHINERY-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $request = app(FaasMachineryRequest::class);

            $ARPNo = 'ARP-MCHN-'.str_pad(($count), 5, "0", STR_PAD_LEFT);
            $entry->ARPNo = $ARPNo;

            TransactionLogs::create([
                'transId' =>$refID,
                'category' =>'faas_machinery',
                'type' =>'create',
            ]);
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

        FaasMachinery::updating(function($entry) {
          
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'faas_machinery',
                'type' =>'update',
            ]);
        });
    }

    public function getDetails(Request $request){
        $id = $request->input('id');
        $results = [];
        if (!empty($id))
        {
            $citizenProfiles = FaasMachinery::select('faas_machineries.*', 'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'))
            ->join('citizen_profiles', 'faas_machineries.primaryOwnerId', '=', 'citizen_profiles.id')
            ->with('citizen_profile')
            ->with('barangay')
            ->with('machinery_owner')
            ->with('land_owner_citizen_profile')
            ->with('building_owner_citizen_profile')
            ->where('faas_machineries.isActive', '=', '1')
            ->where('faas_machineries.id', '=', $id)
            ->get();

            $nameProfiles = FaasMachinery::select('faas_machineries.*', 'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'))
            ->join('name_profiles', 'faas_machineries.primaryOwnerId', '=', 'name_profiles.id')
            ->with('name_profile')
            ->with('barangay')
            ->with('machinery_owner')
            ->with('land_owner_citizen_profile')
            ->with('building_owner_citizen_profile')
            ->where('faas_machineries.isActive', '=', '1')
            ->where('faas_machineries.id', '=', $id)
            ->get();

            $results = $citizenProfiles->merge($nameProfiles);
        }

        return $results;
    }

    public function getSecondaryOwners(Request $request){
        $machinery_profile_id = $request->input('machinery_profile_id');
        $results = [];
        if (!empty($machinery_profile_id))
        {
            $results = DB::table('faas_machinery_secondary_owners')
            ->join('citizen_profiles', 'faas_machinery_secondary_owners.citizen_profile_id', '=', 'citizen_profiles.id')
            ->select('faas_machinery_secondary_owners.*', 'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address')
            ->where('faas_machinery_secondary_owners.machinery_profile_id', '=', $machinery_profile_id)
            ->get();
        }

        return $results;
    }

    public function create()
    {
        Widget::add()->type('script')->content('assets/js/faas/create-machinery-functions.js');
        $this->crud->hasAccessOrFail('create');
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;
        return view('faas.machinery.create', $this->data);
    }

    public function edit($id)
    {
        Widget::add()->type('script')->content('assets/js/faas/edit-machinery-functions.js');
        $this->crud->hasAccessOrFail('update');
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntryWithLocale($id);
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id'] = $id;

        return view('faas.machinery.edit', $this->data);
    }
    
}
