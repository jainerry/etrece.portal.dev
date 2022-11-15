<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BuildingProfileRequest;
use App\Models\BuildingProfile;
use App\Models\CitizenProfile;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

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
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
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
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->enableBulkActions();
        $this->crud->enableExportButtons();
        
        CRUD::column('arpNo')->label('Reference No.');
     
        $this->crud->column('code');
        $this->crud->addColumn([
            // run a function on the CRUD model and show its return value
            'name'  => 'primary_owner',
            'label' => 'Primary Owner', // Table column heading
            'type'  => 'select',
            'entity'    => 'citizen_profile',
            'attribute' => 'full_name', 
            // 'function_parameters' => [$one, $two], // pass one/more parameters to that method
            // 'limit' => 100, // Limit the number of characters shown
            // 'escaped' => false, // echo using {!! !!} instead of {{ }}, in order to render HTML
         ],);
        CRUD::addColumn([
            'name'  => 'assessment_status',
            'label' => 'Assessment Status',
            'type'  => 'select',
            'entity'    => 'assessment_status',
            'attribute' => 'name'
        ],);
        CRUD::addColumn([
            'label'=>'Status',
            'type'  => 'model_function',
            'function_name' => 'getStatus',
        ]);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']);
         */
    }
    protected function setupShowOperation()
    {
        $this->setupListOperation();
        $this->crud->addColumn([
            // run a function on the CRUD model and show its return value
            'name'  => 'primary_owner',
            'label' => 'Primary Owner', // Table column heading
            'type'  => 'select',
            'entity'    => 'citizen_profile',
            'attribute' => 'full_name', 
            // 'function_parameters' => [$one, $two], // pass one/more parameters to that method
            // 'limit' => 100, // Limit the number of characters shown
            // 'escaped' => false, // echo using {!! !!} instead of {{ }}, in order to render HTML
         ]);
         $this->crud->addColumn('roof');
         
         
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
        $this->crud->addField([
            'label' => 'Primary Owner',
            'type' => 'primary_owner_input',
            'name' => 'primary_owner',
            'entity' => 'citizen_profile',
            'attribute' => 'full_name',
            
            'data_source' => url('/admin/api/cp/search'),
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
       
        $this->crud->addField([
            'name' => 'building_owner', // JSON variable name
            'label' => 'Secondary Owner', // human-readable label for the input
            'type' => 'secondary_owner',
            'entity' => 'building_owner',
            'data_source' => url('/admin/api/cp/search'),
            'attribute' => 'full_name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'tel_no', 
            'label' => 'Tel No.', 
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'owner_tin_no', 
            'label' => 'TIN Number:', 
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
      
        
        $this->crud->addField([   // CustomHTML
            'name'  => 'separator',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'             => 'Main Information',
        ]);

        $this->crud->addField([
            'name' => 'administrator',
            'label' => 'Administrator',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'admin_address',
            'label' => 'Administrator Address',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'admin_tel_no',
            'label' => 'Administrator Tel No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'admin_tin_no',
            'label' => 'Administrator Tin No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
        $this->crud->addField([
            'name' => 'isActive',
            'label' => 'isActive',
            'type' => 'select_from_array',
            'options' => ['Y' => 'TRUE', 'N' => 'FALSE'],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4',
            ],
            'tab'             => 'Main Information',
        ]);

        $this->crud->addField([
            'name'=>'assessmentStatusId',
            'label'=>'Assessment Status',
            'type'=>'select',
            'entity' => 'assessment_status',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ],
            'tab' => 'Main Information',
        ]);

        // Building Location
        $this->crud->addField([
            'name' => 'no_of_street',
            'label' => 'No. of Street',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Building Location',
        ]);
      
        $this->crud->addField([
            'label' => "Barangay",
            'type'=>'select',
            'name'=>'barangay_id',
            'entity' => 'barangay',
            'attribute' => 'name',

            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab' => 'Building Location',
        ]);
        $this->crud->addField([
            'name'=>'municipality_id',
            'label' => "Municipality",
            'type'=>'select',
            'entity' => 'municipality',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab' => 'Building Location',
        ]);
        $this->crud->addField([
            'name'=>'province_id',
            'label' => "Province",
            'type'=>'select',
            'entity' => 'province',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab' => 'Building Location',
        ]);
        $this->crud->addField([
            'name' => 'oct_tct_no',
            'label' => 'OCT/TCT No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Building Location',
        ]);
        $this->crud->addField([
            'name' => 'lot_no',
            'label' => 'Lot No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Building Location',
        ]);
        $this->crud->addField([
            'name' => 'block_no',
            'label' => 'Block No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Building Location',
        ]);
        $this->crud->addField([
            'name' => 'survey_no',
            'label' => 'Survey No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Building Location',
        ]);
        $this->crud->addField([
            'name' => 'area',
            'label' => 'Area',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Building Location',
        ]);

        // General Description
       
        $this->crud->addField([
            'label' => "Kind of Building",
            'type'=>'select',
            'name'=>'kind_of_building_id',
            'entity' => 'kind_of_building',
            'attribute' => 'name',

            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
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
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab' => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'building_permit_no',
            'label' => 'Building Permit No',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'building_permit_date_issued',
            'label' => 'Building Permit Date No',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'condominium_certificate_of_title',
            'label' => 'Condominium Certificate of Title (CCT)',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'certificate_of_completion_issued_on',
            'label' => 'Certificate of Completion Issued On',
            'type' => 'text',
            'date' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'certificate_of_occupancy_issued_on',
            'label' => 'Certificate of Occupancy Issued On',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'date_constructed',
            'label' => 'Date Constructed',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'date_occupied',
            'label' => 'Date Occupied',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'no_of_storeys',
            'label' => 'No. of Storeys',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);

        $this->crud->addField([   // CustomHTML
            'name'  => 'separator2',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'   => 'General Description',
        ]);

        $this->crud->addField([
            'name' => 'area_first_floor',
            'label' => 'Area of 1st Floor',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'area_second_floor',
            'label' => 'Area of 2nd Floor',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'area_third_floor',
            'label' => 'Area of 3rd Floor',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        $this->crud->addField([
            'name' => 'area_fourth_floor',
            'label' => 'Area of 4th Floor',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);

       $this->crud->addField([   // Checklist
            'label'     => 'Roof',
            'type'      => 'checklist',
            'name'      => 'roof',
            'entity'    => 'roof',
            'attribute' => 'name',
            'model'     => "App\Models\StructuralRoofs",
            'pivot'     => true,
            'tab'             => 'Structural Characteristic',
        ]);
        $this->crud->addField([   // Checklist
            'label'     => 'Flooring',
            'type'      => 'checklist',
            'name'      => 'flooring',
            'entity'    => 'flooring',
            'attribute' => 'name',
            'model'     => "App\Models\StructuralFlooring",
            'pivot'     => true,
            'tab'             => 'Structural Characteristic',
        ]);
        $this->crud->addField([   // Checklist
            'label'     => 'Walling',
            'type'      => 'checklist',
            'name'      => 'walling',
            'entity'    => 'walling',
            'attribute' => 'name',
            'model'     => "App\Models\StructuralWalling",
            'pivot'     => true,
            'tab'             => 'Structural Characteristic',
        ]);
        $this->crud->addField([   // Checklist
            'label'     => 'Additional Items',
            'name' => 'additional_items',
            'type'      => 'relationship',
            'tab'       => 'Structural Characteristic',
            'subfields'       => [
                [
                    'name'=>'name',
                    'type' => 'text',
                ]
            ],
        ]);
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - $this->crud->field('price')->type('number');
         * - $this->crud->addField(['name' => 'price', 'type' => 'number']));
         */
        BuildingProfile::creating(function ($entry) {
            // $req  = app(BuildingProfileRequest::class);
            // dd($req);
            $count = BuildingProfile::select(DB::raw('count(*) as count'))
                ->where('arpNo', 'like', '%' . Date('mdY') . '%')
                ->first();
            $refID = 'BPID' . Date('mdY') . '-' . str_pad($count->count, 4, '0', STR_PAD_LEFT);
            // $entry->roof = json_encode($req->roof);
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

}