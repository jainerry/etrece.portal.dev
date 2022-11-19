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
        $this->crud->addColumn([
            'name'  => 'primaryOwner',
            'label' => 'Primary Owner',
            'type'  => 'select',
            'entity'    => 'citizen_profile',
            'attribute' => 'full_name'
        ],);
        $this->crud->column('ownerAddress')->limit(255)->label('Owner Address');
        $this->crud->addColumn([
            'label'=>'Status',
            'type'  => 'model_function',
            'function_name' => 'getStatus',
        ]);
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
        $this->crud->addField([
            'name' => 'machinery_owner',
            'label' => 'Secondary Owner/s',
            'type' => 'secondary_owner',
            'entity' => 'machinery_owner',
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
            'label'=>'Barangay',
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
            'data_source' => url('/admin/api/citizen-profile/ajaxsearch'),
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
        $this->crud->addField([   // n-n relationship
            'label' => 'Building Owner',
            'type' => 'primary_owner_input',
            'name' => 'buildingOwnerId',
            'entity' => 'building_owner_citizen_profile',
            'attribute' => 'full_name',
            'data_source' => url('/admin/api/citizen-profile/ajaxsearch'),
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
            'subfields' => [ // also works as: "fields"
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
                    'name'  => 'originalCost',
                    'type'  => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
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
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'rateOfDepreciation',
                    'type'  => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_percent',
                    ],
                    'label' => 'Rate of Depreciation',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'totalDepreciationPercentage',
                    'type'  => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_percent',
                    ],
                    'label' => 'Total Depreciation - %',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'totalDepreciationValue',
                    'type'  => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
                    ],
                    'label' => 'Total Depreciation - Value',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'  => 'depreciatedValue',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
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
                    'attribute' => 'name',
                    'wrapper' => ['class' => 'form-group col-md-3 actualUse'],
                ],
                [
                    'name'    => 'marketValue',
                    'type'    => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
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
                    ],
                    'label'   => 'Assessment Level',
                    'wrapper' => ['class' => 'form-group col-md-3 assessmentLevel'],
                ],
                [
                    'name'  => 'assessedValue',
                    'type'  => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency',
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
            'max_rows' => 10,
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

        FaasMachinery::creating(function($entry) {
            $count = FaasMachinery::count();
            $refID = 'MACHINERY-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $transCount = TransactionLogs::count();
            $transRefID = 'TRANS-LOG'.'-'.str_pad(($transCount), 4, "0", STR_PAD_LEFT);

            TransactionLogs::create([
                'refID' => $transRefID,
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

            $transCount = TransactionLogs::count();
            $transRefID = 'TRANS-LOG'.'-'.str_pad(($transCount), 4, "0", STR_PAD_LEFT);
          
            TransactionLogs::create([
                'refID' => $transRefID,
                'transId' =>$entry->refID,
                'category' =>'faas_machinery',
                'type' =>'update',
            ]);
        });
    }
    
}
