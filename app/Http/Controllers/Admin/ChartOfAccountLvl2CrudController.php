<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChartOfAccountLvl2Request;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\ChartOfAccountLvl2;

/**
 * Class ChartOfAccountLvl2CrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ChartOfAccountLvl2CrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:Chart of Accounts > Level 2', ['only' => ['index','show','create','store','edit','update','destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ChartOfAccountLvl2::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/chart-of-account-lvl2');
        CRUD::setEntityNameStrings('chart of account lvl2', 'chart of account lvl2s');
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

        $this->crud->addColumn([
            'label'     => 'Reference ID',
            'type'      => 'text',
            'name'      => 'refID',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('chart-of-account-lvl2.edit',$entry->id);
                },
            ],
          ]);

        $this->crud->column('name')->limit(255);
        $this->crud->column('code');
        $this->crud->addColumn([
            'name'=>'lvl1ID',
            'label' => "Level 1",
            'type'=>'select',
            'entity' => 'parentLevel1',
            'attribute' => 'code_name',
            'limit' => 255,
        ]);
        $this->crud->column('isActive')->label('Status');
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
        CRUD::setValidation(ChartOfAccountLvl2Request::class);
        
        $this->crud->addField(
            [
                'name'=>'name',
                'label'=>'Name',
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'code',
                'label'=>'Code',
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
                ]
            ]
        );
        $this->crud->addField([
            'label' => "Parent (Level 1)",
            'type'=>'select',
            'name'=>'lvl1ID',
            'entity' => 'parentLevel1',
            'attribute' => 'code_name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6',
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator1',
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
