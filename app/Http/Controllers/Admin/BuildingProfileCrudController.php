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
        // Building Location
        CRUD::addField([
            'name' => 'no_of_street',
            'label' => 'No. Of Street:',
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
