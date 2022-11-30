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
        Widget::add()->type('script')->content('assets/js/faas/machinery/functions.js');
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
        //$this->crud->column('ARPNo')->label('ARP No.');
        $this->crud->addColumn([
            'label'=>'Primary Owner',
            'type'  => 'model_function',
            'function_name' => 'getPrimaryOwner',
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
            'label' => 'Transaction Code',
            'type' => 'text',
            'name' => 'transactionCode',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Main Information',
        ]);
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
            'name'  => 'separator0',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'  => 'Main Information',
        ]);
        $this->crud->addField([
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
        ]);
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
                'class' => 'form-group col-12 col-md-12'
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
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorAddress',
            'label'=>'Address',
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
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
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);
        /*Property Location*/
        $this->crud->addField([
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
        ]);
        /*Property Appraisal*/
        $this->crud->addField([   
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
                'class' => 'form-group col-12 col-md-4'
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
                'class' => 'form-group col-12 col-md-4'
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
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Property Appraisal',
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
                    'model'     => "App\Models\FaasMachineryClassifications",
                    'attribute' => 'name',
                    'wrapper' => ['class' => 'form-group col-md-3 actualUse'],
                ],
                [
                    'name'    => 'marketValue',
                    'type'    => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                        'readonly' => 'readonly'
                    ],
                    'label'   => 'Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3 marketValue'],
                ],
                [
                    'name'    => 'assessmentLevel',
                    'type'    => 'select',
                    'model'     => "App\Models\FaasMachineryClassifications",
                    'attribute' => 'assessmentLevel',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_percent',
                        'readonly' => 'readonly'
                    ],
                    'label'   => 'Assessment Level',
                    'wrapper' => ['class' => 'form-group col-md-3 assessmentLevel'],
                ],
                [
                    'name'  => 'assessedValue',
                    'type'  => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                        'readonly' => 'readonly'
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
                [
                    'name'    => 'actualUse_code',
                    'type'    => 'select',
                    'label'   => 'Actual Use',
                    'model'     => "App\Models\FaasMachineryClassifications",
                    'attribute' => 'code',
                    'wrapper' => ['class' => 'form-group col-md-3 actualUse_code hidden'],
                ],
                [
                    'name'  => 'actualUse_code_text',
                    'type'  => 'hidden',
                ]
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 1,
            'reorder' => true,
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'  => 'separator3',
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
            'name'  => 'separator4',
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

        FaasMachinery::creating(function($entry) {
            $count = FaasMachinery::count();
            $refID = 'MACHINERY-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $request = app(FaasMachineryRequest::class);
            
            /*$ARPNo = 'ARP-MCHN-'.$request->barangay_code_text.'-01-'.str_pad(($count), 5, "0", STR_PAD_LEFT).'-'.$request->propertyAssessment[0]['actualUse_code_text'];
            $entry->ARPNo = $ARPNo;*/

            /*$TDNo = 'TD-MCHN-'.$request->barangay_code_text.'-01-'.str_pad(($count), 5, "0", STR_PAD_LEFT).'-'.$request->propertyAssessment[0]['actualUse_code_text'];
            $entry->TDNo = $TDNo;*/

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

        FaasMachinery::updating(function($entry) {
          
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'faas_machinery',
                'type' =>'update',
            ]);
        });
    }
    
}
