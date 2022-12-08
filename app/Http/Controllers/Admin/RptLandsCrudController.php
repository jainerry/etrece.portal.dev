<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RptLandsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Http\Request;
use App\Models\FaasLand;
use App\Models\RptLands;
use App\Models\CitizenProfile;
use Illuminate\Support\Facades\DB;
use App\Models\NameProfiles;
use App\Models\TransactionLogs;

/**
 * Class RptLandsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RptLandsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\RptLands::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/rpt-lands');
        CRUD::setEntityNameStrings('rpt lands', 'rpt lands');

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
                    return route('rpt-lands.edit',$entry->id);
                },
            ]
        ]);
        $this->crud->column('TDNo')->label('TD No.');
        $this->crud->addColumn([
            'label'=>'Primary Owner',
            'type'  => 'model_function',
            'function_name' => 'getPrimaryOwner',
        ]);
        $this->crud->addColumn([
            'label'=>'Address',
            'type'  => 'model_function',
            'function_name' => 'getAddress',
            'limit' => 255,
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
        CRUD::setValidation(RptLandsRequest::class);

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
            'name' => 'searchByPinId', 
            'label' => 'Search by PIN', 
            'type' => 'text',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([
            'name' => 'searchByOCTTCTNo', 
            'label' => 'Search by OCT/TCT No.', 
            'type' => 'text',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([
            'name' => 'searchBySurveyNo', 
            'label' => 'Search by Survey No.', 
            'type' => 'text',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([
            'name' => 'searchByNoOfStreet', 
            'label' => 'Search by No. of Street', 
            'type' => 'text',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([
            'name' => 'searchByBarangayDistrict', 
            'label' => 'Search by Barangay/District', 
            'type' => 'select',
            'model'     => "App\Models\Barangay",
            'attribute' => 'name',
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
            'name'=>'octTctNo',
            'type'=>'text',
            'label'=>'OCT/TCT No.',
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
            'name'=>'survey_no',
            'type'=>'text',
            'label'=>'Survey No.',
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
            'name'  => 'separator00',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'  => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'lotNo',
            'type'=>'text',
            'label'=>'Lot No.',
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
            'name'=>'blkNo',
            'type'=>'text',
            'label'=>'Block No.',
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
            'name'=>'noOfStreet',
            'label'=>'No. of Street <span style="color:red;">*</span>',
            'type'=>'text',
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
            'name'=>'barangayId',
            'label'=>'Barangay/District <span style="color:red;">*</span>',
            'type'=>'select',
            'entity' => 'barangay',
            'attribute' => 'name',
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
            'tab' => 'Main Information',
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
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator0',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'  => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'propertyBoundaryNorth',
            'label'=>'North',
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
            'name'=>'propertyBoundaryEast',
            'label'=>'East',
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
            'name'=>'propertyBoundarySouth',
            'label'=>'South',
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
            'name'=>'propertyBoundaryWest',
            'label'=>'West',
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
            'name'  => 'separator5',
            'type'  => 'custom_html',
            'value' => '<label>Land Sketch</label>
                <div class="landSketch" id="landSketch"><img src="" /></div>',
            'tab' => 'Main Information',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator2',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'label' => 'Primary Owner <span style="color:red;">*</span>',
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
                'class' => 'form-group col-12 col-md-6 primaryOwnerId_select'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'land_owner',
            'label' => 'Secondary Owner/s',
            'type' => 'secondary_owner',
            'entity' => 'land_owner',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'data_source' => url('/admin/api/citizen-profile/search-secondary-owners'),
            'attribute' => 'full_name',
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 land_owner_select'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerAddress',
            'label'=>'Address <span style="color:red;">*</span>',
            'type'=>'textarea',
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
            'name'=>'ownerTinNo',
            'label'=>'TIN No.',
            'type'=>'text',
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
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administrator',
            'label'=>'Administrator/Occupant',
            'type'=>'textarea',
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
            'name'=>'administratorAddress',
            'label'=>'Address',
            'type'=>'textarea',
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
            'name'=>'administratorTinNo',
            'label'=>'TIN No.',
            'type'=>'text',
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
            'name'  => 'separator1a',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'isActive',
            'label'=>'Status <span style="color:red;">*</span>',
            'type' => 'select_from_array',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
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
        /*Land Appraisal*/
        $this->crud->addField([   
            'name'  => 'landAppraisal',
            'label' => 'Land Appraisal',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'classification',
                    'type'    => 'select',
                    'label'   => 'Classification',
                    'model'     => "App\Models\FaasLandClassification",
                    'attribute' => 'name',
                    'attributes' => [
                        'class' => 'form-control classification',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'subClass',
                    'type'    => 'text',
                    'label'   => 'Sub-Class',
                    'attributes' => [
                        'class' => 'form-control subClass',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'actualUse',
                    'type'    => 'select',
                    'label'   => 'Actual Use',
                    'model'     => "App\Models\FaasLandClassification",
                    'attribute' => 'code',
                    'attributes' => [
                        'class' => 'form-control actualUse',
                        'readonly' => 'readonly'
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3 hidden'],
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
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'area',
                    'type'=>'text',
                    'fake' => true,
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency area',
                    ],
                    'label' => 'Area',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                /*
                [
                    'name'    => 'unitValue',
                    'type'=>'text',
                    'fake' => true,
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency unitValue',
                        'disabled' => 'disabled',
                    ],
                    'label'   => 'Unit Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                */
                [
                    'name'    => 'baseMarketValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency baseMarketValue',
                        'readonly' => 'readonly'
                    ],
                    'label'   => 'Base Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => true,
            'tab' => 'Land Appraisal',
        ]);
        $this->crud->addField([
            'name'  => 'separator1ab',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Land Appraisal',
        ]);
        $this->crud->addField([
            'name'=>'totalLandAppraisalBaseMarketValue',
            'label'=>'TOTAL (Base Market Value)',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency totalLandAppraisalBaseMarketValue',
                'readonly' => 'readonly',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Land Appraisal',
        ]);
        /*Other Improvements*/
        $this->crud->addField([   
            'name'  => 'otherImprovements',
            'label' => 'Other Improvements',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'kind',
                    'type'    => 'text',
                    'label'   => 'Kind',
                    'attributes' => [
                        'class' => 'form-control kind',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'totalNumber',
                    'type'    => 'number',
                    'label'   => 'Total Number',
                    'attributes' => [
                        'class' => 'form-control totalNumber',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'unitValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency unitValue',
                    ],
                    'label'   => 'Unit Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'baseMarketValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency baseMarketValue',
                        'readonly' => 'readonly',
                    ],
                    'label' => 'Base Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ]
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => true,
            'tab' => 'Other Improvements',
        ]);
        $this->crud->addField([
            'name'  => 'separator1abc',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Other Improvements',
        ]);
        $this->crud->addField([
            'name'=>'totalOtherImprovementsBaseMarketValue',
            'label'=>'TOTAL (Base Market Value)',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency totalOtherImprovementsBaseMarketValue',
                'readonly' => 'readonly',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Other Improvements',
        ]);
        /*Market Value*/
        $this->crud->addField([   
            'name'  => 'marketValue',
            'label' => 'Market Value',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'baseMarketValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency baseMarketValue',
                    ],
                    'label'   => 'Base Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'adjustmentFactor',
                    'type'    => 'text',
                    'label'   => 'Adjustment Factor',
                    'attributes' => [
                        'class' => 'form-control adjustmentFactor',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'adjustmentFactorPercentage',
                    'type'    => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_percent adjustmentFactorPercentage',
                    ],
                    'label'   => '% Adj',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'valueAdjustment',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency valueAdjustment',
                    ],
                    'label' => 'Value Adjustment',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'marketValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency marketValue',
                        'readonly' => 'readonly',
                    ],
                    'label' => 'Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ]
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => true,
            'tab' => 'Market Value',
        ]);
        $this->crud->addField([
            'name'  => 'separator1abcd',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Market Value',
        ]);
        $this->crud->addField([
            'name'=>'totalMarketValueMarketValue',
            'label'=>'TOTAL (Market Value)',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency totalMarketValueMarketValue',
                'readonly' => 'readonly',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Market Value',
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
                    'model'     => "App\Models\FaasLandClassification",
                    'attribute' => 'code',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency actualUse',
                        'readonly' => 'readonly',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
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
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'marketValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency marketValue',
                        'readonly' => 'readonly',
                    ],
                    'label'   => 'Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3 propertyAssessment_marketValue'],
                ],
                [
                    'name'    => 'assessmentLevel',
                    'type'    => 'select_from_array',
                    'options' => [],
                    'attributes' => [
                        'class' => 'form-control assessmentLevel',
                    ],
                    'label'   => 'Assessment Level',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'assessmentValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency assessmentValue',
                        'readonly' => 'readonly'
                    ],
                    'label' => 'Assessment Value',
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
            'name'  => 'separator1abcde',
            'type'  => 'custom_html',
            'value' => '<hr>',
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
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Assessment',
        ]);
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
            ],
            'hint'=>'Date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'  => 'separator100',
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
                'class' => 'form-group col-12 col-md-3 approve_items hidden'
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
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 approve_items hidden'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name' => 'faasId', 
            'type' => 'hidden',
            'tab' => 'Property Assessment',
        ]);

        RptLands::creating(function($entry) {
            $count = RptLands::count();
            $refID = 'RPT-LAND-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $request = app(RptLandsRequest::class);

            if($request->isApproved === '1') {
                $TDNo = 'LAND-TDNo.-'.str_pad(($count), 6, "0", STR_PAD_LEFT);
                $entry->TDNo = $TDNo;
            }

            TransactionLogs::create([
                'transId' =>$refID,
                'category' =>'rpt_land',
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

        $this->crud->addField([
            'name' => 'TDNo', 
            'label' => 'TD No.', 
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Property Assessment',
        ]);
        
        RptLands::updating(function($entry) {
            $count = RptLands::count();
            $request = app(RptLandsRequest::class);

            if($request->isApproved === '1') {
                $TDNo = 'LAND-TDNo.-'.str_pad(($count), 6, "0", STR_PAD_LEFT);
                $entry->TDNo = $TDNo;
            }
          
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'rpt_land',
                'type' =>'update',
            ]);
        });
    }

    public function create()
    {
        Widget::add()->type('script')->content('assets/js/rpt/create-land-functions.js');
        $this->crud->hasAccessOrFail('create');
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;
        return view('rpt.land.create', $this->data);
    }

    public function edit($id)
    {
        Widget::add()->type('script')->content('assets/js/rpt/edit-land-functions.js');
        $this->crud->hasAccessOrFail('update');
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntryWithLocale($id);
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id'] = $id;

        return view('rpt.land.edit', $this->data);
    }

    public function applySearchFilters(Request $request){
        $searchByPrimaryOwner = $request->input('searchByPrimaryOwner');
        $searchByReferenceId = $request->input('searchByReferenceId');
        $searchByOCTTCTNo = $request->input('searchByOCTTCTNo');
        $searchByBarangayDistrict = $request->input('searchByBarangayDistrict');

        $results = [];

        $citizenProfile = FaasLand::select('faas_lands.id', 'faas_lands.refID', 'faas_lands.primaryOwnerId', 'faas_lands.ownerAddress', 'faas_lands.noOfStreet', 
        'faas_lands.barangayId', 'faas_lands.octTctNo', 
        'faas_lands.isActive',
        'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'))
        ->join('citizen_profiles', 'faas_lands.primaryOwnerId', '=', 'citizen_profiles.id')
        ->with('citizen_profile')
        ->with('barangay')
        ->with('land_owner');
        
        $nameProfile = FaasLand::select('faas_lands.id', 'faas_lands.refID', 'faas_lands.primaryOwnerId', 'faas_lands.ownerAddress', 'faas_lands.noOfStreet', 
        'faas_lands.barangayId', 'faas_lands.octTctNo',
        'faas_lands.isActive',
        'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'))
        ->join('name_profiles', 'faas_lands.primaryOwnerId', '=', 'name_profiles.id')
        ->with('name_profile')
        ->with('barangay')
        ->with('land_owner');

        if (!empty($searchByReferenceId)) { 
            $citizenProfile->where('faas_lands.refID', 'like', '%'.$searchByReferenceId.'%');
            $nameProfile->where('faas_lands.refID', 'like', '%'.$searchByReferenceId.'%');
        }

        if (!empty($searchByOCTTCTNo)) { 
            $citizenProfile->where('faas_lands.octTctNo', 'like', '%'.$searchByOCTTCTNo.'%');
            $nameProfile->where('faas_lands.octTctNo', 'like', '%'.$searchByOCTTCTNo.'%');
        }

        if (!empty($searchByBarangayDistrict)) { 
            $citizenProfile->where('faas_lands.barangayId', '=', $searchByBarangayDistrict);
            $nameProfile->where('faas_lands.barangayId', '=', $searchByBarangayDistrict);
        }

        if (!empty($searchByPrimaryOwner)) {
            $citizenProfile->where(DB::raw('CONCAT(citizen_profiles.fName," ",citizen_profiles.mName," ",citizen_profiles.lName)'), 'like', '%'.$searchByPrimaryOwner.'%');
            $nameProfile->where(DB::raw('CONCAT(name_profiles.first_name," ",name_profiles.middle_name," ",name_profiles.last_name)'), 'like', '%'.$searchByPrimaryOwner.'%');
        }

        $citizenProfiles = $citizenProfile->where('faas_lands.isActive', '=', '1')->orderBy('faas_lands.refID','ASC')->get();
        $nameProfiles = $nameProfile->where('faas_lands.isActive', '=', '1')->orderBy('faas_lands.refID','ASC')->get();

        $results = $citizenProfiles->merge($nameProfiles);

        return $results;
    }
}
