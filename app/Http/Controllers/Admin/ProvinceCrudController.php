<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProvinceRequest;
use App\Models\Province;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
/**
 * Class ProvinceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProvinceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view-provinces', ['only' => ['index','show']]);
        $this->middleware('can:create-provinces', ['only' => ['create','store']]);
        $this->middleware('can:edit-provinces', ['only' => ['edit','update']]);
        $this->middleware('can:delete-provinces', ['only' => ['destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
       $this->crud->setModel(\App\Models\Province::class);
       $this->crud->setRoute(config('backpack.base.route_prefix') . '/province');
       $this->crud->setEntityNameStrings('province', 'provinces');
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
        $this->crud->removeButton('update');  
        $this->crud->orderBy('refID','desc');
        $this->crud->addClause('where', 'isActive', '=', 'y');
        $this->crud->addFilter([
            'type'  => 'date',
            'name'  => 'created_at',
            'label' => 'Created At'
          ],
            false,
          function ($value) { // if the filter is active, apply these constraints
            $this->crud->addClause('whereDate', 'created_at', $value);
          });
          $this->crud->addFilter([
            'type'  => 'select2',
            'name'  => 'region_id',
            'label' => 'Region'
          ],
          function() {
            return \App\Models\Regions::all()->pluck('name', 'id')->toArray();
            },
          function ($value) { // if the filter is active, apply these constraints
            $this->crud->addClause('where', 'region_id', $value);
          });
          

        $this->crud->addColumn([
            // Select
            'label'     => 'Reference ID',
            'type'      => 'text',
            'name'      => 'refID', // the db column for the foreign key
            'wrapper'   => [
                // 'element' => 'a', // the element will default to "a" so you can skip it here
                'href' => function ($crud, $column, $entry, ) {
                    return route('province.edit',$entry->id);
                },
            ],
          ]);


        $this->crud->removeButton('delete');  
        $this->crud->removeButton('show');
       $this->crud->Column('region_id');
       $this->crud->Column('name');
       $this->crud->Column('created_at');
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * -$this->crud->column('price')->type('number');
         * -$this->crud->addColumn(['name' => 'price', 'type' => 'number']); 
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
       $this->crud->setValidation(ProvinceRequest::class);
       $this->crud->addField([  // Select
        'label'     => "Region",
        'type'      => 'select',
        'name'      => 'region_id', 
        'entity'    => 'region',
     
        // optional - manually specify the related model and attribute
        'model'     => "App\Models\Regions", // related model
        'attribute' => 'name', // foreign key attribute that is shown to user
     
        // optional - force the related options to be a custom query, instead of all();
        'options'   => (function ($query) {
             return $query->orderBy('name', 'ASC')->get();
         }), //  you can use this to filter the results show in the select
     ]);
        
       $this->crud->addField([
            'name' => 'name',
            'label' => 'Province Name',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6',
            ]
        ]);

        Province::creating(function($entry) {
            $count = Province::select(DB::raw('count(*) as count'))->where('refID','like',"%".Date('mdY')."%")->first();
            $refId = 'PROV'.Date('mdY').'-'.str_pad(($count->count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refId;
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
