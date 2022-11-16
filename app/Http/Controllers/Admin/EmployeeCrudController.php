<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeRequest;
use App\Models\Appointment;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Employee;
use App\Models\CitizenProfile;
use Illuminate\Support\Facades\DB;
use App\Models\Barangay;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;
use App\Models\TransactionLogs;
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
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    
    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view-employees', ['only' => ['index','show']]);
        $this->middleware('can:create-employees', ['only' => ['create','store']]);
        $this->middleware('can:edit-employees', ['only' => ['edit','update']]);
        $this->middleware('can:delete-employees', ['only' => ['destroy']]);
    }

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
            'label'     => 'Employee ID',
            'type'      => 'text',
            'name'      => 'employeeId', 
            'wrapper'   => [
                // 'element' => 'a', // the element will default to "a" so you can skip it here
                'href' => function ($crud, $column, $entry, ) {
                    return route('employee.edit',$entry->id);
                },
            ],
          ]);

        // CRUD::column('employeeId');
        CRUD::column('fullname');
        CRUD::column('birthDate');
        // CRUD::addColumn([
        //     'label'=>'Section',
        //     'type'  => 'model_function',
        //     'function_name' => 'getSection',
        //     'searchLogic' => function ($query, $column, $searchTerm) {
        //         $query->orWhereHas('section', function ($q) use ($column, $searchTerm) {
        //             $q->where('name', 'like', '%'.$searchTerm.'%');
        //         })->orWhereHas('position', function ($q) use ($column, $searchTerm) {
        //             $q->where('name', 'like', '%'.$searchTerm.'%');
        //         })
        //         ->orWhere('firstName', 'like', '%'.$searchTerm.'%')
        //         ->orWhere('lastName', 'like', '%'.$searchTerm.'%')
        //         ->orWhereDate('birthDate', '=', date($searchTerm));
        //     }
        // ]);
        // CRUD::addColumn([
        //     'label'=>'Position',
        //     'type'  => 'model_function',
        //     'function_name' => 'getPosition',
        // ]);
        // CRUD::addColumn([
        //     'label'=>'Status',
        //     'type'  => 'model_function',
        //     'function_name' => 'getStatus',
        // ]);

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
        Widget::add([
            'type'     => 'script',
            'name'      => 'custom_script',
            'content'  => '/assets/js/employee_create.js',
        ]);
        $this->crud->addField([
            'name'=>'firstName',
            'label'=>'First Name',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Personal Information',
        ]);
        $this->crud->addField([
            'name'=>'middleName',
            'label'=>'Middle Name',
            'hint'=>'(optional)',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Personal Information',
        ]);
        $this->crud->addField([
            'name'=>'lastName',
            'label'=>'Last Name',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Personal Information',
        ]);
        $this->crud->addField([
            'name'=>'suffix',
            'label'=>'Suffix',
            'type' => 'select_from_array',
            'options' => [
                'JRA' => 'JRA',
                'SR' => 'SR',
                'JR' => 'JR',
                'I' => 'I',
                'II' => 'II',
                'III' => 'III',
                'IV' => 'IV',
                'V' => 'V',
                'VI' => 'VI',
                'VII' => 'VII'
            ],
            'hint'=>'(optional)',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Personal Information',
        ]);
        // $this->crud->addField([
        //     'name'=>'nickName',
        //     'label'=>'Nick Name',
        //     'allows_null' => false,
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'birthDate',
        //     'label'=>'Birth Date',
        //     'type'=>'date',
        //     'allows_null' => false,
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        $this->crud->addField([   // date_picker
            'name'  => 'birthDate',
            'type'  => 'date_picker',
            'label' => 'Birthdate',
            
            // optional:
            'date_picker_options' => [
               'todayBtn' => 'linked',
               'format'   => 'yyyy-mm-dd',
               'language' => 'fr',
               'endDate' => '0d',
               'startDate' => Carbon::now()->subYears(130)->format('Y-m-d')
            ],
            'tab' => 'Personal Information',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
            ]
         ]);
        // $this->crud->addField([
        //     'name'=>'birthPlace',
        //     'label'=>'Place of Birth',
        //     'allows_null' => false,
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-6'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'civilStatus',
        //     'label'=>'Civil Status',
        //     'type' => 'select_from_array',
        //     'options' => [
        //         'Single' => 'Single',
        //         'Married' => 'Married',
        //         'Widow/Widower' => 'Widow/Widower',
        //         'Separated' => 'Separated'
        //     ],
        //     'allows_null' => false,
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'citizenShip',
        //     'label'=>'Citizenship',
        //     'type' => 'select_from_array',
        //     'options' => [
        //         'Filipino' => 'Filipino',
        //         'Dual Citizenship' => 'Dual Citizenship'
        //     ],
        //     'allows_null' => false,
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'citizenShipAcquisition',
        //     'label'=>'Citizenship Acquisition',
        //     'type' => 'select_from_array',
        //     'options' => [
        //         'By Birth' => 'By Birth',
        //         'By Naturalization' => 'By Naturalization'
        //     ],
        //     'allows_null' => false,
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'dualCitizenCountry',
        //     'label'=>'Country',
        //     'hint' => '(for dual citizens)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'sex',
        //     'label'=>'Sex',
        //     'type' => 'select_from_array',
        //     'options' => [
        //         'Male' => 'Male',
        //         'Female' => 'Female'
        //     ],
        //     'allows_null' => false,
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'height',
        //     'label'=>'Height in meter',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'weight',
        //     'label'=>'Weight in kilograms',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([ 
        //     'name' => 'bloodType',
        //     'label' => "Blood Type",
        //     'type' => 'select_from_array',
        //     'options' => [
        //         'A+' => 'A+',
        //         'A-' => 'A-',
        //         'B+' => 'B+',
        //         'B-' => 'B-',
        //         'O+' => 'O+',
        //         'O-' => 'AO-',
        //         'AB+' => 'AB+',
        //         'AB-' => 'AB-'
        //     ],
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'gsisNo',
        //     'label'=>'GSIS No.',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'pagibigNo',
        //     'label'=>'Pagibig No.',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'philhealthNo',
        //     'label'=>'Philhealth No.',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'sssNo',
        //     'label'=>'SSS No.',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'tinNo',
        //     'label'=>'TIN No.',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'telephoneNo',
        //     'label'=>'Telephone No.',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'cellphoneNo',
        //     'label'=>'Cellphone No.',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'email',
        //     'label'=>'Email',
        //     'type'=>'email',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-3'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'remarks',
        //     'label'=>'Remarks',
        //     'type' => 'textarea',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-12'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
        // $this->crud->addField([
        //     'name'=>'appointmentId',
        //     'label'=>'Appointment Status',
        //     'type' => 'select',
        //     'entity' => 'appointment',
        //     'attribute' => 'name',
        //     'allows_null' => false,
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4'
        //     ],
        //     'tab' => 'Personal Information',
        // ]);
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
            'tab' => 'Personal Information',
        ]);
        // $this->crud->addField(
        //     [
        //         'name'=>'positionId',
        //         'label'=>'Position',
        //         'type' => 'select',
        //         'entity' => 'position',
        //         'attribute' => 'name',
        //         'allows_null' => false,
        //         'wrapperAttributes' => [
        //             'class' => 'form-group col-12 col-md-4'
        //         ],
        //         'tab' => 'Personal Information',
        //     ]
        // );
        // $this->crud->addField(
        //     [
        //         'name'=>'officeId',
        //         'label'=>'Office',
        //         'type' => 'select',
        //         'entity' => 'office',
        //         'attribute' => 'name',
        //         'allows_null' => false,
        //         'wrapperAttributes' => [
        //             'class' => 'form-group col-12 col-md-4'
        //         ],
        //         'tab' => 'Personal Information',
        //     ]
        // );
        // $this->crud->addField(
        //     [
        //         'name'=>'sectionId',
        //         'label'=>'Division',
        //         'type' => 'select',
        //         'entity' => 'section',
        //         'attribute' => 'name',
        //         'allows_null' => false,
        //         'wrapperAttributes' => [
        //             'class' => 'form-group col-12 col-md-4'
        //         ],
        //         'tab' => 'Personal Information',
        //     ]
        // );
        // /* Address Details */
        // $this->crud->addField([
        //     'name'=>'residentialAddress',
        //     'label'=>'House/Block/Lot No.',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4'
        //     ],
        //     'tab' => 'Address Details',
        // ]);
        // $this->crud->addField([
        //     'name'=>'residentialStreetId',
        //     'label'=>'Street',
        //     'type' => 'select',
        //     'entity' => 'residentialStreet',
        //     'attribute' => 'name',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4'
        //     ],
        //     'tab' => 'Address Details',
        // ]);
        // $this->crud->addField([
        //     'name'=>'residentialBarangayId',
        //     'label'=>'Barangay',
        //     'type' => 'select',
        //     'entity' => 'residentialBarangay',
        //     'attribute' => 'name',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4'
        //     ],
        //     'tab' => 'Address Details',
        // ]);
        
        // $this->crud->addField([
        //     'name'=>'permanentAddress',
        //     'label'=>'House/Block/Lot No.',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4'
        //     ],
        //     'tab' => 'Address Details',
        // ]);
        // $this->crud->addField([
        //     'name'=>'permanentStreetId',
        //     'label'=>'Street',
        //     'type' => 'select',
        //     'entity' => 'permanentStreet',
        //     'attribute' => 'name',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4'
        //     ],
        //     'tab' => 'Address Details',
        // ]);
        // $this->crud->addField([
        //     'name'=>'permanentBarangayId',
        //     'label'=>'Barangay',
        //     'type' => 'select',
        //     'entity' => 'permanentBarangay',
        //     'attribute' => 'name',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4'
        //     ],
        //     'tab' => 'Address Details',
        // ]);
        // /* Address Details */

        // /* Emergency Contact Details */
        // $this->crud->addField([
        //     'name'=>'emergencyContactPerson',
        //     'label'=>'Contact Person',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4'
        //     ],
        //     'tab' => 'Emergency Contact Details',
        // ]);
        // $this->crud->addField([
        //     'name'=>'emergencyContactNo',
        //     'label'=>'Contact No.',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4'
        //     ],
        //     'tab' => 'Emergency Contact Details',
        // ]);
        // $this->crud->addField([
        //     'name'=>'emergencyContactRelationship',
        //     'label'=>'Relationship',
        //     'type' => 'select_from_array',
        //     'options' => [
        //         'Mother' => 'Mother',
        //         'Father' => 'Father',
        //         'Sister' => 'Sister',
        //         'Brother' => 'Brother',
        //         'Relative' => 'Relative',
        //         'Friend' => 'Friend'
        //     ],
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-4'
        //     ],
        //     'tab' => 'Emergency Contact Details',
        // ]);
        // $this->crud->addField([
        //     'name'=>'emergencyContactAddress1',
        //     'label'=>'Address 1',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-12'
        //     ],
        //     'hint'=>'(optional)',
        //     'tab' => 'Emergency Contact Details',
        // ]);
        // $this->crud->addField([
        //     'name'=>'emergencyContactAddress2',
        //     'label'=>'Address 2',
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-12'
        //     ],
        //     'tab' => 'Emergency Contact Details',
        // ]);
        // /* Emergency Contact Details */

        // /* Uploads */
        // $this->crud->addField([
        //     'label' => "Picture (2x2)",
        //     'name' => "idPicture",
        //     'type' => 'image',
        //     'crop' => true, // set to true to allow cropping, false to disable
        //     'aspect_ratio' => 0, // omit or set to 0 to allow any aspect ratio
        //     // 'disk'      => 's3_bucket', // in case you need to show images from a different disk
        //     // 'prefix'    => 'uploads/images/profile_pictures/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-12'
        //     ],
        //     'tab' => 'Uploads',
        // ]);

        // $this->crud->addField([
        //     'label' => "Picture (half)",
        //     'name' => "halfPicture",
        //     'type' => 'image',
        //     'crop' => true, // set to true to allow cropping, false to disable
        //     'aspect_ratio' => 0, // omit or set to 0 to allow any aspect ratio
        //     // 'disk'      => 's3_bucket', // in case you need to show images from a different disk
        //     // 'prefix'    => 'uploads/images/profile_pictures/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-12'
        //     ],
        //     'tab' => 'Uploads',
        // ]);

        // $this->crud->addField([
        //     'label' => "Signature",
        //     'name' => "signature",
        //     'type' => 'image',
        //     'crop' => true, // set to true to allow cropping, false to disable
        //     'aspect_ratio' => 0, // omit or set to 0 to allow any aspect ratio
        //     // 'disk'      => 's3_bucket', // in case you need to show images from a different disk
        //     // 'prefix'    => 'uploads/images/profile_pictures/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
        //     'hint'=>'(optional)',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-12 col-md-12'
        //     ],
        //     'tab' => 'Uploads',
        // ]);
        /* Uploads */

        
       
        

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */

        Employee::creating(function($entry) {
            
            $employeeIdCtr = Employee::select(DB::raw('count(*) as count'))->orderBy('created_at', 'desc')->first();
            //EP22-001 (EP)(last two digit of current year)(-)(series)
            $employeeId = 'EP'.substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.str_pad(($employeeIdCtr->count), 3, "0", STR_PAD_LEFT);
            // $request = app(EmployeeRequest::class);
            // $appointmentName = Appointment::find($request->input('appointmentId'))->name;
            // $appointmentInitial = strtoupper(substr($appointmentName,0,1));
            $IDNoCtr = Employee::select(DB::raw('count(*) as count'))->orderBy('created_at', 'desc')->first();
            //22-J001 (last two digit of current year)(-)(initial of appointment)(series)
            // $IDNo = substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.$appointmentInitial.str_pad(($IDNoCtr->count), 3, "0", STR_PAD_LEFT);
            // $entry->IDNo = $IDNo;
            TransactionLogs::create([
                'transId' =>$employeeId,
                'category' =>'employee',
                'type' =>'create',
            ]);
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
        Widget::name('custom_script')->remove();
        Employee::updating(function($entry) {
            TransactionLogs::create([
                'transId' =>$entry->employeeId,
                'category' =>'citizen_profile',
                'type' =>'update',
            ]);
        });
       


    }
    public function checkDuplicate(Request $req){
        $input = $req->all();
     
        $count = Employee::select(DB::raw('count(*) as count'))
        ->where('firstName',strtolower($req->firstName))
        ->where('lastName',strtolower($req->lastName))
        ->where('birthDate',"{$req->birthDate}");

        if(isset($req->middleName)){
            $count->where('middleName',strtolower($req->middleName));
        }
        if(isset($req->suffix)){
            $count->where('suffix',strtolower($req->suffix));
        }
        return response()->json($count->first());
    }

    /**
     * Show the form for creating inserting a new row.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');

        $this->crud->hasAccessOrFail('create');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        //return view($this->crud->getCreateView(), $this->data);
        return view('employee.create', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');

        $this->crud->hasAccessOrFail('update');
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        // get the info for that entry

        $this->data['entry'] = $this->crud->getEntryWithLocale($id);
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        //return view($this->crud->getEditView(), $this->data);
        return view('employee.edit', $this->data);
    }

    /**
     * Define what happens when the api - /api/employee/ajaxsearch - has been called
     * 
     * 
     * @return void
     */
    public function ajaxsearch(Request $request){ // This is the function which I want to call from ajax
        //do something awesome with that post data 

        $search_term = $request->input('q');

        if ($search_term)
        {
            $results = Employee::select(DB::raw('CONCAT(firstName," ",middleName," ",lastName) as fullname, id, residentialAddress, permanentAddress, residentialBarangayId, permanentBarangayId, residentialStreetId, permanentStreetId, employeeId, nickName, birthDate, sectionId, positionId, appointmentId, officeId'))
                ->orWhereHas('residentialStreet', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhereHas('permanentStreet', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhereHas('residentialBarangay', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhereHas('permanentBarangay', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhereHas('section', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhereHas('position', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhereHas('appointment', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhereHas('office', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhere('employeeId', 'like', '%'.$search_term.'%')
                ->orWhere('firstName', 'like', '%'.$search_term.'%')
                ->orWhere('middleName', 'like', '%'.$search_term.'%')
                ->orWhere('lastName', 'like', '%'.$search_term.'%')
                ->orWhere('nickName', 'like', '%'.$search_term.'%')
                ->orWhereDate('birthDate', '=', date($search_term))
                ->orderBy('fullname','ASC')
                ->get();
        }
        else
        {
            $results = Employee::orderBy('lastName','ASC')->paginate(10);
        }

        return $results;
    }
}
