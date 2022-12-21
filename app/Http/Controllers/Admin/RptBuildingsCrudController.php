<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RptBuildingsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Http\Request;
use App\Models\BuildingProfile;
use App\Models\RptBuildings;
use App\Models\CitizenProfile;
use Illuminate\Support\Facades\DB;
use App\Models\NameProfiles;
use App\Models\TransactionLogs;

/**
 * Class RptBuildingsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RptBuildingsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\RptBuildings::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/rpt-buildings');
        CRUD::setEntityNameStrings('rpt buildings', 'rpt buildings');
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
                    return route('rpt-buildings.edit',$entry->id);
                },
            ]
        ]);
        $this->crud->column('TDNo')->label('TD No.');
        $this->crud->addColumn([
            'type' => 'model_function',
            'label' => 'Primary Owner',
            'function_name' => 'getPrimaryOwner',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->with('faas_building_profile')
                ->orWhereHas('faas_building_profile.citizen_profile', function ($q) use ($column, $searchTerm) {
                    $q->where('fName', 'like', '%'.$searchTerm.'%');
                    $q->orWhere('mName', 'like', '%'.$searchTerm.'%');
                    $q->orWhere('lName', 'like', '%'.$searchTerm.'%');
                })
                ->orWhereHas('faas_building_profile.name_profile', function ($q) use ($column, $searchTerm) {
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
                $query->orWhereHas('faas_building_profile', function ($q) use ($column, $searchTerm) {
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
        CRUD::setValidation(RptBuildingsRequest::class);

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
            'name' => 'searchByBuildingClassification', 
            'label' => 'Search by Building Classification', 
            'type'=>'select',
            'model' => "App\Models\FaasBuildingClassifications",
            'attribute' => 'name',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([
            'name' => 'searchByStructuralType', 
            'label' => 'Search by Building Structural Type', 
            'type'=>'select',
            'model' => "App\Models\StructuralType",
            'attribute' => 'name',
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
            'label' => 'Primary Owner',
            'type' => 'primary_owner_union',
            'name' => 'primary_owner',
            'entity' => 'citizen_profile',
            'attribute' => 'full_name',
            'data_source' => url('/admin/api/citizen-profile/search-primary-owner'),
            'minimum_input_length' => 1,
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
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
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'ownerAddress', 
            'label' => 'Address', 
            'type' => 'textarea',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12 ownerAddress',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'tel_no', 
            'label' => 'Telephone No.', 
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'owner_tin_no', 
            'label' => 'TIN No.', 
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'class' => 'form-control text_input_mask_tin',
            ],
            'attributes' => [
                'disabled' => 'disabled',
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
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'admin_address',
            'label' => 'Address',
            'type' => 'textarea',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12',
            ],
            'tab'  => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'admin_tel_no',
            'label' => 'Telephone No.',
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'admin_tin_no',
            'label' => 'TIN No.',
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'class' => 'form-control text_input_mask_tin',
                'disabled' => 'disabled',
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
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);

        /*General Description*/
        $this->crud->addField([
            'label' => "Kind of Building",
            'type'=>'select',
            'name'=>'kind_of_building_id',
            'model'     => "App\Models\FaasBuildingClassifications",
            'attribute' => 'name',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'buildingAge',
            'label' => 'Building Age',
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
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
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'building_permit_no',
            'label' => 'Building Permit No',
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'building_permit_date_issued',
            'label' => 'Building Permit Date Issued',
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
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
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
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
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'certificate_of_occupancy_issued_on',
            'label' => 'Certificate of Occupancy Issued On',
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'date_constructed',
            'label' => 'Date Constructed',
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'date_occupied',
            'label' => 'Date Occupied',
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
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
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
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
                        'disabled' => 'disabled',
                    ], 
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-3',
                    ],
                ],
                [
                    'name'    => 'floorNo',
                    'label' => "Floor",
                    'type'=>'text',
                    'fake' => true,
                    'attributes' => [
                        'disabled' => 'disabled',
                    ], 
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-3',
                    ],
                ],
                [
                    'name'    => 'area',
                    'type'    => 'text',
                    'label'   => 'Area',
                    'fake' => true,
                    'wrapper' => ['class' => 'form-group col-md-3'],
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                        'disabled' => 'disabled',
                    ],
                ],
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => false,
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
            'fake' => true,
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'disabled' => 'disabled',
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
            'model' => "App\Models\StructuralRoofs",
            'attribute' => 'name',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Structural Characteristic',
        ]);
        $this->crud->addField([
            'name' => 'other_roof',
            'label' => 'Please Specify',
            'type' => 'text',
            'fake' => true,
            'attributes' => [
                'disabled' => 'disabled',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 other_roof ',
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
                        'disabled' => 'disabled',
                    ], 
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-3',
                    ],
                ],
                [
                    'name'    => 'floorNo',
                    'label' => "Floor",
                    'type'=>'text',
                    'fake' => true,
                    'attributes' => [
                        'disabled' => 'disabled',
                    ], 
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-3',
                    ],
                ],
                [
                    'name'    => 'type',
                    'type' => 'select',
                    'model'     => "App\Models\StructuralFlooring",
                    'attribute' => 'name',
                    'fake' => true,
                    'attributes' => [
                        'disabled' => 'disabled',
                    ],
                    'label'   => 'Type',
                    'wrapper' => ['class' => 'form-group col-md-3 type'],
                ],
                [
                    'name'    => 'others',
                    'type'    => 'text',
                    'fake' => true,
                    'attributes' => [
                        'disabled' => 'disabled',
                    ],
                    'label'   => 'Please Specify',
                    'wrapper' => ['class' => 'form-group col-md-3 others'],
                ],
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => false,
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
                        'disabled' => 'disabled',
                    ], 
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-3',
                    ],
                ],
                [
                    'name'    => 'floorNo',
                    'label' => "Floor",
                    'type'=>'text',
                    'fake' => true,
                    'attributes' => [
                        'disabled' => 'disabled',
                    ], 
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-3',
                    ],
                ],
                [
                    'name'    => 'type',
                    'type' => 'select',
                    'model'     => "App\Models\StructuralWalling",
                    'attribute' => 'name',
                    'fake' => true,
                    'attributes' => [
                        'disabled' => 'disabled',
                    ],
                    'label'   => 'Type',
                    'wrapper' => ['class' => 'form-group col-md-3 type'],
                ],
                [
                    'name'    => 'others',
                    'type'    => 'text',
                    'fake' => true,
                    'attributes' => [
                        'disabled' => 'disabled',
                    ],
                    'label'   => 'Please Specify',
                    'wrapper' => ['class' => 'form-group col-md-3 others'],
                ],
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => false,
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
                    'fake' => true,
                    'attributes' => [
                        'disabled' => 'disabled',
                    ],
                    'label'   => '',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'additionalItem2',
                    'type'    => 'text',
                    'fake' => true,
                    'attributes' => [
                        'disabled' => 'disabled',
                    ],
                    'label'   => '',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'additionalItem3',
                    'type'    => 'text',
                    'fake' => true,
                    'attributes' => [
                        'disabled' => 'disabled',
                    ],
                    'label'   => '',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'additionalItem4',
                    'type'    => 'text',
                    'fake' => true,
                    'attributes' => [
                        'disabled' => 'disabled',
                    ],
                    'label'   => '',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ]
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => false,
            'tab' => 'Structural Characteristic',
        ]);

        /*Property Appraisal*/
        $this->crud->addField([
            'name'  => 'separator5',
            'type'  => 'custom_html',
            'value' => '<p>Unit Construction Cost: Php - <input type="text" class="simple-form-input text_input_mask_currency" name="unitConstructionCost_fake" id="unitConstructionCost_fake" value="" disabled="disabled" /> /sq.m.</p>
                <p>Building Core: <i>(Use additional sheets if necessary)</i></p>
                <p><b>Sub-Total: Php - </b> <input type="text" class="simple-form-input text_input_mask_currency" name="unitConstructionSubTotal_fake" id="unitConstructionSubTotal_fake" value="" /> </p>',
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
                <p><b>Sub-Total: Php - </b> <input type="text" class="simple-form-input text_input_mask_currency" name="costOfAdditionalItemsSubTotal_fake" id="costOfAdditionalItemsSubTotal_fake" value="" /> </p>',
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator6a',
            'type'  => 'custom_html',
            'value' => '<p><b>TOTAL CONSTRUCTION COST: Php - </b> <input type="text" class="simple-form-input text_input_mask_currency" name="totalConstructionCost_fake" id="totalConstructionCost_fake" value="" disabled="disabled" /> </p>',
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
        ]);
        $this->crud->addField([   
            'name'  => 'unitConstructionCost',
            'label' => 'unitConstructionCost',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency unitConstructionCost',
                'readonly' => 'readonly',
            ],
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([   
            'name'  => 'unitConstructionSubTotal',
            'label' => 'unitConstructionSubTotal',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency unitConstructionSubTotal',
                'readonly' => 'readonly',
            ],
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([   
            'name'  => 'costOfAdditionalItemsSubTotal',
            'label' => 'costOfAdditionalItemsSubTotal',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency costOfAdditionalItemsSubTotal',
                'readonly' => 'readonly',
            ],
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([   
            'name'  => 'totalConstructionCost',
            'label' => 'totalConstructionCost',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency totalConstructionCost',
                'readonly' => 'readonly',
            ],
            'tab' => 'Property Appraisal',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
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
                'class' => 'form-control text_input_mask_percent depreciationRate',
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
                'class' => 'form-control text_input_mask_currency depreciationCost',
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
                'class' => 'form-control text_input_mask_percent totalPercentDepreciation',
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
                'class' => 'form-control text_input_mask_currency marketValue',
                'readonly' => 'readonly',
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
                /*[
                    'name'    => 'actualUse',
                    'type'    => 'select',
                    'label'   => 'Actual Use',
                    'model'     => "App\Models\FaasBuildingClassifications",
                    'attribute' => 'name',
                    'wrapper' => ['class' => 'form-group col-md-3 actualUse'],
                ],*/
                [
                    'name'    => 'actualUse',
                    'type'    => 'select',
                    'label'   => 'Actual Use',
                    'model'     => "App\Models\FaasBuildingClassifications",
                    'attribute' => 'code',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency actualUse',
                        'readonly' => 'readonly',
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
                    'name'    => 'marketValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency marketValue',
                        'readonly' => 'readonly',
                    ],
                    'label'   => 'Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3 propertyAssessment_marketValue'],
                ],
                /*[
                    'name'    => 'assessmentLevel',
                    'type'    => 'select',
                    'label'   => 'Assessment Level',
                    'model'     => "App\Models\FaasBuildingClassifications",
                    'attribute' => 'assessmentLevel',
                    'attributes' => [
                        'readonly' => 'readonly',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-3 assessmentLevel'],
                ],*/
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
                /*[
                    'name'  => 'assessedValue',
                    'type'  => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                        'readonly' => 'readonly',
                    ],
                    'label' => 'Assessed Value',
                    'wrapper' => ['class' => 'form-group col-md-3 assessedValue'],
                ],*/
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

        RptBuildings::creating(function($entry) {
            $count = RptBuildings::count();
            $refID = 'RPT-BLDG-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $request = app(RptBuildingsRequest::class);

            if($request->isApproved === '1') {
                // $TDNo = 'TD-BLDG-'.$request->barangayCode.'-01-'.str_pad(($count), 5, "0", STR_PAD_LEFT);
                $TDNo = 'TD-BLDG-'.str_pad(($count), 6, "0", STR_PAD_LEFT);
                $entry->TDNo = $TDNo;
            }

            TransactionLogs::create([
                'transId' =>$refID,
                'category' =>'rpt_building',
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
        
        RptBuildings::updating(function($entry) {
            $count = RptBuildings::count();
            $request = app(RptBuildingsRequest::class);

            if($request->isApproved === '1') {
                $TDNo = 'TD-BLDG-'.str_pad(($count), 6, "0", STR_PAD_LEFT);
                $entry->TDNo = $TDNo;
            }
          
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'faas_building',
                'type' =>'update',
            ]);
        });
    }

    public function create()
    {
        Widget::add()->type('script')->content('assets/js/rpt/create-building-functions.js');
        $this->crud->hasAccessOrFail('create');
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;
        return view('rpt.building.create', $this->data);
    }

    public function edit($id)
    {
        Widget::add()->type('script')->content('assets/js/rpt/edit-building-functions.js');
        $this->crud->hasAccessOrFail('update');
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntryWithLocale($id);
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id'] = $id;

        return view('rpt.building.edit', $this->data);
    }

    public function applySearchFilters(Request $request){
        $searchByPrimaryOwner = $request->input('searchByPrimaryOwner');
        $searchByReferenceId = $request->input('searchByReferenceId');
        $searchByBuildingClassification = $request->input('searchByBuildingClassification');
        $searchByStructuralType = $request->input('searchByStructuralType');
        $searchByLandReferenceId = $request->input('searchByLandReferenceId');
        $searchByPrimaryOwnerAddress = $request->input('searchByPrimaryOwnerAddress');

        $results = [];

        $citizenProfile = BuildingProfile::select('faas_building_profiles.id', 'faas_building_profiles.refID', 'faas_building_profiles.primary_owner', 'faas_building_profiles.ownerAddress',
            'faas_building_profiles.kind_of_building_id', 'faas_building_profiles.structural_type_id', 'faas_building_profiles.landProfileId',
            'faas_building_profiles.isActive',
            'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'),
            'faas_lands.refID as landRefID')
            ->join('citizen_profiles', 'faas_building_profiles.primary_owner', '=', 'citizen_profiles.id')
            ->join('faas_lands', 'faas_building_profiles.landProfileId', '=', 'faas_lands.id')
            ->with('citizen_profile')
            ->with('faas_land_profile')
            ->with('barangay')
            ->with('building_owner')
            ->with('structural_type')
            ->with('building_classification');

        $nameProfile = BuildingProfile::select('faas_building_profiles.id', 'faas_building_profiles.refID', 'faas_building_profiles.primary_owner', 'faas_building_profiles.ownerAddress', 
            'faas_building_profiles.kind_of_building_id', 'faas_building_profiles.structural_type_id', 'faas_building_profiles.landProfileId',
            'faas_building_profiles.isActive',
            'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'),
            'faas_lands.refID as landRefID')
            ->join('name_profiles', 'faas_building_profiles.primary_owner', '=', 'name_profiles.id')
            ->join('faas_lands', 'faas_building_profiles.landProfileId', '=', 'faas_lands.id')
            ->with('name_profile')
            ->with('faas_land_profile')
            ->with('barangay')
            ->with('building_owner')
            ->with('structural_type')
            ->with('building_classification');

        if (!empty($searchByPrimaryOwnerAddress)) { 
            $citizenProfile->where('faas_building_profiles.ownerAddress', 'like', '%'.$searchByPrimaryOwnerAddress.'%');
            $nameProfile->where('faas_building_profiles.ownerAddress', 'like', '%'.$searchByPrimaryOwnerAddress.'%');
        }

        if (!empty($searchByReferenceId)) { 
            $citizenProfile->where('faas_building_profiles.refID', 'like', '%'.$searchByReferenceId.'%');
            $nameProfile->where('faas_building_profiles.refID', 'like', '%'.$searchByReferenceId.'%');
        }

        if (!empty($searchByBuildingClassification)) { 
            $citizenProfile->where('faas_building_profiles.kind_of_building_id', '=', $searchByBuildingClassification);
            $nameProfile->where('faas_building_profiles.kind_of_building_id', '=', $searchByBuildingClassification);
        }

        if (!empty($searchByStructuralType)) { 
            $citizenProfile->where('faas_building_profiles.structural_type_id', '=', $searchByStructuralType);
            $nameProfile->where('faas_building_profiles.structural_type_id', '=', $searchByStructuralType);
        }

        if (!empty($searchByLandReferenceId)) { 
            $citizenProfile->where('faas_lands.refID', 'like', '%'.$searchByLandReferenceId.'%');
            $nameProfile->where('faas_lands.refID', 'like', '%'.$searchByLandReferenceId.'%');
        }

        if (!empty($searchByPrimaryOwner)) {
            $citizenProfile->where(DB::raw('CONCAT(citizen_profiles.fName," ",citizen_profiles.mName," ",citizen_profiles.lName)'), 'like', '%'.$searchByPrimaryOwner.'%');
            $nameProfile->where(DB::raw('CONCAT(name_profiles.first_name," ",name_profiles.middle_name," ",name_profiles.last_name)'), 'like', '%'.$searchByPrimaryOwner.'%');
        }

        $citizenProfiles = $citizenProfile->where('faas_building_profiles.isActive', '=', '1')->orderBy('faas_building_profiles.refID','ASC')->get();
        $nameProfiles = $nameProfile->where('faas_building_profiles.isActive', '=', '1')->orderBy('faas_building_profiles.refID','ASC')->get();

        $results = $citizenProfiles->merge($nameProfiles);

        return $results;
    }

    public function getDetails(Request $request){
        $id = $request->input('id');
        
        $results = [];
        if (!empty($id))
        {
            $citizenProfiles = RptBuildings::select('rpt_buildings.*',
                'faas_building_profiles.refID as faasRefId', 'faas_building_profiles.primary_owner', 'faas_building_profiles.ownerAddress',
                'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType')
                )
                ->join('faas_building_profiles', 'rpt_buildings.faasId', '=', 'faas_building_profiles.id')
                ->join('citizen_profiles', 'faas_building_profiles.primary_owner', '=', 'citizen_profiles.id')
                ->with('citizen_profile')
                ->where('rpt_buildings.isActive', '=', '1')
                ->where('rpt_buildings.id', '=', $id)
                ->get();

            $nameProfiles = RptBuildings::select('rpt_buildings.*',
                'faas_building_profiles.refID as faasRefId', 'faas_building_profiles.primary_owner', 'faas_building_profiles.ownerAddress',
                'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType')
                )
                ->join('faas_building_profiles', 'rpt_buildings.faasId', '=', 'faas_building_profiles.id')
                ->join('name_profiles', 'faas_building_profiles.primary_owner', '=', 'name_profiles.id')
                ->with('name_profile')
                ->where('rpt_buildings.isActive', '=', '1')
                ->where('rpt_buildings.id', '=', $id)
                ->get();
            
            $results = $citizenProfiles->merge($nameProfiles);
        }

        return $results;
    }
}
