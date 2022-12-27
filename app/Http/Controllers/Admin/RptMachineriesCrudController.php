<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RptMachineriesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Http\Request;
use App\Models\FaasMachinery;
use App\Models\RptMachineries;
use App\Models\CitizenProfile;
use Illuminate\Support\Facades\DB;
use App\Models\NameProfiles;
use App\Models\TransactionLogs;

/**
 * Class RptMachineriesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RptMachineriesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:RPT Assessments > Machineries', ['only' => ['index','show','create','store','edit','update','destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\RptMachineries::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/rpt-machineries');
        CRUD::setEntityNameStrings('rpt machineries', 'rpt machineries');

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
            'name'      => 'refID',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('rpt-machineries.edit',$entry->id);
                },
            ]
        ]);
        $this->crud->column('TDNo')->label('TD No.');
        $this->crud->addColumn([
            'type' => 'model_function',
            'label' => 'Primary Owner',
            'function_name' => 'getPrimaryOwner',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->with('faas_machinery_profile')
                ->orWhereHas('faas_machinery_profile.citizen_profile', function ($q) use ($column, $searchTerm) {
                    $q->where('fName', 'like', '%'.$searchTerm.'%');
                    $q->orWhere('mName', 'like', '%'.$searchTerm.'%');
                    $q->orWhere('lName', 'like', '%'.$searchTerm.'%');
                })
                ->orWhereHas('faas_machinery_profile.name_profile', function ($q) use ($column, $searchTerm) {
                    $q->where('first_name', 'like', '%'.$searchTerm.'%');
                    $q->orWhere('middle_name', 'like', '%'.$searchTerm.'%');
                    $q->orWhere('last_name', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
        $this->crud->addColumn([
            'type' => 'model_function',
            'label' => 'Address',
            'function_name' => 'getAddress',
            'limit' => 255,
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('faas_machinery_profile', function ($q) use ($column, $searchTerm) {
                    $q->where('ownerAddress', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
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
        $this->crud->column('created_at')->label('Date Created');
        $this->crud->addClause('where', 'isActive', '=', '1');
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
        CRUD::setValidation(RptMachineriesRequest::class);

        /*Search Fields*/
        $this->crud->addField([
            'name' => 'searchByReferenceId', 
            'label' => 'Search by Reference ID', 
            'type' => 'text',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([
            'name' => 'searchByPrimaryOwner', 
            'label' => 'Search by Primary Owner', 
            'type' => 'text',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([
            'name' => 'searchByPrimaryOwnerAddress', 
            'label' => 'Search by Owner Address', 
            'type' => 'textarea',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
        ]);
        $this->crud->addField([
            'name' => 'searchByPinId', 
            'label' => 'Search by PIN', 
            'type' => 'text',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([
            'name' => 'searchByLandReferenceId', 
            'label' => 'Search by Land Reference ID', 
            'type' => 'text',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([
            'name' => 'searchByBuildingReferenceId', 
            'label' => 'Search by Building Reference ID', 
            'type' => 'text',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator01',
            'type'  => 'custom_html',
            'value' => '<br>',
        ]);
        $this->crud->addField([
            'name'  => 'separator02',
            'type'  => 'custom_html',
            'value' => '',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-10',
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator04',
            'type'  => 'custom_html',
            'value' => '<a href="javascript:void(0)" id="btnSearch" class="btn btn-primary" data-style="zoom-in" style="width:110px;"><span class="ladda-label"><i class="la la-search"></i> Search</span></a>
                <a href="javascript:void(0)" id="btnClear" class="btn btn-default" data-style="zoom-in" style="width:110px;"><span class="ladda-label"><i class="la la-eraser"></i> Clear</span></a>',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-2',
            ],
        ]);

        /*Main Information*/
        $this->crud->addField([
            'name'=>'pin',
            'type'=>'text',
            'label'=>'PIN',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
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
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
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
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerAddress',
            'label'=>'Address',
            'type'=>'textarea',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
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
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12 ownerAddress_fake hidden'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerTelephoneNo',
            'label'=>'Telephone No.',
            'type'=>'text',
            'fake' => true,
            'attributes' => [
                'class' => 'form-control',
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerTin',
            'label'=>'TIN No.',
            'fake' => true,
            'attributes' => [
                'class' => 'form-control text_input_mask_tin',
                'disabled' => 'disabled',
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
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorAddress',
            'label'=>'Address',
            'type' => 'textarea',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorTelephoneNo',
            'label'=>'Telephone No.',
            'type'=>'text',
            'fake' => true,
            'attributes' => [
                'class' => 'form-control',
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorTin',
            'label'=>'TIN No.',
            'fake' => true,
            'attributes' => [
                'class' => 'form-control text_input_mask_tin',
                'disabled' => 'disabled',
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
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
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
                    'attributes' => [
                        'class' => 'form-control kindOfMachinery',
                    ],
                    'hint'    => '(Use additional sheets if necessary)',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'brandModel',
                    'type'    => 'text',
                    'label'   => 'Brand & Model',
                    'attributes' => [
                        'class' => 'form-control brandModel',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'capacity',
                    'type'    => 'text',
                    'label'   => 'Capacity/HP',
                    'attributes' => [
                        'class' => 'form-control capacity',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'dateAcquired',
                    'type'  => 'text',
                    'label' => 'Date Acquired',
                    'hint' => '(Year)',
                    'attributes' => [
                        'class' => 'form-control dateAcquired',
                    ],
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
                    'attributes' => [
                        'class' => 'form-control conditionWhenAcquired',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'economicLifeEstimated',
                    'type'    => 'number',
                    'label'   => 'Economic Life - Estimated',
                    'hint'    => '(No. of Years)',
                    'attributes' => [
                        'class' => 'form-control economicLifeEstimated',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'economicLifeRemain',
                    'type'    => 'number',
                    'label'   => 'Economic Life - Remain',
                    'hint'    => '(No. of Years)',
                    'attributes' => [
                        'class' => 'form-control economicLifeRemain',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'yearInstalled',
                    'type'    => 'text',
                    'label'   => 'Year Installed',
                    'attributes' => [
                        'class' => 'form-control yearInstalled',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'yearOfInitialOperation',
                    'type'  => 'text',
                    'label' => 'Year of Initial Operation',
                    'attributes' => [
                        'class' => 'form-control yearOfInitialOperation',
                    ],
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
                    'attributes' => [
                        'class' => 'form-control yearOfInitialOperation',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'rcn',
                    'type'  => 'text',
                    'label' => 'RCN',
                    'attributes' => [
                        'class' => 'form-control rcn',
                    ],
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
        $this->crud->addField([   
            'name'  => 'propertyAssessment',
            'label' => 'Property Assessment',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'actualUse',
                    'type'    => 'select',
                    'label'   => 'Actual Use',
                    'model'     => "App\Models\FaasMachineryClassifications",
                    'attribute' => 'code',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency actualUse',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3 '],
                ],
                [
                    'name'    => 'actualUse_fake',
                    'type'    => 'text',
                    'label'   => 'Actual Use',
                    'fake'   => true,
                    'attributes' => [
                        'class' => 'form-control actualUse_fake',
                        'readonly' => 'readonly'
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3 hidden'],
                ],
                [
                    'name'    => 'marketValue',
                    'type'    => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency marketValue',
                        'readonly' => 'readonly'
                    ],
                    'label'   => 'Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3 marketValue'],
                ],
                [
                    'name'    => 'assessmentLevel',
                    'type'    => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_percent assessmentLevel',
                        'readonly' => 'readonly',
                    ],
                    'label'   => 'Assessment Level',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'assessmentValue',
                    'type'    => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency assessmentValue',
                        'readonly' => 'readonly',
                    ],
                    'label'   => 'Assessment Value',
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
            'max_rows' => 1,
            'reorder' => true,
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'=>'totalPropertyAssessmentMarketValue',
            'label'=>'TOTAL (Market Value)',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency totalPropertyAssessmentMarketValue',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'=>'totalPropertyAssessmentAssessmentValue',
            'label'=>'TOTAL (Assessment Value)',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency totalPropertyAssessmentAssessmentValue',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'  => 'separator3',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Property Assessment',
        ]);

        $this->crud->addField([
            'name'  => 'ifAssessmentTypeIsExempt',
            'type'  => 'custom_html',
            'value' => '<div class="alert alert-warning" role="alert"><i class="la la-exclamation-triangle"></i> This property needs to go through an approval process.</div>',
            'tab' => 'Property Assessment',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12 hidden ifAssessmentTypeIsExempt',
            ],
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
        
        /*$this->crud->addField([
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
        ]);*/

        $year = date('Y', strtotime('+1 years'));
        $this->crud->addField([
            'name'=>'assessmentEffectivityValue',
            'label'=>'Effectivity of Assessment/Reassessment',
            'type' => 'select_from_array',
            'options' => [
                '' => '-',
                '1st Quarter of '.$year => '1st Quarter of '.$year, 
                '2nd Quarter of '.$year => '2nd Quarter of '.$year,
                '3rd Quarter of '.$year => '3rd Quarter of '.$year,
            ],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
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
            'name'  => 'separator6a1',
            'type'  => 'custom_html',
            'value' => '<label>Appraised / Assessed By</label>',
            'tab' => 'Property Assessment',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator6a2',
            'type'  => 'custom_html',
            'value' => '<label>Recommending Approval</label>',
            'tab' => 'Property Assessment',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
        ]);
        $this->crud->addField([
            'name'=>'assessedBy',
            'label'=>'',
            'type'=>'text',
            'hint'=>'Name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'=>'assessedDate',
            'label'=>'',
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'yyyy-mm-dd',
                'language' => 'fr',
                'endDate' => '0d',
                //'startDate' => Carbon::now()->subYears(130)->format('Y-m-d')
            ],
            'hint'=>'Date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'=>'recommendingPersonel',
            'label'=>'',
            'type'=>'text',
            'hint'=>'Name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'=>'recommendingApprovalDate',
            'label'=>'',
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'yyyy-mm-dd',
                'language' => 'fr',
                'endDate' => '0d',
                //'startDate' => Carbon::now()->subYears(130)->format('Y-m-d')
            ],
            'hint'=>'Date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Assessment',
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
        ]);
        $this->crud->addField([
            'name' => 'TDNo', 
            'label' => 'TD No.', 
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 approve_items hidden',
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name' => 'faasId', 
            'type' => 'hidden',
            'tab' => 'Property Assessment',
        ]);

        RptMachineries::creating(function($entry) {
            $count = RptMachineries::count();
            $refID = 'RPT-MCHN-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $request = app(RptMachineriesRequest::class);

            if($request->isApproved === '1') {
                // $TDNo = 'TD-MCHN-'.$request->barangayCode.'-01-'.str_pad(($count), 5, "0", STR_PAD_LEFT);
                $TDNo = 'TD-MCHN-'.str_pad(($count), 6, "0", STR_PAD_LEFT);
                $entry->TDNo = $TDNo;
            }

            TransactionLogs::create([
                'transId' =>$refID,
                'category' =>'rpt_machinery',
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
        
        RptMachineries::updating(function($entry) {
            $count = RptMachineries::count();
            $request = app(RptMachineriesRequest::class);

            if($request->isApproved === '1') {
                // $TDNo = 'TD-MCHN-'.$request->barangayCode.'-01-'.str_pad(($count), 5, "0", STR_PAD_LEFT);
                $TDNo = 'TD-MCHN-'.str_pad(($count), 6, "0", STR_PAD_LEFT);
                $entry->TDNo = $TDNo;
            }
          
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'rpt_machinery',
                'type' =>'update',
            ]);
        });
    }

    public function create()
    {
        Widget::add()->type('script')->content('assets/js/rpt/create-machinery-functions.js');
        $this->crud->hasAccessOrFail('create');
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;
        return view('rpt.machinery.create', $this->data);
    }

    public function edit($id)
    {
        Widget::add()->type('script')->content('assets/js/rpt/edit-machinery-functions.js');
        $this->crud->hasAccessOrFail('update');
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntryWithLocale($id);
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id'] = $id;

        return view('rpt.machinery.edit', $this->data);
    }

    public function applySearchFilters(Request $request){
        $searchByPrimaryOwner = $request->input('searchByPrimaryOwner');
        $searchByReferenceId = $request->input('searchByReferenceId');
        $searchByPinId = $request->input('searchByPinId');
        $searchByBuildingReferenceId = $request->input('searchByBuildingReferenceId');
        $searchByLandReferenceId = $request->input('searchByLandReferenceId');
        $searchByPrimaryOwnerAddress = $request->input('searchByPrimaryOwnerAddress');

        $results = [];

        $citizenProfile = FaasMachinery::select('faas_machineries.id', 'faas_machineries.refID', 'faas_machineries.primaryOwnerId', 'faas_machineries.ownerAddress', 'faas_machineries.pin',
        'faas_machineries.isActive',
        'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'),
        'faas_lands.refID as landRefID', 'faas_building_profiles.refID as buildingRefId')
        ->join('citizen_profiles', 'faas_machineries.primaryOwnerId', '=', 'citizen_profiles.id')
        ->join('faas_lands', 'faas_machineries.landProfileId', '=', 'faas_lands.id')
        ->join('faas_building_profiles', 'faas_machineries.buildingProfileId', '=', 'faas_building_profiles.id')
        ->with('citizen_profile')
        ->with('barangay')
        ->with('machinery_owner');

        $nameProfile = FaasMachinery::select('faas_machineries.id', 'faas_machineries.refID', 'faas_machineries.primaryOwnerId', 'faas_machineries.ownerAddress', 'faas_machineries.pin',
        'faas_machineries.isActive',
        'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'),
        'faas_lands.refID as landRefID', 'faas_building_profiles.refID as buildingRefId')
        ->join('name_profiles', 'faas_machineries.primaryOwnerId', '=', 'name_profiles.id')
        ->join('faas_lands', 'faas_machineries.landProfileId', '=', 'faas_lands.id')
        ->join('faas_building_profiles', 'faas_machineries.buildingProfileId', '=', 'faas_building_profiles.id')
        ->with('name_profile')
        ->with('barangay')
        ->with('machinery_owner');

        if (!empty($searchByPrimaryOwnerAddress)) { 
            $citizenProfile->where('faas_machineries.ownerAddress', 'like', '%'.$searchByPrimaryOwnerAddress.'%');
            $nameProfile->where('faas_machineries.ownerAddress', 'like', '%'.$searchByPrimaryOwnerAddress.'%');
        }

        if (!empty($searchByReferenceId)) { 
            $citizenProfile->where('faas_machineries.refID', 'like', '%'.$searchByReferenceId.'%');
            $nameProfile->where('faas_machineries.refID', 'like', '%'.$searchByReferenceId.'%');
        }

        if (!empty($searchByPinId)) { 
            $citizenProfile->where('faas_machineries.pin', 'like', '%'.$searchByPinId);
            $nameProfile->where('faas_machineries.pin', 'like', '%'.$searchByPinId);
        }

        if (!empty($searchByBuildingReferenceId)) { 
            $citizenProfile->where('faas_building_profiles.refID', 'like', '%'.$searchByBuildingReferenceId.'%');
            $nameProfile->where('faas_building_profiles.refID', 'like', '%'.$searchByBuildingReferenceId.'%');
        }

        if (!empty($searchByLandReferenceId)) { 
            $citizenProfile->where('faas_lands.refID', 'like', '%'.$searchByLandReferenceId.'%');
            $nameProfile->where('faas_lands.refID', 'like', '%'.$searchByLandReferenceId.'%');
        }

        if (!empty($searchByPrimaryOwner)) {
            $citizenProfile->where(DB::raw('CONCAT(citizen_profiles.fName," ",citizen_profiles.mName," ",citizen_profiles.lName)'), 'like', '%'.$searchByPrimaryOwner.'%');
            $nameProfile->where(DB::raw('CONCAT(name_profiles.first_name," ",name_profiles.middle_name," ",name_profiles.last_name)'), 'like', '%'.$searchByPrimaryOwner.'%');
        }

        $citizenProfiles = $citizenProfile->where('faas_machineries.isActive', '=', '1')->orderBy('faas_machineries.refID','ASC')->get();
        $nameProfiles = $nameProfile->where('faas_machineries.isActive', '=', '1')->orderBy('faas_machineries.refID','ASC')->get();

        $results = $citizenProfiles->merge($nameProfiles);

        return $results;
    }

    public function getDetails(Request $request){
        $id = $request->input('id');
        
        $results = [];
        if (!empty($id))
        {
            $citizenProfiles = RptMachineries::select('rpt_machineries.*',
                'faas_machineries.refID as faasRefId', 'faas_machineries.primaryOwnerId', 'faas_machineries.ownerAddress',
                'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType')
                )
                ->join('faas_machineries', 'rpt_machineries.faasId', '=', 'faas_machineries.id')
                ->join('citizen_profiles', 'faas_machineries.primaryOwnerId', '=', 'citizen_profiles.id')
                ->with('citizen_profile')
                ->with('faas_machinery_profile.land_profile')
                ->where('rpt_machineries.isActive', '=', '1')
                ->where('rpt_machineries.id', '=', $id)
                ->get();

            $nameProfiles = RptMachineries::select('rpt_machineries.*',
                'faas_machineries.refID as faasRefId', 'faas_machineries.primaryOwnerId', 'faas_machineries.ownerAddress',
                'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType')
                )
                ->join('faas_machineries', 'rpt_machineries.faasId', '=', 'faas_machineries.id')
                ->join('name_profiles', 'faas_machineries.primaryOwnerId', '=', 'name_profiles.id')
                ->with('name_profile')
                ->with('faas_machinery_profile.land_profile')
                ->where('rpt_machineries.isActive', '=', '1')
                ->where('rpt_machineries.id', '=', $id)
                ->get();
            
            $results = $citizenProfiles->merge($nameProfiles);
        }

        return $results;
    }
}
