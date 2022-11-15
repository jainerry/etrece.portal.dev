<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FaasLandRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use App\Models\FaasLand;
use Backpack\CRUD\app\Library\Widget;

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
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;

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
        CRUD::setModel(\App\Models\FaasLand::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/faas-land');
        //CRUD::setEntityNameStrings('faas land', 'faas lands');
        CRUD::setEntityNameStrings('land', 'lands');
        $this->crud->removeButton('delete');
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

        // CRUD::column('ARPNo')->label('Reference No.');
        CRUD::column('refID')->label('Reference ID');
        
       /* CRUD::column('octTctNo')->label('OCT/TCT No.');
        CRUD::column('pin')->label('PIN');

        CRUD::column('transactionCode')->label('Transaction Code');
        CRUD::column('TDNo')->label('TD No.');

        CRUD::column('lotNo')->label('Lot No.');
        CRUD::column('blkNo')->label('Block No.');
        
        CRUD::addColumn([
            'name'  => 'previousOwner',
            'label' => 'Previous Owner',
            'type'  => 'select',
            'entity'    => 'old_owner_citizen_profile',
            'attribute' => 'full_name'
        ],);*/
        
        CRUD::addColumn([
            'name'  => 'primaryOwner',
            'label' => 'Primary Owner',
            'type'  => 'select',
            'entity'    => 'citizen_profile',
            'attribute' => 'full_name'
        ],);
        CRUD::column('ownerAddress')->limit(255)->label('Owner Address');
        /*CRUD::column('ownerTelephoneNo')->label('Owner Telephone No.');
        CRUD::column('ownerTin')->label('Owner TIN');
        CRUD::column('administrator')->label('Administrator');
        CRUD::column('administratorAddress')->limit(255)->label('Administrator Address');
        CRUD::column('administratorTelephoneNo')->label('Administrator Telephone No.');
        CRUD::column('administratorTin')->label('Administrator TIN');
        CRUD::column('noOfStreet')->label('No. of Street');
        CRUD::addColumn([
            'name'  => 'barangay',
            'label' => 'Barangay',
            'type'  => 'select',
            'entity'    => 'barangay',
            'attribute' => 'name'
         ],);
        CRUD::addColumn([
            'name'  => 'land_owner',
            'label' => 'Secondary Owners', // Table column heading
            'type'  => 'select',
            'entity'    => 'land_owner',
            'attribute' => 'full_name'
         ],);
        CRUD::addColumn([
            'name'  => 'assessment_status',
            'label' => 'Assessment Status',
            'type'  => 'select',
            'entity'    => 'assessment_status',
            'attribute' => 'name'
        ],);*/
        CRUD::addColumn([
            'label'=>'Status',
            'type'  => 'model_function',
            'function_name' => 'getStatus',
        ]);
         
        /*CRUD::addColumn([
            'name'  => 'municipality',
            'label' => 'Municipality',
            'type'  => 'select',
            'entity'    => 'municipality',
            'attribute' => 'name'
         ],);
         CRUD::addColumn([
            'name'  => 'province',
            'label' => 'Province',
            'type'  => 'select',
            'entity'    => 'province',
            'attribute' => 'name'
         ],);
         
        CRUD::column('propertyBoundaryNorth')->label('Boundary North');
        CRUD::column('propertyBoundaryEast')->label('Boundary East');
        CRUD::column('propertyBoundarySouth')->label('Boundary South');
        CRUD::column('propertyBoundaryWest')->label('Boundary West');

        //CRUD::column('landSketch')->label('Land Sketch');

        CRUD::addColumn([
            'label'=>'Land Appraisal',
            'type'  => 'model_function',
            'function_name' => 'getLandAppraisal',
        ]);
        
        CRUD::addColumn([
            'label'=>'Other Improvements',
            'type'  => 'model_function',
            'function_name' => 'getOtherImprovements',
        ]);

        CRUD::addColumn([
            'label'=>'Market Value',
            'type'  => 'model_function',
            'function_name' => 'getMarketValue',
        ]);

        CRUD::addColumn([
            'label'=>'Property Assessment',
            'type'  => 'model_function',
            'function_name' => 'getPropertyAssessment',
        ]);

        CRUD::column('assessmentType')->label('Assessment Type');
        CRUD::column('assessmentEffectivity')->label('Assessment Effectivity');
        CRUD::column('assessmentEffectivityValue')->label('Assessment Effectivity Qtr./Yr.');
        CRUD::column('assessedBy')->label('Assessed By');
        CRUD::column('assessedDate')->label('Assessed Date');
        CRUD::column('recommendingPersonel')->label('Recommending Personel');
        CRUD::column('recommendingApprovalDate')->label('Recommending Approval Date');
        CRUD::column('approvedBy')->label('Approved By');
        CRUD::column('approvedDate')->label('Approved Date');
        CRUD::column('memoranda')->label('Memoranda');
        CRUD::column('recordOfAssesmentEntryDate')->label('Record of Assessment Entry Date');
        CRUD::column('recordingPersonel')->label('Recording Personel');*/

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
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
        CRUD::setValidation(FaasLandRequest::class);

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
            'name'=>'pin',
            'type'=>'text',
            'label'=>'PIN',
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

        CRUD::addField([   // CustomHTML
            'name'  => 'separator0',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Main Information',
        ]);

        $this->crud->addField([   // n-n relationship
            'label' => 'Primary Owner',
            'type' => 'primary_owner_input',
            'name' => 'primaryOwnerId',
            'entity' => 'citizen_profile',
            'attribute' => 'full_name',
            'data_source' => url('/admin/api/citizen-profile/ajaxsearch'),
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Main Information',
        ]);

        $this->crud->addField([   // n-n relationship
            'name' => 'land_owner', // JSON variable name
            'label' => 'Secondary Owner/s', // human-readable label for the input
            'type' => 'secondary_owner',
            'entity' => 'land_owner',
            'data_source' => url('/admin/api/citizen-profile/ajaxsearch'),
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
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-8'
            ],
            'tab' => 'Main Information',
        ]);

        $this->crud->addField([
            'name'=>'ownerTin',
            'label'=>'TIN',
            'type'=>'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_tin',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
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
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);

        CRUD::addField([   // CustomHTML
            'name'  => 'separator1',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Main Information',
        ]);
        
        $this->crud->addField([
            'name'=>'administrator',
            'label'=>'Administrator/Occupant',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);

        $this->crud->addField([
            'name'=>'administratorTin',
            'label'=>'TIN',
            'type'=>'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_tin',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);

        $this->crud->addField([
            'name'=>'administratorAddress',
            'label'=>'Address',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-8'
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
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);

        /*$this->crud->addField([
            'name'=>'assessmentStatusId',
            'label'=>'Assessment Status',
            'type'=>'select',
            'entity' => 'assessment_status',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);*/

        CRUD::addField([   // CustomHTML
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
            'label'=>'Barangay',
            'type'=>'select',
            'entity' => 'barangay',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);

        /*$this->crud->addField([
            'name'=>'cityId',
            'label'=>'Municipality',
            'type'=>'select',
            'entity' => 'municipality',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);

        $this->crud->addField([
            'name'=>'provinceId',
            'label'=>'Province',
            'type'=>'select',
            'entity' => 'province',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);*/

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
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 0, // omit or set to 0 to allow any aspect ratio
            // 'disk'      => 's3_bucket', // in case you need to show images from a different disk
            // 'prefix'    => 'uploads/images/profile_pictures/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
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
            'subfields' => [ // also works as: "fields"
                [
                    'name'    => 'classification',
                    'type'    => 'select',
                    'label'   => 'Classification',
                    'model'     => "App\Models\FaasLandClassification", // related model
                    'attribute' => 'code',
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
                    'model'     => "App\Models\FaasLandClassification", // related model
                    'attribute' => 'name',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'area',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                    ],
                    'label' => 'Area',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'unitValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                    ],
                    'label'   => 'Unit Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'baseMarketValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                    ],
                    'label'   => 'Base Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ]
            ],
        
            // optional
            'new_item_label'  => 'New Item', // customize the text of the button
            'init_rows' => 1, // number of empty rows to be initialized, by default 1
            'min_rows' => 1, // minimum rows allowed, when reached the "delete" buttons will be hidden
            'max_rows' => 10, // maximum rows allowed, when reached the "new item" button will be hidden
            // allow reordering?
            //'reorder' => false, // hide up&down arrows next to each row (no reordering)
            'reorder' => true, // show up&down arrows next to each row
            // 'reorder' => 'order', // show arrows AND add a hidden subfield with that name (value gets updated when rows move)
            // 'reorder' => ['name' => 'order', 'type' => 'number', 'attributes' => ['data-reorder-input' => true]], // show arrows AND add a visible number subfield
            'tab' => 'Land Appraisal',
        ]);

        $this->crud->addField([   
            'name'  => 'otherImprovements',
            'label' => 'Other Improvements',
            'type'  => 'repeatable',
            'subfields' => [ // also works as: "fields"
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
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'unitValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                    ],
                    'label'   => 'Unit Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'baseMarketValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                    ],
                    'label' => 'Base Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ]
            ],
        
            // optional
            'new_item_label'  => 'New Item', // customize the text of the button
            'init_rows' => 1, // number of empty rows to be initialized, by default 1
            'min_rows' => 1, // minimum rows allowed, when reached the "delete" buttons will be hidden
            'max_rows' => 10, // maximum rows allowed, when reached the "new item" button will be hidden
            // allow reordering?
            //'reorder' => false, // hide up&down arrows next to each row (no reordering)
            'reorder' => true, // show up&down arrows next to each row
            // 'reorder' => 'order', // show arrows AND add a hidden subfield with that name (value gets updated when rows move)
            // 'reorder' => ['name' => 'order', 'type' => 'number', 'attributes' => ['data-reorder-input' => true]], // show arrows AND add a visible number subfield
            'tab' => 'Other Improvements',
        ]);

        $this->crud->addField([   
            'name'  => 'marketValue',
            'label' => 'Market Value',
            'type'  => 'repeatable',
            'subfields' => [ // also works as: "fields"
                [
                    'name'    => 'baseMarketValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
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
                        'class' => 'form-control text_input_mask_percent',
                    ],
                    'label'   => '% Adj',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'valueAdjustment',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                    ],
                    'label' => 'Value Adjustment',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'marketValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                    ],
                    'label' => 'Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ]
            ],
        
            // optional
            'new_item_label'  => 'New Item', // customize the text of the button
            'init_rows' => 1, // number of empty rows to be initialized, by default 1
            'min_rows' => 1, // minimum rows allowed, when reached the "delete" buttons will be hidden
            'max_rows' => 10, // maximum rows allowed, when reached the "new item" button will be hidden
            // allow reordering?
            //'reorder' => false, // hide up&down arrows next to each row (no reordering)
            'reorder' => true, // show up&down arrows next to each row
            // 'reorder' => 'order', // show arrows AND add a hidden subfield with that name (value gets updated when rows move)
            // 'reorder' => ['name' => 'order', 'type' => 'number', 'attributes' => ['data-reorder-input' => true]], // show arrows AND add a visible number subfield
            'tab' => 'Market Value',
        ]);

        $this->crud->addField([   
            'name'  => 'propertyAssessment',
            'label' => 'Property Assessment',
            'type'  => 'repeatable',
            'subfields' => [ // also works as: "fields"
                [
                    'name'    => 'actualUse',
                    'type'    => 'select',
                    'label'   => 'Actual Use',
                    'model'     => "App\Models\FaasLandClassification", // related model
                    'attribute' => 'name',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'marketValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                    ],
                    'label'   => 'Market Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'assessmentLevel',
                    'type'    => 'select',
                    'model'     => "App\Models\FaasLandClassification", // related model
                    'attribute' => 'assessmentLevel',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_percent',
                    ],
                    'label'   => 'Assessment Level',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'assessmentValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                    ],
                    'label' => 'Assessment Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ]
            ],
        
            // optional
            'new_item_label'  => 'New Item', // customize the text of the button
            'init_rows' => 1, // number of empty rows to be initialized, by default 1
            'min_rows' => 1, // minimum rows allowed, when reached the "delete" buttons will be hidden
            'max_rows' => 10, // maximum rows allowed, when reached the "new item" button will be hidden
            // allow reordering?
            //'reorder' => false, // hide up&down arrows next to each row (no reordering)
            'reorder' => true, // show up&down arrows next to each row
            // 'reorder' => 'order', // show arrows AND add a hidden subfield with that name (value gets updated when rows move)
            // 'reorder' => ['name' => 'order', 'type' => 'number', 'attributes' => ['data-reorder-input' => true]], // show arrows AND add a visible number subfield
            'tab' => 'Property Assessment',
        ]);

        $this->crud->addField([
            'name'=>'assessmentType',
            'label'=>'Assessment Type',
            'type' => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
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
                // the key will be stored in the db, the value will be shown as label; 
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

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */

        FaasLand::creating(function($entry) {
            $count = FaasLand::select(DB::raw('count(*) as count'))->where('refID','like',"%".Date('mdY')."%")->first();
            $refID = 'LAND-'.Date('mdY').'-'.str_pad(($count->count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;
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
    }

    /**
     * Show the form for creating inserting a new row.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        Widget::add()->type('style')->content('assets/css/faas/styles.css');
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');
        Widget::add()->type('script')->content('assets/js/jquery.inputmask.bundle.min.js');
        Widget::add()->type('script')->content('assets/js/backpack/crud/inputmask.js');
        
        $this->crud->hasAccessOrFail('create');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('faas_land.create', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        Widget::add()->type('style')->content('assets/css/faas/styles.css');
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');
        Widget::add()->type('script')->content('assets/js/jquery.inputmask.bundle.min.js');
        Widget::add()->type('script')->content('assets/js/backpack/crud/inputmask.js');

        $this->crud->hasAccessOrFail('update');
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        // get the info for that entry

        $this->data['entry'] = $this->crud->getEntryWithLocale($id);
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('faas_land.edit', $this->data);
    }
    
}
