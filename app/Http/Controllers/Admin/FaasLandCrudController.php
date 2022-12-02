<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FaasLandRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use App\Models\FaasLand;
use App\Models\FaasLandSecondaryOwners;
use Backpack\CRUD\app\Library\Widget;
use App\Models\TransactionLogs;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Http\Request as HttpRequest;

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
        //     'label' => 'Transaction Code',
        //     'type' => 'text',
        //     'name' => 'transactionCode',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3',
        //     ],
        //     'tab' => 'Main Information',
        // ]);
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
            'name'=>'ownerTinNo',
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
            'name'=>'administratorTinNo',
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

        FaasLand::creating(function($entry) {
            $count = FaasLand::count();
            $refID = 'LAND-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $request = app(FaasLandRequest::class);

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
        $this->setupCreateOperation();

        FaasLand::updating(function($entry) {
          
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'faas_land',
                'type' =>'update',
            ]);
        });
    }

    public function ajaxsearch(Request $request){
        $searchTxt = $request->q;
        $searchQuery = FaasLand::select('faas_lands.*')
        ->where('faas_lands.refID','like','%'.$searchTxt.'%')
        ->leftJoin('municipalities','municipalities.id','=','faas_lands.cityId')
        ->leftJoin('citizen_profiles','citizen_profiles.id','=','faas_lands.primaryOwnerId')
        ->orWhere('municipalities.name','like','%'.$searchTxt.'%')
        ->orWhere('faas_lands.ownerAddress','like','%'.$searchTxt.'%')
        ->orWhere(DB::raw('CONCAT(TRIM(citizen_profiles.fName)," ",
        (IF(citizen_profiles.mName IS NULL OR citizen_profiles.mName = ""  , "",CONCAT(citizen_profiles.mName," "))),
        TRIM(citizen_profiles.lName),
        (IF(citizen_profiles.suffix IS NULL OR citizen_profiles.suffix = ""  , "",CONCAT(" ",TRIM(citizen_profiles.suffix)))))'),'LIKE',"%".strtolower($searchTxt)."%")
        ->orWhereHas('land_owner', function( $query) use($searchTxt){
            return  $query->where(DB::raw('CONCAT(TRIM(citizen_profiles.fName)," ",
            (IF(citizen_profiles.mName IS NULL OR citizen_profiles.mName = ""  , "",CONCAT(citizen_profiles.mName," "))),
            TRIM(citizen_profiles.lName),
            (IF(citizen_profiles.suffix IS NULL OR citizen_profiles.suffix = ""  , "",CONCAT(" ",TRIM(citizen_profiles.suffix)))))'),'like',"%".strtolower($searchTxt)."%");
            
        })
        ->orWhereHas('barangay', function( $query) use($searchTxt){
            return  $query->where('name','like',"%".strtolower($searchTxt)."%")
            ->orWhere('refID','like',"%".strtolower($searchTxt)."%");
        })
        
        ->with('citizen_profile')
        ->with('barangay')
        ->with('land_owner')->get();

    


        return $searchQuery;

    }

    public function getDetails(Request $request){
        $id = $request->input('id');
        $results = [];
        if (!empty($id))
        {
            /*$citizenProfiles = DB::table('faas_lands')
            ->join('citizen_profiles', 'faas_lands.primaryOwnerId', '=', 'citizen_profiles.id')
            ->select('faas_lands.*', 'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'))
            ->where('faas_lands.isActive', '=', '1')
            ->where('faas_lands.id', '=', $id)
            ->get();*/

            $citizenProfiles = FaasLand::select('faas_lands.*', 'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'))
            ->join('citizen_profiles', 'faas_lands.primaryOwnerId', '=', 'citizen_profiles.id')
            ->with('citizen_profile')
            ->with('barangay')
            ->with('land_owner')
            ->where('faas_lands.isActive', '=', '1')
            ->where('faas_lands.id', '=', $id)
            ->get();
            
            /*$nameProfiles = DB::table('faas_lands')
            ->join('name_profiles', 'faas_lands.primaryOwnerId', '=', 'name_profiles.id')
            ->select('faas_lands.*', 'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'))
            ->where('faas_lands.isActive', '=', '1')
            ->where('faas_lands.id', '=', $id)
            ->get();*/

            $nameProfiles = FaasLand::select('faas_lands.*', 'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'))
            ->join('name_profiles', 'faas_lands.primaryOwnerId', '=', 'name_profiles.id')
            ->with('name_profile')
            ->with('barangay')
            ->with('land_owner')
            ->where('faas_lands.isActive', '=', '1')
            ->where('faas_lands.id', '=', $id)
            ->get();

            $results = $citizenProfiles->merge($nameProfiles);
        }

        return $results;
    }

    public function getSecondaryOwners(Request $request){
        $land_profile_id = $request->input('land_profile_id');
        $results = [];
        if (!empty($land_profile_id))
        {
            $results = DB::table('faas_land_secondary_owners')
            ->join('citizen_profiles', 'faas_land_secondary_owners.citizen_profile_id', '=', 'citizen_profiles.id')
            ->select('faas_land_secondary_owners.*', 'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address')
            ->where('faas_land_secondary_owners.land_profile_id', '=', $land_profile_id)
            ->get();
        }

        return $results;
    }
    
}
