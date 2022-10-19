<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BuildingProfileRequest;
use App\Models\BuildingProfile;
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
        CRUD::addField([    // Select2Multiple = n-n relationship (with pivot table)
            'label'     => "Owner",
            'type'      => 'select2_multiple',

            'name'      => 'citizen_profile', // the method that defines the relationship in your Model
       
            // optional
            'entity'    => 'citizen_profile', // the method that defines the relationship in your Model
            'model'     => "App\Models\CitizenProfile", // foreign key model
            'attribute' => 'full_name_with_id_and_address', // foreign key attribute that is shown to user
            'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
            // 'select_all' => true, // show Select All and Clear buttons?
       
            // optional
            'options'   => (function ($query) {
                return $query->orderBy('fName', 'ASC')->get();
            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
       ],);
       $this->crud->addField([   
        'name'        => 'isActive',
        'label'       => "isActive",
        'type'        => 'select_from_array',
        'options'     => ['y'=>'TRUE','n'=>'FALSE'],
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
            // dd($this->crud);
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
    }
}
