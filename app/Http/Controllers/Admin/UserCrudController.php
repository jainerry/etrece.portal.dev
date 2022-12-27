<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        /*$this->middleware('can:view-users', ['only' => ['index','show']]);
        $this->middleware('can:create-users', ['only' => ['create','store']]);
        $this->middleware('can:edit-users', ['only' => ['edit','update']]);
        $this->middleware('can:delete-users', ['only' => ['destroy']]);*/
        //$this->middleware('can:Authentication > Users', ['only' => ['index','show','create','store','edit','update','destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\User::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
        $this->crud->setEntityNameStrings('user', 'users');
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
        
       $this->crud->column('name');
       $this->crud->column('email');

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
       $this->crud->setValidation(UserRequest::class);

       $this->crud->field('name');
       $this->crud->field('email');
       $this->crud->field('password');

        //$this->crud->field('name')->validationRules('required|min:5');
        //$this->crud->field('email')->validationRules('required|email|unique:users,email');
        //$this->crud->field('password')->validationRules('required');

        // \App\Models\User::creating(function ($entry) {
        //     $entry->password = \Hash::make($entry->password);
        // });

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * -$this->crud->field('price')->type('number');
         * -$this->crud->addField(['name' => 'price', 'type' => 'number'])); 
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

        //$this->crud->field('name')->validationRules('required|min:5');
        //$this->crud->field('email')->validationRules('required|email|unique:users,email,'.CRUD::getCurrentEntryId());
        //$this->crud->field('password')->hint('Type a password to change it.');

        // \App\Models\User::updating(function ($entry) {
        //     if (request('password') == null) {
        //         $entry->password = $entry->getOriginal('password');
        //     } else {
        //         $entry->password = \Hash::make(request('password'));
        //     }
        // });
    }
}
