<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BuildingProfileRequest;
use App\Models\BuildingProfile;
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
    //     CRUD::addField([    // Select2Multiple = n-n relationship (with pivot table)
    //         'label'     => "Owner",
    //         'type'      => 'select2_multiple',

    //         'name'      => 'citizen_profile', // the method that defines the relationship in your Model
       
    //         // optional
    //         'entity'    => 'citizen_profile', // the method that defines the relationship in your Model
    //         'model'     => "App\Models\CitizenProfile", // foreign key model
    //         'attribute' => 'full_name_with_id_and_address', // foreign key attribute that is shown to user
    //         'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
    //         // 'select_all' => true, // show Select All and Clear buttons?
       
    //         // optional
    //         'options'   => (function ($query) {
    //             return $query->orderBy('fName', 'ASC')->get();
    //         }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
    //    ],);
    CRUD::addField([   // n-n relationship
        'label'       => "Owner", // Table column heading
        'type'        => "select2_from_ajax_multiple",
        'name'        => 'citizen_profile', // a unique identifier (usually the method that defines the relationship in your Model)
        'entity'      => 'citizen_profile', // the method that defines the relationship in your Model
        'attribute'   => "fullname", // foreign key attribute that is shown to user
        'data_source' => url("admin/api/cp"), // url to controller search function (with /{id} should return model)
        'pivot'       => true, // on create&update, do you need to add/delete pivot table entries?
    
        // OPTIONAL
        'delay'                      => 500, // the minimum amount of time between ajax requests when searching in the field
        'model'                      => "App\Models\CitizenProfile", // foreign key model
        'placeholder'                => "Select a Profile", // placeholder for the select
        'minimum_input_length'       => 1, // minimum characters to type before querying results
        // 'method'                  => 'POST', // optional - HTTP method to use for the AJAX call (GET, POST)
        // 'include_all_form_fields' => false, // optional - only send the current field through AJAX (for a smaller payload if you're not using multiple chained select2s)
    ]);
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
            $req = app(BuildingProfileRequest::class);
            dd($req->citizen_profile);
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
