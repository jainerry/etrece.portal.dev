<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TransactionLogsRequest;
use App\Models\TransactionLogs;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TransactionLogsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TransactionLogsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:Transaction Logs', ['only' => ['index','show','create','store','edit','update','destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\TransactionLogs::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/transaction-logs');
        $this->crud->setEntityNameStrings('transaction logs', 'transaction logs');
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
            'type'  => 'dropdown',
            'name'  => 'type',
            'label' => 'Type',
          ],
          function(){
            $logs = TransactionLogs::select(['type'])->groupBy('type')->get()->toArray();
            $typeFilter = [];
            foreach($logs as $index => $log){
                $typeFilter[$log['type']] =$log['type'];
                //  array_merge($typeFilter,[$log['type']=>$log['type']]);
              }
              return $typeFilter;
          },
            function($value){
                $this->crud->addClause('where','type', $value);
            }
         );
         $this->crud->addFilter([
            'type'  => 'dropdown',
            'name'  => 'category',
            'label' => 'Category',
          ],
          function(){
            $logs = TransactionLogs::select(['category'])->groupBy('category')->get()->toArray();
            $typeFilter = [];
            foreach($logs as $index => $log){
                $typeFilter[$log['category']] =$log['category'];
                //  array_merge($typeFilter,[$log['type']=>$log['type']]);
              }
              return $typeFilter;
          },
            function($value){
                $this->crud->addClause('where','category', $value);
            }
         );

        // $this->crud->column('refID');
        $this->crud->addColumn([
          'label'     => 'Reference ID',
          'type'      => 'text',
          'name'      => 'refID',
          'wrapper'   => [
              'href' => function ($crud, $column, $entry, ) {
                  return route('transaction-logs.edit',$entry->id);
              },
          ]
      ]);

        $this->crud->column('transId');
        $this->crud->column('category');
        $this->crud->column('type');
        $this->crud->column('created_at');


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']); 
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
        $this->crud->setValidation(TransactionLogsRequest::class);
        $this->crud->field('transId');
        $this->crud->field('category');
        $this->crud->field('type');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - $this->crud->field('price')->type('number');
         * - $this->crud->addField(['name' => 'price', 'type' => 'number'])); 
         */
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
