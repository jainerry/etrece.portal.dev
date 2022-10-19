<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BuildingProfileRequest;
use App\Models\BuildingProfile;
use App\Models\CitizenProfile;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


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
        CRUD::field('code');
        CRUD::addField([
            'label'    => "Primary Owner", 
            'type'      => 'select2_from_ajax',
            'name'     => 'primary_owner', 
            'entity'   => 'citizen_profile',
            'attribute' => 'data',
            'data_source'   => url("/admin/api/cp"),
            
        ]);
    // CRUD::addField([
    //     'name'     => 'second_owner', // JSON variable name
    //     'label'    => "Second Owner", // human-readable label for the input
    
    //     'fake'     => true, // show the field, but don't store it in the database column above
    //     'store_in' => 'extras' // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
    // ]);

    CRUD::addField([   
        'name'        => 'isActive',
        'label'       => "isActive",
        'type'        => 'select_from_array',
        'options'     => ['Y'=>'TRUE','N'=>'FALSE'],
        'allows_null' => false,
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-lg-12'
        ]
    ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
        BuildingProfile::creating(function($entry) {
            // $req  = app(BuildingProfileRequest::class);
            // dd($req);
            $count = BuildingProfile::select(DB::raw('count(*) as count'))->where('arpNo','like',"%".Date('mdY')."%")->first();
            $arpNo = 'BPID'.Date('mdY').'-'.str_pad(($count->count), 4, "0", STR_PAD_LEFT);
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
        CRUD::addField([
            'label'    => "Primary Owner", 
            'type'      => 'select2_from_ajax',
            'name'     => 'primary_owner', 
            'entity'   => 'citizen_profile',
            'attribute' => 'entry_data',
            'data_source'   => url("/admin/api/cp"),
            
        ]);
    }
}
