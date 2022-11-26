<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FaasLandRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use App\Models\FaasLand;
use Backpack\CRUD\app\Library\Widget;
use App\Models\TransactionLogs;

/**
 * Class FaasLandCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FaasLandCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view-faas-lands', ['only' => ['index','show']]);
        $this->middleware('can:create-faas-lands', ['only' => ['create','store']]);
        $this->middleware('can:edit-faas-lands', ['only' => ['edit','update']]);
        $this->middleware('can:delete-faas-lands', ['only' => ['destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
       $this->crud->setModel(\App\Models\FaasLand::class);
       $this->crud->setRoute(config('backpack.base.route_prefix') . '/faas-land');
       $this->crud->setEntityNameStrings('land', 'lands');
        $this->crud->removeButton('delete');

        Widget::add()->type('style')->content('assets/css/faas/styles.css');
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');
        Widget::add()->type('script')->content('assets/js/jquery.inputmask.bundle.min.js');
        Widget::add()->type('script')->content('assets/js/backpack/crud/inputmask.js');
        Widget::add()->type('script')->content('assets/js/faas/land/functions.js');
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
                    return route('faas-land.edit',$entry->id);
                },
            ]
        ]);
        $this->crud->column('ARPNo')->label('ARP No.');
        $this->crud->addColumn([
            'name'  => 'primaryOwner',
            'label' => 'Primary Owner',
            'type'  => 'select',
            'entity'    => 'citizen_profile',
            'attribute' => 'full_name'
        ],);
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
        $this->crud->setValidation(FaasLandRequest::class);
        /*Main Information*/
        $this->crud->addField([
            'name'  => 'isIdleLand',
            'label' => 'Is Idle Land',
            'type'  => 'checkbox',
            'attributes' => [
                'class' => 'isIdleLand_checkbox'
            ],
            'tab'  => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator00',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'  => 'Main Information',
        ]);
        // $this->crud->addField([
        //     'label' => 'ARP No.',
        //     'type' => 'text',
        //     'name' => 'ARPNo',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3',
        //     ],
        //     'tab' => 'Main Information',
        // ]);
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
            'name'=>'octTctNo',
            'type'=>'text',
            'label'=>'OCT/TCT No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'lotNo',
            'type'=>'text',
            'label'=>'Lot No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'blkNo',
            'type'=>'text',
            'label'=>'Block No.',
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
            'name'  => 'isOwnerNonTreceResident',
            'label' => 'Non Trece Resident',
            'type'  => 'checkbox',
            'attributes' => [
                'class' => 'isOwnerNonTreceResident_checkbox'
            ],
            'tab'  => 'Main Information',
        ]);
        $this->crud->addField([
            'label' => 'Primary Owner <span style="color:red;">*</span>',
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
        $this->crud->addField([
            'name'=>'primaryOwnerText',
            'label'=>'Primary Owner <span style="color:red;">*</span>',
            'type'=>'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 primaryOwnerText hidden'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'land_owner',
            'label' => 'Secondary Owner/s',
            'type' => 'secondary_owner',
            'entity' => 'land_owner',
            'data_source' => url('/admin/api/citizen-profile/search-secondary-owners'),
            'attribute' => 'full_name',
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 land_owner_select'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'secondaryOwnersText',
            'label'=>'Secondary Owner/s',
            'type'=>'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 secondaryOwnersText hidden'
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
            'type'=>'text',
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
            'tab' => 'Main Information',
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
            'type'=>'textarea',
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
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorTin',
            'label'=>'TIN No.',
            'type'=>'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_tin',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator2',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'noOfStreet',
            'label'=>'No. of Street',
            'type'=>'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
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
            'name'  => 'cityId',
            'type'  => 'hidden',
            'value' => 'db3510e6-3add-4d81-8809-effafbbaa6fd',
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
            'name'  => 'provinceId',
            'type'  => 'hidden',
            'value' => 'eb9e8c56-957b-4084-b5ae-904054d2a1b3',
            'tab' => 'Main Information',
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
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'barangay_code_text',
            'type'  => 'hidden',
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
        /*Property Boundaries*/
        $this->crud->addField([
            'name'=>'propertyBoundaryNorth',
            'label'=>'North',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Boundaries',
        ]);
        $this->crud->addField([
            'name'=>'propertyBoundaryEast',
            'label'=>'East',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Boundaries',
        ]);
        $this->crud->addField([
            'name'=>'propertyBoundarySouth',
            'label'=>'South',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Boundaries',
        ]);
        $this->crud->addField([
            'name'=>'propertyBoundaryWest',
            'label'=>'West',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Property Boundaries',
        ]);
        $this->crud->addField([
            'label' => "Land Sketch",
            'name' => "landSketch",
            'type' => 'image',
            'crop' => true,
            'aspect_ratio' => 0,
            'hint'=>'(Not necessary drawn to scale)',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
            ],
            'tab' => 'Property Boundaries',
        ]);
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
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'area',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency area',
                    ],
                    'label' => 'Area',
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
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Land Appraisal',
        ]);
        $this->crud->addField([   
            'name'  => 'otherImprovements',
            'label' => 'Other Improvements',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'kind',
                    'type'    => 'text',
                    'label'   => 'Kind',
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
                        'readonly' => 'readonly'
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
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Other Improvements',
        ]);
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
                        'readonly' => 'readonly'
                    ],
                    'label' => 'Value Adjustment',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'marketValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency marketValue',
                        'readonly' => 'readonly'
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
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Market Value',
        ]);
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
                        'readonly' => 'readonly'
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'marketValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency marketValue',
                        'readonly' => 'readonly'
                    ],
                    'label'   => 'Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3 propertyAssessment_marketValue'],
                ],
                [
                    'name'    => 'assessmentLevel',
                    'type'    => 'select',
                    'model'     => "App\Models\FaasLandClassification",
                    'attribute' => 'assessmentLevel',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_percent assessmentLevel',
                        'readonly' => 'readonly'
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
                [
                    'name'    => 'actualUse_code',
                    'type'    => 'select',
                    'label'   => 'Actual Use',
                    'model'     => "App\Models\FaasLandClassification",
                    'attribute' => 'code',
                    'wrapper' => ['class' => 'form-group col-md-3 actualUse_code hidden'],
                ],
                [
                    'name'  => 'actualUse_code_text',
                    'type'  => 'hidden',
                    'wrapper' => ['class' => 'form-group col-md-3 actualUse_code_text hidden'],
                ]
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
                'class' => 'form-group col-12 col-md-4'
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
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Property Assessment',
        ]);
        $this->crud->addField([
            'name'  => 'separator3',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Property Assessment',
        ]);
        // $this->crud->addField([
        //     'name'=>'assessmentType',
        //     'label'=>'Assessment Type',
        //     'type' => 'radio',
        //     'options'     => [
        //         "Taxable" => "Taxable",
        //         "Exempt" => "Exempt"
        //     ],
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4'
        //     ],
        //     'tab' => 'Property Assessment',
        // ]);
        // $this->crud->addField([
        //     'name'=>'assessmentEffectivity',
        //     'label'=>'Effectivity of Assessment/Reassessment',
        //     'type' => 'radio',
        //     'options'     => [
        //         "Quarter" => "Quarter",
        //         "Year" => "Year"
        //     ],
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4'
        //     ],
        //     'tab' => 'Property Assessment',
        // ]);
        // $this->crud->addField([
        //     'name'=>'assessmentEffectivityValue',
        //     'label'=>'Effectivity of Assessment/Reassessment Value',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4'
        //     ],
        //     'tab' => 'Property Assessment',
        // ]);
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
        ]);
        // $this->crud->addField([
        //     'name'=>'TDNo',
        //     'label'=>'TD No.',
        //     'type'=>'text',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4 approve_items hidden'
        //     ],
        //     'tab' => 'Property Assessment',
        // ]);

        FaasLand::creating(function($entry) {
            $count = FaasLand::count();
            $refID = 'LAND-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $request = app(FaasLandRequest::class);

            $ARPNo = 'ARP-LAND-'.$request->barangay_code_text.'-01-'.str_pad(($count), 5, "0", STR_PAD_LEFT).'-'.$request->propertyAssessment[0]['actualUse_code_text'];
            $TDNo = 'TD-LAND-'.$request->barangay_code_text.'-01-'.str_pad(($count), 5, "0", STR_PAD_LEFT).'-'.$request->propertyAssessment[0]['actualUse_code_text'];
            $entry->ARPNo = $ARPNo;
            $entry->TDNo = $TDNo;

            TransactionLogs::create([
                'transId' =>$refID,
                'category' =>'faas_land',
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
        $this->crud->addField([
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
        ]);

        $this->crud->addField([
            'name'  => 'separator10ab',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Main Information',
        ]);

        $this->setupCreateOperation();

        FaasLand::updating(function($entry) {
          
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'faas_land',
                'type' =>'update',
            ]);
        });
    }
    
}
