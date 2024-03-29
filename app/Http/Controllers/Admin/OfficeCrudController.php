<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OfficeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
/**
 * Class OfficeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OfficeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Office::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/office');
        CRUD::setEntityNameStrings('office', 'offices');
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

        CRUD::column('code');
        CRUD::column('name');
        CRUD::addColumn([
            'label'=>'Status',
            'type'  => 'model_function',
            'function_name' => 'getStatus',
        ]);
        CRUD::addColumn([
            'label'=>'Location',
            'type'  => 'model_function',
            'function_name' => 'getOfficeLocation',
        ]);
        CRUD::addColumn([
            'label'=>'Head',
            'type'  => 'model_function',
            'function_name' => 'getOfficeHead',
        ]);
        CRUD::column('contactNo');

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
        CRUD::setValidation(OfficeRequest::class);

        $employees = Employee::select('id', DB::raw("CONCAT(firstName,' ',lastName) AS fullName"))->where('isActive','Y')->get();
        $employeeOptions = [];
        foreach($employees as $employee){
            $employeeOptions += [$employee->id => $employee->fullName];
        }

        $this->crud->addField(
            [
                'name'=>'code',
                'label'=>'Code',
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4'
               ]
            ]
        );
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
        $this->crud->addField(
            [
                'name' => 'contactNo',
                'label' => 'Contact No.',
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'name',
                'label'=>'Name',
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-12'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'officeLocationId',
                'label'=>'Location',
                'type' => 'select',
                'entity' => 'officeLocation',
                'model' => 'App\Models\OfficeLocation',
                'attribute' => 'name',
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->where('isActive', 'Y')->get();
                }),
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
               ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'headId',
                'label'=>'Office Head',
                'type' => 'select_from_array',
                'options'   => $employeeOptions,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
               ]
            ]
        );
        

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
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
