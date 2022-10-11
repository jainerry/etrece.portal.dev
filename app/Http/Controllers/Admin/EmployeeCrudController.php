<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EmployeeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EmployeeCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Employee::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/employee');
        CRUD::setEntityNameStrings('employee', 'employees');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        
        CRUD::column('employeeId');
        CRUD::column('firstName');
        CRUD::column('LastName');

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
        CRUD::setValidation(EmployeeRequest::class);

        CRUD::field('employeeId');
        CRUD::field('IDNo');
        CRUD::field('lastName');
        CRUD::field('firstName');
        CRUD::field('middleName');
        //CRUD::field('birthDate');
        CRUD::addField([
            'label'        => "Birth Date",
            'name'         => "birthDate",
            'type'         => 'date',
        ]);
        CRUD::field('bloodType');
        CRUD::field('tinNo');
        CRUD::field('bpNo');
        CRUD::field('emergencyContactPerson');
        CRUD::field('emergencyContactRelationship');
        CRUD::field('emergencyContactAddress1');
        CRUD::field('emergencyContactAddress2');
        CRUD::field('oldIDNo');
        //CRUD::field('departmentId');
        CRUD::addField([
            'label'        => "Department",
            'name'         => "departmentId",
            'type'         => 'select',
            'entity'    => 'department',
            'model'     => "App\Models\Department",
            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->where('isActive', 'Y')->get();
            }), 
        ]);
        
        CRUD::field('sectionId');
        CRUD::field('positionId');
        CRUD::field('picName');
        CRUD::field('halfPicName');
        CRUD::field('signName');
        CRUD::field('empPrint');
        CRUD::field('workStatus');
        CRUD::field('remarks');
        CRUD::field('encryptCode');
        CRUD::field('contactNo');
        CRUD::field('smallPrint');
        CRUD::field('suffix');
        CRUD::field('birthPlace');
        CRUD::field('civilStatus');
        CRUD::field('citizenShip');
        CRUD::field('citizenShipAcquisition');
        CRUD::field('country');
        CRUD::field('sex');
        CRUD::field('height');
        CRUD::field('weight');
        CRUD::field('pagibigNo');
        CRUD::field('philhealthNo');
        CRUD::field('sssNo');
        CRUD::field('landlineNo');
        CRUD::field('email');
        CRUD::field('residentialAddress');
        CRUD::field('permanentAddress');
        CRUD::field('residentialSitio');
        CRUD::field('permanentSitio');
        CRUD::field('isActive');

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
