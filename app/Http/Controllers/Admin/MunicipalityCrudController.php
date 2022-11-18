<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MunicipalityRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Municipality;
use App\Models\TransactionLogs;

/**
 * Class MunicipalityCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MunicipalityCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view-municipalities', ['only' => ['index','show']]);
        $this->middleware('can:create-municipalities', ['only' => ['create','store']]);
        $this->middleware('can:edit-municipalities', ['only' => ['edit','update']]);
        $this->middleware('can:delete-municipalities', ['only' => ['destroy']]);
    }


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\Municipality::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/cities');
        $this->crud->setEntityNameStrings('Cities', 'cities');
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
            'name'  => 'province_id',
            'label' => 'Province'
          ],
          function() {
            return \App\Models\Province::all()->pluck('name', 'id')->toArray();
            },
          function ($value) { // if the filter is active, apply these constraints
            $this->crud->addClause('where', 'province_id', $value);
          });

        $this->crud->addColumn([
            'label'     => 'Reference ID',
            'type'      => 'text',
            'name'      => 'refID',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('cities.edit',$entry->id);
                },
            ]
        ]);
        $this->crud->column('name');
        $this->crud->column('province');
        $this->crud->addColumn([
            'label'=>'Status',
            'type'  => 'model_function',
            'function_name' => 'getStatus',
        ]);
        $this->crud->column('created_at');
    }
   

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(MunicipalityRequest::class);
        $this->crud->addField([
            'name' => 'name',
            'label' => 'City',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4',
            ]
        ]);
        $this->crud->addField([
            'label' => 'ZIP Code',
            'type' => 'text',
            'name' => 'zipcode',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4',
            ],
        ]);
        $this->crud->addField([
            'name'=>'province',
            'label' => "Province",
            'type'=>'select',
            'entity' => 'province',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4',
            ]
        ]);
        $this->crud->addField([
            'name'  => 'separator2a',
            'type'  => 'custom_html',
            'value' => '<hr>',
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
                'class' => 'form-group col-12 col-md-3'
            ],
        ]);

        Municipality::creating(function($entry) {
            $count = Municipality::count();
            $refID = 'MUNICIPALITY-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $transCount = TransactionLogs::count();
            $transRefID = 'TRANS-LOG'.'-'.str_pad(($transCount), 4, "0", STR_PAD_LEFT);

            TransactionLogs::create([
                // 'refID' => $transRefID,
                'transId' =>$refID,
                'category' =>'municipality',
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

        Municipality::updating(function($entry) {

            $transCount = TransactionLogs::count();
            $transRefID = 'TRANS-LOG'.'-'.str_pad(($transCount), 4, "0", STR_PAD_LEFT);
          
            TransactionLogs::create([
                // 'refID' => $transRefID,
                'transId' =>$entry->refID,
                'category' =>'municipality',
                'type' =>'update',
            ]);
        });
    }
}
