<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StreetRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Barangay;
use App\Models\Street;
use App\Models\TransactionLogs;

/**
 * Class StreetCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StreetCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view-streets', ['only' => ['index','show']]);
        $this->middleware('can:create-streets', ['only' => ['create','store']]);
        $this->middleware('can:edit-streets', ['only' => ['edit','update']]);
        $this->middleware('can:delete-streets', ['only' => ['destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\Street::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/street');
        $this->crud->setEntityNameStrings('street', 'streets');
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
            'name'  => 'barangay_id',
            'label' => 'Barangay'
          ],
          function() {
            return \App\Models\Barangay::all()->pluck('name', 'id')->toArray();
            },
          function ($value) { // if the filter is active, apply these constraints
            $this->crud->addClause('where', 'barangay_id', $value);
          });
        $this->crud->addColumn([
            'label'     => 'Reference ID',
            'type'      => 'text',
            'name'      => 'refID',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('street.edit',$entry->id);
                },
            ]
        ]);
        $this->crud->column('name');
        $this->crud->addColumn([
            'name'=>'barangay_id',
            'label' => "Barangay",
            'type'=>'select',
            'entity' => 'barangay',
            'attribute' => 'name',
        ]);
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
        $this->crud->setValidation(StreetRequest::class);

        $this->crud->addField([
            'name'=>'barangay_id',
            'label' => "Barangay",
            'type'=>'select',
            'entity' => 'barangay',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4',
            ]
        ]);
        $this->crud->addField(
            [
                'name'=>'name',
                'label'=>'Name',
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4'
                ]
            ]
        );
        $this->crud->addField([
            'name'  => 'separator2a',
            'type'  => 'custom_html',
            'value' => '<hr>',
        ]);
        $this->crud->addField(
            [
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
            ]
        );

        Street::creating(function($entry) {
            $count = Street::count();
            $refID = 'STREET-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $transCount = TransactionLogs::count();
            $transRefID = 'TRANS-LOG'.'-'.str_pad(($transCount), 4, "0", STR_PAD_LEFT);

            TransactionLogs::create([
                'refID' => $transRefID,
                'transId' =>$refID,
                'category' =>'street',
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

        Street::updating(function($entry) {

            $transCount = TransactionLogs::count();
            $transRefID = 'TRANS-LOG'.'-'.str_pad(($transCount), 4, "0", STR_PAD_LEFT);
          
            TransactionLogs::create([
                'refID' => $transRefID,
                'transId' =>$entry->refID,
                'category' =>'street',
                'type' =>'update',
            ]);
        });
    }
}
