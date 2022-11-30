<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BuildingProfileRequest;
use App\Models\BuildingProfile;
use App\Models\CitizenProfile;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionLogs;
use Backpack\CRUD\app\Library\Widget;
use App\Models\StructuralRoofs;
use Illuminate\Http\Request;

/**
 * Class BuildingProfileCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BuildingProfileCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view-building-profiles', ['only' => ['index','show']]);
        $this->middleware('can:create-building-profiles', ['only' => ['create','store']]);
        $this->middleware('can:edit-building-profiles', ['only' => ['edit','update']]);
        $this->middleware('can:delete-building-profiles', ['only' => ['destroy']]);
    }
    
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\BuildingProfile::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/building-profile');
        $this->crud->setEntityNameStrings('building profile', 'building profiles');
        $this->crud->removeButton('delete');

        Widget::add()->type('style')->content('assets/css/faas/styles.css');
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');
        Widget::add()->type('style')->content('assets/css/faas/building/styles.css');
        Widget::add()->type('script')->content('assets/js/jquery.inputmask.bundle.min.js');
        Widget::add()->type('script')->content('assets/js/backpack/crud/inputmask.js');
        Widget::add()->type('script')->content('assets/js/faas/building/functions.js');
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
                    return route('building-profile.edit',$entry->id);
                },
            ]
        ]);
        //$this->crud->column('ARPNo')->label('ARP No.');
        $this->crud->addColumn([
            'name'  => 'primary_owner',
            'label' => 'Primary Owner',
            'type'  => 'select',
            'entity'    => 'citizen_profile',
            'attribute' => 'full_name'
        ]);
        $this->crud->column('ownerAddress')->limit(255)->label('Owner Address');
        $this->crud->addColumn([
            'name'  => 'isApproved',
            'label' => 'Approved',
            'type'  => 'boolean',
            'options' => [0 => 'No', 1 => 'Yes'],
            'wrapper' => [
                'element' => 'span',
                'class'   => function ($crud, $column, $entry, $related_key) {
                    if ($column['text'] == 'Yes') {
                        return 'badge badge-success';
                    }
                    return 'badge badge-default';
                },
            ],
        ]);
        $this->crud->addColumn([
            'name'  => 'isActive',
            'label' => 'Status',
            'type'  => 'boolean',
            'options' => [0 => 'Inactive', 1 => 'Active'],
        ]);
        $this->crud->orderBy('refID','ASC');
    }
    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }


    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(BuildingProfileRequest::class);
        
        /*Main Information*/
        /*$this->crud->addField([
            'label' => 'Transaction Code',
            'type' => 'text',
            'name' => 'transactionCode',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator0',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'  => 'Main Information',
        ]);*/
        $this->crud->addField([
            'label' => 'Primary Owner',
            'type' => 'primary_owner_union',
            'name' => 'primary_owner',
            'entity' => 'citizen_profile',
            'attribute' => 'full_name',
            'data_source' => url('/admin/api/citizen-profile/search-primary-owner'),
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'building_owner',
            'label' => 'Secondary Owner',
            'type' => 'secondary_owner',
            'entity' => 'building_owner',
            'data_source' => url('/admin/api/citizen-profile/search-secondary-owners'),
            'attribute' => 'full_name',
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'ownerAddress', 
            'label' => 'Address', 
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12 ownerAddress',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerAddress_fake',
            'label'=>'Address <span style="color:red;">*</span>',
            'type' => 'select_from_array',
            'options'     => [
                '' => '-',
            ],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12 ownerAddress_fake hidden'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'tel_no', 
            'label' => 'Telephone No.', 
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'owner_tin_no', 
            'label' => 'TIN No.', 
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_tin',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
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
            'name' => 'administrator',
            'label' => 'Administrator/Occupant',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'admin_address',
            'label' => 'Address',
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12',
            ],
            'tab'  => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'admin_tel_no',
            'label' => 'Telephone No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'admin_tin_no',
            'label' => 'TIN No.',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_tin',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
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

        /*Building Location Tab*/
        $this->crud->addField([
            'name' => 'no_of_street',
            'label' => 'No. of Street',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Building Location',
        ]);
        $this->crud->addField([
            'label' => "Barangay/District",
            'type'=>'select',
            'name'=>'barangay_id',
            'entity' => 'barangay',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Building Location',
        ]);
        $this->crud->addField([
            'name'=>'municipality_id_fake',
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
            'tab' => 'Building Location',
        ]);
        $this->crud->addField([
            'name'  => 'municipality_id',
            'type'  => 'hidden',
            'value' => 'db3510e6-3add-4d81-8809-effafbbaa6fd',
            'tab' => 'Building Location',
        ]);
        $this->crud->addField([
            'name'=>'province_id_fake',
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
            'tab' => 'Building Location',
        ]);
        $this->crud->addField([
            'name'  => 'province_id',
            'type'  => 'hidden',
            'value' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3',
            'tab' => 'Building Location',
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
            'tab' => 'Building Location',
        ]);
        $this->crud->addField([
            'name'  => 'barangay_code_text',
            'type'  => 'hidden',
            'tab' => 'Building Location',
        ]);
        /*Land Reference Tab*/
        $this->crud->addField([
            'name' => 'oct_tct_no',
            'label' => 'OCT/TCT No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Land Reference',
        ]);
        $this->crud->addField([
            'name' => 'survey_no',
            'label' => 'Survey No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Land Reference',
        ]);
        $this->crud->addField([
            'name'  => 'separator2b',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Land Reference',
        ]);
        $this->crud->addField([
            'name' => 'lot_no',
            'label' => 'Lot No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Land Reference',
        ]);
        $this->crud->addField([
            'name' => 'block_no',
            'label' => 'Block No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Land Reference',
        ]);
        $this->crud->addField([
            'name' => 'area',
            'label' => 'Area',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Land Reference',
        ]);
        /*General Description*/
        $this->crud->addField([
            'label' => "Kind of Building",
            'type'=>'select',
            'name'=>'kind_of_building_id',
            'model'     => "App\Models\FaasBuildingClassifications",
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'buildingAge',
            'label' => 'Building Age',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'label' => "Structural Type",
            'type'=>'select',
            'name'=>'structural_type_id',
            'entity' => 'structural_type',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'label' => "Kind of Building",
            'type'=>'select',
            'name'=>'kind_of_building_code',
            'model'     => "App\Models\FaasBuildingClassifications",
            'attribute' => 'code',
            'attributes' => [
                'class' => 'form-control',
                'readonly' => 'readonly',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 hidden',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name'  => 'kind_of_building_code_text',
            'type'  => 'hidden',
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'building_permit_no',
            'label' => 'Building Permit No',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'building_permit_date_issued',
            'label' => 'Building Permit Date Issued',
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'yyyy-mm-dd',
                'language' => 'fr',
                'endDate' => '0d',
                //'startDate' => Carbon::now()->subYears(130)->format('Y-m-d')
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name'  => 'separator2c',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'condominium_certificate_of_title',
            'label' => 'Condominium Certificate of Title (CCT)',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
            'tab'  => 'General Description',
        ]);
        $this->crud->addField([
            'name'  => 'separator2ca',
            'type'  => 'custom_html',
            'value' => '',
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'certificate_of_completion_issued_on',
            'label' => 'Certificate of Completion Issued On',
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'yyyy-mm-dd',
                'language' => 'fr',
                'endDate' => '0d',
                //'startDate' => Carbon::now()->subYears(130)->format('Y-m-d')
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'certificate_of_occupancy_issued_on',
            'label' => 'Certificate of Occupancy Issued On',
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'yyyy-mm-dd',
                'language' => 'fr',
                'endDate' => '0d',
                //'startDate' => Carbon::now()->subYears(130)->format('Y-m-d')
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'date_constructed',
            'label' => 'Date Constructed',
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'yyyy-mm-dd',
                'language' => 'fr',
                'endDate' => '0d',
                //'startDate' => Carbon::now()->subYears(130)->format('Y-m-d')
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'date_occupied',
            'label' => 'Date Occupied',
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'yyyy-mm-dd',
                'language' => 'fr',
                'endDate' => '0d',
                //'startDate' => Carbon::now()->subYears(130)->format('Y-m-d')
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name'  => 'separator2',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'   => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'no_of_storeys',
            'label' => 'No. of Storeys',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([   
            'name'  => 'floorsArea',
            'label' => 'Area',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'=>'floorNo_fake',
                    'label' => "Floor",
                    'type'=>'text',
                    'fake' => true,
                    'attributes' => [
                        'readonly' => 'readonly',
                    ], 
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-3',
                    ],
                ],
                [
                    'name'    => 'floorNo',
                    'type'    => 'hidden',
                ],
                [
                    'name'    => 'area',
                    'type'    => 'text',
                    'label'   => 'Area',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                    ],
                ],
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => true,
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name'  => 'separator2d',
            'type'  => 'custom_html',
            'value' => '',
            'tab'   => 'General Description',
        ]);
        
        $this->crud->addField([
            'name'  => 'separator2e',
            'type'  => 'custom_html',
            'value' => '',
            'tab'   => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'totalFloorArea',
            'label' => 'Total Floor Area',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        
        $this->crud->addField([
            'label' => "Roof",
            'type'=>'select',
            'name'=>'roof',
            'model'     => "App\Models\StructuralRoofs",
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Structural Characteristic',
        ]);
        $this->crud->addField([
            'name' => 'other_roof',
            'label' => 'Please Specify',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden other_roof ',
            ],
            'tab' => 'Structural Characteristic',
        ]);
        $this->crud->addField([
            'name'  => 'separator3',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'   => 'Structural Characteristic',
        ]);
        
        $this->crud->addField([   
            'name'  => 'flooring',
            'label' => 'Flooring',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'=>'floorNo_fake',
                    'label' => "Floor",
                    'type'=>'text',
                    'fake' => true,
                    'attributes' => [
                        'readonly' => 'readonly',
                    ], 
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-3',
                    ],
                ],
                [
                    'name'    => 'floorNo',
                    'type'    => 'hidden',
                ],
                [
                    'name'    => 'type',
                    'type' => 'select',
                    'model'     => "App\Models\StructuralFlooring",
                    'attribute' => 'name',
                    'label'   => 'Type',
                    'wrapper' => ['class' => 'form-group col-md-3 type'],
                ],
                [
                    'name'    => 'others',
                    'type'    => 'text',
                    'label'   => 'Please Specify',
                    'wrapper' => ['class' => 'form-group col-md-3 others hidden'],
                ],
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => true,
            'tab' => 'Structural Characteristic',
        ]);
        $this->crud->addField([   
            'name'  => 'walling',
            'label' => 'Walling',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'=>'floorNo_fake',
                    'label' => "Floor",
                    'type'=>'text',
                    'fake' => true,
                    'attributes' => [
                        'readonly' => 'readonly',
                    ], 
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-3',
                    ],
                ],
                [
                    'name'    => 'floorNo',
                    'type'    => 'hidden',
                ],
                [
                    'name'    => 'type',
                    'type' => 'select',
                    'model'     => "App\Models\StructuralWalling",
                    'attribute' => 'name',
                    'label'   => 'Type',
                    'wrapper' => ['class' => 'form-group col-md-3 type'],
                ],
                [
                    'name'    => 'others',
                    'type'    => 'text',
                    'label'   => 'Please Specify',
                    'wrapper' => ['class' => 'form-group col-md-3 others hidden'],
                ],
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => true,
            'tab' => 'Structural Characteristic',
        ]);
        /*Additional Items (Repeatable)*/
        $this->crud->addField([   
            'name'  => 'additionalItems',
            'label' => 'Additional Items',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'additionalItem1',
                    'type'    => 'text',
                    'label'   => '',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'additionalItem2',
                    'type'    => 'text',
                    'label'   => '',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'additionalItem3',
                    'type'    => 'text',
                    'label'   => '',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'additionalItem4',
                    'type'    => 'text',
                    'label'   => '',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ]
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => true,
            'tab' => 'Structural Characteristic',
        ]);
        /*Property Appraisal*/
        $this->crud->addField([
            'name'  => 'separator5',
            'type'  => 'custom_html',
            'value' => '<p>Unit Construction Cost: Php - <input type="text" class="simple-form-input text_input_mask_currency" name="unitConstructionCost_temp" id="unitConstructionCost_temp" value="" /> /sq.m.</p>
                <p>Building Core: <i>(Use additional sheets if necessary)</i></p>
                <p><b>Sub-Total: Php - </b> <input type="text" class="simple-form-input text_input_mask_currency" name="unitConstructionSubTotal_temp" id="unitConstructionSubTotal_temp" value="" readonly="readonly" /> </p>',
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator6',
            'type'  => 'custom_html',
            'value' => '<p>Cost of Additional Items:</p>
                <br><br>
                <p><b>Sub-Total: Php - </b> <input type="text" class="simple-form-input text_input_mask_currency" name="costOfAdditionalItemsSubTotal_temp" id="costOfAdditionalItemsSubTotal_temp" value="" /> </p>',
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator6a',
            'type'  => 'custom_html',
            'value' => '<p><b>TOTAL CONSTRUCTION COST: Php - </b> <input type="text" class="simple-form-input text_input_mask_currency" name="totalConstructionCost_temp" id="totalConstructionCost_temp" value="" readonly="readonly" /> </p>',
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
        ]);
        $this->crud->addField([
            'name'  => 'unitConstructionCost',
            'type'  => 'hidden',
            'tab' => 'Property Appraisal',
        ]);
        $this->crud->addField([
            'name'  => 'unitConstructionSubTotal',
            'type'  => 'hidden',
            'tab' => 'Property Appraisal',
        ]);
        $this->crud->addField([
            'name'  => 'costOfAdditionalItemsSubTotal',
            'type'  => 'hidden',
            'tab' => 'Property Appraisal',
        ]);
        $this->crud->addField([
            'name'  => 'totalConstructionCost',
            'type'  => 'hidden',
            'tab' => 'Property Appraisal',
        ]);
        $this->crud->addField([
            'name'  => 'separator8',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Property Appraisal',
        ]);
        $this->crud->addField([   
            'name'  => 'depreciationRate',
            'label' => 'Depreciation Rate',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_percent',
            ],
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([   
            'name'  => 'depreciationCost',
            'label' => 'Depreciation Cost',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
            ],
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([   
            'name'  => 'totalPercentDepreciation',
            'label' => 'Total % Depreciation',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_percent',
            ],
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([   
            'name'  => 'marketValue',
            'label' => 'Market Value',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly',
            ],
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        /*Property Assessment*/
        /*$this->crud->addField([   
            'name'  => 'propertyAssessment',
            'label' => 'Property Assessment',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'actualUse',
                    'type'    => 'select',
                    'label'   => 'Actual Use',
                    'model'     => "App\Models\FaasBuildingClassifications",
                    'attribute' => 'name',
                    'attributes' => [
                        'readonly' => 'readonly',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3 actualUse'],
                ],
                [
                    'name'    => 'marketValue',
                    'type'    => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                        'readonly' => 'readonly',
                    ],
                    'label'   => 'Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3 marketValue'],
                ],
                [
                    'name'    => 'assessmentLevel',
                    'type'    => 'select',
                    'label'   => 'Assessment Level',
                    'model'     => "App\Models\FaasBuildingClassifications",
                    'attribute' => 'assessmentLevel',
                    'attributes' => [
                        'readonly' => 'readonly',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3 assessmentLevel'],
                ],
                [
                    'name'  => 'assessedValue',
                    'type'  => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                        'readonly' => 'readonly',
                    ],
                    'label' => 'Assessed Value',
                    'wrapper' => ['class' => 'form-group col-md-3 assessedValue'],
                ],
                [
                    'name'  => 'yearOfEffectivity',
                    'type'  => 'text',
                    'label' => 'Year of Effectivity',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
            ],
            'new_item_label'  => 'New Item', 
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 1,
            'reorder' => true,
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'  => 'separator9',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'=>'assessmentType',
            'label'=>'Assessment Type',
            'type' => 'select_from_array',
            'options' => [
                'Taxable' => 'Taxable', 
                'Exempt' => 'Exempt'
            ],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Property Assessment',
        ]);

        $this->crud->addField([
            'name'=>'assessmentEffectivity',
            'label'=>'Effectivity of Assessment/Reassessment',
            'type' => 'select_from_array',
            'options'     => [
                "Quarter" => "Quarter",
                "Year" => "Year"
            ],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Property Assessment',
        ]);
        
        $this->crud->addField([
            'name'=>'assessmentEffectivityValue_select_fake',
            'label'=>'Effectivity of Assessment/Reassessment Value <span style="color:red;">*</span>',
            'type' => 'select_from_array',
            'options'     => [
                "1st Quarter" => "1st Quarter",
                "2nd Quarter" => "2nd Quarter",
                "3rd Quarter" => "3rd Quarter",
                "4th Quarter" => "4th Quarter"
            ],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 assessmentEffectivityValue_select_fake'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'=>'assessmentEffectivityValue_input_fake',
            'label'=>'Effectivity of Assessment/Reassessment Value <span style="color:red;">*</span>',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 hidden assessmentEffectivityValue_input_fake'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'=>'assessmentEffectivityValue',
            'type' => 'hidden',
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'  => 'ifAssessmentTypeIsExempt',
            'type'  => 'custom_html',
            'value' => '<div class="alert alert-warning" role="alert">This property needs to go through an approval process.</div>',
            'tab' => 'Property Assessment',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 hidden ifAssessmentTypeIsExempt',
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator10',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'=>'memoranda',
            'label'=>'Memoranda',
            'type'=>'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'  => 'separator10a',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'  => 'isApproved',
            'label' => 'Approve',
            'type'  => 'checkbox',
            'attributes' => [
                'class' => 'isApproved_checkbox'
            ],
            'tab'  => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'=>'approvedBy',
            'label'=>'Approved By',
            'type'=>'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 approve_items hidden'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'=>'approvedDate',
            'label'=>'Approved Date',
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'yyyy-mm-dd',
                'language' => 'fr',
                'endDate' => '0d',
                //'startDate' => Carbon::now()->subYears(130)->format('Y-m-d')
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 approve_items hidden'
            ],
            'tab' => 'Property Assessment',
        ]);*/

        BuildingProfile::creating(function($entry) {
            $count = BuildingProfile::count();
            $refID = 'BUILDING-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $request = app(BuildingProfileRequest::class);

            /*$ARPNo = 'ARP-BLDG-'.$request->barangay_code_text.'-01-'.str_pad(($count), 5, "0", STR_PAD_LEFT).'-'.$request->kind_of_building_code_text;
            $entry->ARPNo = $ARPNo;*/
            
            /*$TDNo = 'TD-BLDG-'.$request->barangay_code_text.'-01-'.str_pad(($count), 5, "0", STR_PAD_LEFT).'-'.$request->kind_of_building_code_text;
            $entry->TDNo = $TDNo;*/

            TransactionLogs::create([
                'transId' =>$refID,
                'category' =>'faas_building',
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
        /*$this->crud->addField([
            'name'=>'ARPNo',
            'label' => "ARP No.",
            'type'=>'text',
            'fake' => true,
            'attributes' => [
                'readonly' => 'readonly',
            ], 
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Main Information',
        ]);

        $this->crud->addField([
            'name'=>'TDNo',
            'label' => "TD No.",
            'type'=>'text',
            'fake' => true,
            'attributes' => [
                'readonly' => 'readonly',
            ], 
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Main Information',
        ]);*/

        $this->setupCreateOperation();

        BuildingProfile::updating(function($entry) {
          
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'faas_building',
                'type' =>'update',
            ]);
        });
       
    }

    public function getDetails(Request $request){
        $id = $request->input('id');
        $results = [];
        if (!empty($id))
        {
            $citizenProfiles = DB::table('faas_building_profiles')
            ->join('citizen_profiles', 'faas_building_profiles.primary_owner', '=', 'citizen_profiles.id')
            ->select('faas_building_profiles.*', 'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'))
            ->where('faas_building_profiles.isActive', '=', '1')
            ->where('faas_building_profiles.id', '=', $id)
            ->get();
            
            $nameProfiles = DB::table('faas_building_profiles')
            ->join('name_profiles', 'faas_building_profiles.primary_owner', '=', 'name_profiles.id')
            ->select('faas_building_profiles.*', 'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'))
            ->where('faas_building_profiles.isActive', '=', '1')
            ->where('faas_building_profiles.id', '=', $id)
            ->get();

            $results = $citizenProfiles->merge($nameProfiles);
        }

        return $results;
    }

    public function getSecondaryOwners(Request $request){
        $building_profile_id = $request->input('building_profile_id');
        $results = [];
        if (!empty($building_profile_id))
        {
            $results = DB::table('faas_building_profile_secondary_owners')
            ->join('citizen_profiles', 'faas_building_profile_secondary_owners.citizen_profile_id', '=', 'citizen_profiles.id')
            ->select('faas_building_profile_secondary_owners.*', 'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address')
            ->where('faas_building_profile_secondary_owners.building_profile_id', '=', $building_profile_id)
            ->get();
        }

        return $results;
    }

}