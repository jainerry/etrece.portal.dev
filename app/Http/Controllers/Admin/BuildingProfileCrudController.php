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
        $this->crud->setCreateView('buildingProfile.create');
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
                    return route('faas-machinery.edit',$entry->id);
                },
            ]
        ]);
        $this->crud->addColumn([
            'name'  => 'primary_owner',
            'label' => 'Primary Owner',
            'type'  => 'select',
            'entity'    => 'citizen_profile',
            'attribute' => 'full_name'
        ]);
        $this->crud->column('ownerAddress')->limit(255)->label('Owner Address');
        $this->crud->addColumn([
            'label'=>'Status',
            'type'  => 'model_function',
            'function_name' => 'getStatus',
        ]);
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
        
        /*Main Information Tab*/
        $this->crud->addField([
            'label' => 'ARP No.',
            'type' => 'text',
            'name' => 'arpNo',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'label' => 'Transaction Code',
            'type' => 'text',
            'name' => 'code',
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
        ]);
        $this->crud->addField([
            'label' => 'Primary Owner',
            'type' => 'primary_owner_input',
            'name' => 'primary_owner',
            'entity' => 'citizen_profile',
            'attribute' => 'full_name',
            'data_source' => url('/admin/api/cp/search'),
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
            'data_source' => url('/admin/api/cp/search'),
            'attribute' => 'full_name',
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
                'class' => 'form-group col-12 col-md-12',
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
                'Y' => 'Active', 
                'N' => 'Inactive'
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
            'name' => 'tdn_arpNo',
            'label' => 'TDN/ARP No.',
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
            'entity' => 'kind_of_building',
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
            'label' => 'Building Permit Date No',
            'type' => 'date',
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
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'certificate_of_occupancy_issued_on',
            'label' => 'Certificate of Occupancy Issued On',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'date_constructed',
            'label' => 'Date Constructed',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'date_occupied',
            'label' => 'Date Occupied',
            'type' => 'date',
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
            'name'  => 'separator2d',
            'type'  => 'custom_html',
            'value' => '',
            'tab'   => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'area_first_floor',
            'label' => 'Area of 1st Floor',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency nth-floor-area',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'area_second_floor',
            'label' => 'Area of 2nd Floor',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency nth-floor-area',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab'=> 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'area_third_floor',
            'label' => 'Area of 3rd Floor',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency nth-floor-area',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'area_fourth_floor',
            'label' => 'Area of 4th Floor',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency nth-floor-area',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
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
                'readonly' => 'readonly',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        /*Structural Characteristic*/
        /*$this->crud->addField([
            'label'     => 'Roof',
            'type'      => 'radio',
            'name'      => 'roof',
            'entity'    => 'roof',
            'attribute' => 'name',
            'model'     => "App\Models\StructuralRoofs",
            'pivot'     => true,
            'attributes' => [
                'class' => 'structural-roof-checklist',
            ],
            'tab' => 'Structural Characteristic',
        ]);*/
        $structuralRoofs = StructuralRoofs::where('isActive','=','Y')->get();
        $structuralRoofsArray = [];
        foreach($structuralRoofs as $structuralRoof){
            $structuralRoofsArray += [$structuralRoof->id => $structuralRoof->name];
        }
        $this->crud->addField([
            'name'        => 'roof',
            'label'       => 'Roof',
            'type'        => 'radio',
            'options'     => $structuralRoofsArray,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12 structural-roof-checklist',
            ],
            'tab' => 'Structural Characteristic',
        ]);
        $this->crud->addField([
            'name' => 'other_roof',
            'label' => 'Others',
            'type' => 'text',
            'hint'=>'(Please specify)',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden other_roof',
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
            'label' => 'Flooring',
            'type' => 'flooring_checklist_input',
            'name' => 'flooring',
            'entity' => 'flooring',
            'attribute' => 'name',
            'model'     => "App\Models\StructuralFlooring",
            'tab' => 'Structural Characteristic',
        ]);
        $this->crud->addField([
            'name'  => 'separator4',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'   => 'Structural Characteristic',
        ]);
        $this->crud->addField([
            'label' => 'Walling',
            'type' => 'walling_checklist_input',
            'name' => 'walling',
            'entity' => 'walling',
            'attribute' => 'name',
            'model'     => "App\Models\StructuralWalling",
            'tab' => 'Structural Characteristic',
        ]);
        /*Additional Items (Repeatable)*/
        $this->crud->addField([   
            'name'  => 'additional_items',
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
                    'name'    => 'additionalItem1',
                    'type'    => 'text',
                    'label'   => '',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'additionalItem1',
                    'type'    => 'text',
                    'label'   => '',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'additionalItem1',
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
            'tab' => 'Additional Items',
        ]);
        /*Property Appraisal*/
        $this->crud->addField([
            'name'  => 'separator5',
            'type'  => 'custom_html',
            'value' => '<p>Unit Construction Cost: Php - <input type="text" class="simple-form-input text_input_mask_currency" name="unitConstructionCost" id="unitConstructionCost" value="" /> /sq.m.</p>
                <p>Building Core: <i>(Use additional sheets if necessary)</i></p>
                <p><b>Sub-Total: Php - </b> <input type="text" class="simple-form-input text_input_mask_currency" name="unitConstructionSubTotal" id="unitConstructionSubTotal" value="" /> </p>',
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator6',
            'type'  => 'custom_html',
            'value' => '<p>Cost of Additional Items:</p>
                <p><b>Sub-Total</b></p>
                <p><b>TOTAL CONSTRUCTION COST: Php - </b> <input type="text" class="simple-form-input text_input_mask_currency" name="totalConstructionCost" id="totalConstructionCost" value="" /> </p>',
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
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
            ],
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        /*Property Assessment*/
        $this->crud->addField([   
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
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'marketValue',
                    'type'    => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                    ],
                    'label'   => 'Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'assessmentLevel',
                    'type'        => 'select_from_array',
                    'options'     => [],
                    'label'   => 'Assessment Level',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'assessedValue',
                    'type'  => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                    ],
                    'label' => 'Assessed Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
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
            'max_rows' => 10,
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
            'type' => 'radio',
            'options'     => [
                "Taxable" => "Taxable",
                "Exempt" => "Exempt"
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'=>'assessmentEffectivity',
            'label'=>'Effectivity of Assessment/Reassessment',
            'type' => 'radio',
            'options'     => [
                "Quarter" => "Quarter",
                "Year" => "Year"
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'=>'assessmentEffectivityValue',
            'label'=>'Effectivity of Assessment/Reassessment Value',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Property Assessment',
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

        BuildingProfile::creating(function($entry) {
            $count = BuildingProfile::count();
            $refID = 'BUILDING-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $transCount = TransactionLogs::count();
            $transRefID = 'TRANS-LOG'.'-'.str_pad(($transCount), 4, "0", STR_PAD_LEFT);

            TransactionLogs::create([
                'refID' => $transRefID,
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
        $this->setupCreateOperation();

        BuildingProfile::updating(function($entry) {

            $transCount = TransactionLogs::count();
            $transRefID = 'TRANS-LOG'.'-'.str_pad(($transCount), 4, "0", STR_PAD_LEFT);
          
            TransactionLogs::create([
                'refID' => $transRefID,
                'transId' =>$entry->refID,
                'category' =>'faas_building',
                'type' =>'update',
            ]);
        });
       
    }

}