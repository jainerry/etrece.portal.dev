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
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\BuildingProfile::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/building-profile');
        CRUD::setEntityNameStrings('building profile', 'building profiles');
        CRUD::setCreateView('buildingProfile.create');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('arpNo');
        
        CRUD::column('code');
        CRUD::addColumn([
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
        CRUD::column('isActive');
        CRUD::column('created_at');
        CRUD::column('updated_at');

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
        CRUD::setValidation(BuildingProfileRequest::class);
        CRUD::addField([
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
       
        CRUD::addField([
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
        CRUD::addField([
            'name' => 'tel_no', 
            'label' => 'Tel No.', 
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
        CRUD::addField([
            'name' => 'owner_tin_no', 
            'label' => 'TIN Number:', 
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
      
        
        CRUD::addField([   // CustomHTML
            'name'  => 'separator',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'             => 'Main Information',
        ]);
        CRUD::addField([
            'name' => 'administrator',
            'label' => 'Administrator',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
        CRUD::addField([
            'name' => 'admin_address',
            'label' => 'Administrator Address',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
        CRUD::addField([
            'name' => 'admin_tel_no',
            'label' => 'Administrator Tel No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
        CRUD::addField([
            'name' => 'admin_tin_no',
            'label' => 'Administrator Tin No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Main Information',
        ]);
        CRUD::addField([
            'name' => 'isActive',
            'label' => 'isActive',
            'type' => 'select_from_array',
            'options' => ['Y' => 'TRUE', 'N' => 'FALSE'],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-12',
            ],
            'tab'             => 'Main Information',
        ]);

        // Building Location
        CRUD::addField([
            'name' => 'no_of_street',
            'label' => 'No. Of Street',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Building Location',
        ]);
      
        CRUD::addField([
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
        CRUD::addField([
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
        CRUD::addField([
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
        CRUD::addField([
            'name' => 'oct_tct_no',
            'label' => 'OCT/TCT No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Building Location',
        ]);
        CRUD::addField([
            'name' => 'lot_no',
            'label' => 'Lot No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Building Location',
        ]);
        CRUD::addField([
            'name' => 'block_no',
            'label' => 'Block No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Building Location',
        ]);
        CRUD::addField([
            'name' => 'survey_no',
            'label' => 'Survey No.',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Building Location',
        ]);
        CRUD::addField([
            'name' => 'area',
            'label' => 'Area',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'Building Location',
        ]);

        // General Description
       
        CRUD::addField([
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
        
        CRUD::addField([
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
        CRUD::addField([
            'name' => 'building_permit_no',
            'label' => 'Building Permit No',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        CRUD::addField([
            'name' => 'building_permit_date_issued',
            'label' => 'Building Permit Date No',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        CRUD::addField([
            'name' => 'condominium_certificate_of_title',
            'label' => 'Condominium Certificate of Title (CCT)',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        CRUD::addField([
            'name' => 'certificate_of_completion_issued_on',
            'label' => 'Certificate of Completion Issued On',
            'type' => 'text',
            'date' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        CRUD::addField([
            'name' => 'certificate_of_occupancy_issued_on',
            'label' => 'Certificate of Occupancy Issued On',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        CRUD::addField([
            'name' => 'date_constructed',
            'label' => 'Date Constructed',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        CRUD::addField([
            'name' => 'date_occupied',
            'label' => 'Date Occupied',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        CRUD::addField([
            'name' => 'no_of_storeys',
            'label' => 'No. of Storeys',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        CRUD::addField([
            'name' => 'area_first_floor',
            'label' => 'Area of 1st Floor',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        CRUD::addField([
            'name' => 'area_second_floor',
            'label' => 'Area of 2nd Floor',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        CRUD::addField([
            'name' => 'area_third_floor',
            'label' => 'Area of 3rd Floor',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        CRUD::addField([
            'name' => 'area_fourth_floor',
            'label' => 'Area of 4th Floor',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ],
            'tab'             => 'General Description',
        ]);
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
        BuildingProfile::creating(function ($entry) {
            // $req  = app(BuildingProfileRequest::class);
            // dd($req);
            $count = BuildingProfile::select(DB::raw('count(*) as count'))
                ->where('arpNo', 'like', '%' . Date('mdY') . '%')
                ->first();
            $arpNo = 'BPID' . Date('mdY') . '-' . str_pad($count->count, 4, '0', STR_PAD_LEFT);
            $entry->arpNo = $arpNo;
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
