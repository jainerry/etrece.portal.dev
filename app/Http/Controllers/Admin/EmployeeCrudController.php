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
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    
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
        $this->crud->addFilter([
            'type'  => 'date',
            'name'  => 'created_at',
            'label' => 'Created At'
          ],
            false,
          function ($value) { // if the filter is active, apply these constraints
            $this->crud->addClause('whereDate', 'created_at', $value);
          });
        
        $this->crud->addColumn([
            'label'     => 'Reference ID',
            'type'      => 'text',
            'name'      => 'refID',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('employee.edit',$entry->id);
                },
            ]
        ]);
        $this->crud->addColumn([
            'label'     => 'Employee ID',
            'type'      => 'text',
            'name'      => 'employeeId', 
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('employee.edit',$entry->id);
                },
            ],
        ]);
        $this->crud->column('fullname');
        $this->crud->column('birthDate');
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
        $this->crud->addField([
            'name'  => 'birthDate',
            'type'  => 'date_picker',
            'label' => 'Birthdate',
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

        Employee::creating(function($entry) {
            
            $employeeIdCtr = Employee::select(DB::raw('count(*) as count'))->orderBy('created_at', 'desc')->first();
            //EP22-001 (EP)(last two digit of current year)(-)(series)
            $employeeId = 'EP'.substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.str_pad(($employeeIdCtr->count), 3, "0", STR_PAD_LEFT);
            /*
            $request = app(EmployeeRequest::class);
            $appointmentName = Appointment::find($request->input('appointmentId'))->name;
            $appointmentInitial = strtoupper(substr($appointmentName,0,1));
            //22-J001 (last two digit of current year)(-)(initial of appointment)(series)
            $IDNoCtr = Employee::select(DB::raw('count(*) as count'))->orderBy('created_at', 'desc')->first();
            $IDNo = substr(Date('Y'),(strlen(Date('Y'))-2),2).'-'.$appointmentInitial.str_pad(($IDNoCtr->count), 3, "0", STR_PAD_LEFT);
            $entry->IDNo = $IDNo;
            */

            $count = Employee::count();
            $refID = 'EMPLOYEE-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

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
                'category' =>'employee',
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
  

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
   

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
