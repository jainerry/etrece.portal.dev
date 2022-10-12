<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

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
        CRUD::addColumn([
            'label'=>'Full Name',
            'type'  => 'model_function',
            'function_name' => 'getFullName',
        ]);
        CRUD::addColumn([
            'label'=>'Department',
            'type'  => 'model_function',
            'function_name' => 'getDepartment',
        ]);
        CRUD::addColumn([
            'label'=>'Section',
            'type'  => 'model_function',
            'function_name' => 'getSection',
        ]);
        CRUD::addColumn([
            'label'=>'Position',
            'type'  => 'model_function',
            'function_name' => 'getPosition',
        ]);
        CRUD::addColumn([
            'label'=>'Status',
            'type'  => 'model_function',
            'function_name' => 'getStatus',
        ]);

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

        $this->crud->addField(
            [
                'name'=>'IDNo',
                'label'=>'ID No.',
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4'
               ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'oldIDNo',
                'label'=>'Old ID No.',
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4'
               ]
            ]
        );
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
                'class' => 'form-group col-12 col-md-4'
            ],
        ]);
        $this->crud->addField([
            'name'=>'firstName',
            'label'=>'First Name',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'middleName',
            'label'=>'Middle Name',
            'hint'=>'(optional)',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name'=>'lastName',
            'label'=>'Last Name',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'suffix',
            'label'=>'Suffix',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-1'
           ]
        ]);
        $this->crud->addField(
            [
                'name'=>'departmentId',
                'label'=>'Department',
                'type' => 'select',
                'entity' => 'department',
                'model' => 'App\Models\Department',
                'attribute' => 'name',
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->where('isActive', 'Y')->get();
                }),
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4'
               ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'sectionId',
                'label'=>'Section',
                'type' => 'select',
                'entity' => 'section',
                'model' => 'App\Models\Section',
                'attribute' => 'name',
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->where('isActive', 'Y')->get();
                }),
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4'
               ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'positionId',
                'label'=>'Position',
                'type' => 'select',
                'entity' => 'position',
                'model' => 'App\Models\Position',
                'attribute' => 'name',
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->where('isActive', 'Y')->get();
                }),
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4'
               ]
            ]
        );
        $this->crud->addField([
            'name'=>'sex',
            'label'=>'Sex',
            'type' => 'select_from_array',
            'options' => [
                'Male' => 'Male',
                'Female' => 'Female'
            ],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name'=>'birthDate',
            'label'=>'Birth Date',
            'type'=>'date',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name'=>'civilStatus',
            'label'=>'Civil Status',
            'type' => 'select_from_array',
            'options' => [
                'Single' => 'Single',
                'Married' => 'Married',
                'Divorced' => 'Divorced',
                'Separated' => 'Separated'
            ],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([ 
            'name' => 'bloodType',
            'label' => "Blood Type",
            'type' => 'select_from_array',
            'options' => [
                'A+' => 'A+',
                'A-' => 'A-',
                'B+' => 'B+',
                'B-' => 'B-',
                'O+' => 'O+',
                'O-' => 'AO-',
                'AB+' => 'AB+',
                'AB-' => 'AB-'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ]
        ]);
        $this->crud->addField([
            'name'=>'birthPlace',
            'label'=>'Birth Place',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
           ]
        ]);
        $this->crud->addField([
            'name'=>'citizenShip',
            'label'=>'Citizenship',
            'type' => 'select_from_array',
            'options' => [
                'Filipino' => 'Filipino'
            ],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'citizenShipAcquisition',
            'label'=>'Citizenship Acquisition',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'country',
            'label'=>'Country',
            'type' => 'select_from_array',
            'options' => [
                'Philippines' => 'Philippines'
            ],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'email',
            'label'=>'Email',
            'type'=>'email',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'contactNo',
            'label'=>'Contact No.',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'landlineNo',
            'label'=>'Landline No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'height',
            'label'=>'Height',
            'hint'=>'(in cm)',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name'=>'weight',
            'label'=>'Weight',
            'hint'=>'(in kg)',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name' => 'picName',
            'label' => 'Upload Picture',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'uploads',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        $this->crud->addField([
            'name' => 'halfPicName',
            'label' => 'Upload Half Picture',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'uploads',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'signName',
            'label'=>'Sign Name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'tinNo',
            'label'=>'TIN No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name'=>'bpNo',
            'label'=>'BP No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name'=>'pagibigNo',
            'label'=>'Pagibig No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name'=>'philhealthNo',
            'label'=>'Philhealth No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name'=>'sssNo',
            'label'=>'SSS No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name'=>'residentialAddress',
            'label'=>'Residential Address',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
           ]
        ]);
        $this->crud->addField([
            'name'=>'permanentAddress',
            'label'=>'Permanent Address',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
           ]
        ]);
        $this->crud->addField([
            'name'=>'residentialSitio',
            'label'=>'Residential Sitio',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
           ]
        ]);
        $this->crud->addField([
            'name'=>'Permanent Sitio',
            'label'=>'Permanent Sitio',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
           ]
        ]);
        $this->crud->addField([
            'name'=>'emergencyContactPerson',
            'label'=>'Emergency Contact Person',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
           ]
        ]);
        $this->crud->addField([
            'name'=>'emergencyContactRelationship',
            'label'=>'Emergency Contact Relationship',
            'type' => 'select_from_array',
            'options' => [
                'Mother' => 'Mother',
                'Father' => 'Father',
                'Sister' => 'Sister',
                'Brother' => 'Brother',
                'Relative' => 'Relative'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
           ]
        ]);
        $this->crud->addField([
            'name'=>'emergencyContactAddress1',
            'label'=>'Emergency Contact Address 1',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
           ]
        ]);
        $this->crud->addField([
            'name'=>'emergencyContactAddress2',
            'label'=>'Emergency Contact Address 2',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
           ]
        ]);
        
        $this->crud->addField([
            'name'=>'empPrint',
            'label'=>'EMP Print',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'workStatus',
            'label'=>'Work Status',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'remarks',
            'label'=>'Remarks',
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
           ]
        ]);
        $this->crud->addField([
            'name'=>'encryptCode',
            'label'=>'Encrypted Code',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
        
        $this->crud->addField([
            'name'=>'smallPrint',
            'label'=>'Small Print',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
           ]
        ]);
       
        

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */

        Employee::creating(function($entry) {
            $count = Employee::select(DB::raw('count(*) as count'))->where('employeeId','like',"%".Date('mdY')."%")->first();
            $employeeId = 'EMPID'.Date('mdY').'-'.str_pad(($count->count), 4, "0", STR_PAD_LEFT);

            $entry->employeeId = $employeeId;
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
