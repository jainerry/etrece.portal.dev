<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CitizenProfileRequest;
use App\Models\CitizenProfile;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Date;
use App\Models\Barangay;
use App\Models\Street;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\TransactionLogs;
use \Backpack\CRUD\app\Library\Widget;
/**
 * Class CitizenProfileCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */


class CitizenProfileCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view-citizen-profiles', ['only' => ['index','show']]);
        $this->middleware('can:create-citizen-profiles', ['only' => ['create','store']]);
        $this->middleware('can:edit-citizen-profiles', ['only' => ['edit','update']]);
        $this->middleware('can:delete-citizen-profiles', ['only' => ['destroy']]);
        
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    
    public function setup()
    {
        $this->crud->setModel(\App\Models\CitizenProfile::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/citizen-profile');
        $this->crud->setEntityNameStrings('citizen profile', 'citizen profiles');
        $this->crud->setCreateContentClass('col-md-12 asdasd');
    
        // dd($this->crud);
      
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
            // Select
            'label'     => 'Reference ID',
            'type'      => 'text',
            'name'      => 'refID', // the db column for the foreign key
            'wrapper'   => [
                // 'element' => 'a', // the element will default to "a" so you can skip it here
                'href' => function ($crud, $column, $entry, ) {
                    return route('citizen-profile.edit',$entry->id);
                },
            ],
          ]);
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
            'name'  => 'brgyID',
            'label' => 'Barangay'
          ],
          function() {
            return \App\Models\Barangay::all()->pluck('name', 'id')->toArray();
            },
          function ($value) { // if the filter is active, apply these constraints
            $this->crud->addClause('where', 'brgyID', $value);
          });

        $this->crud->addColumn([
            'name'        => 'fullname',
            'label'       => 'Full Name',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere(DB::raw('CONCAT(TRIM(citizen_profiles.fName)," ",
                (IF(citizen_profiles.mName IS NULL OR citizen_profiles.mName = ""  , "",CONCAT(citizen_profiles.mName," "))),
                TRIM(citizen_profiles.lName),
                (IF(citizen_profiles.suffix IS NULL OR citizen_profiles.suffix = ""  , "",CONCAT(" ",TRIM(citizen_profiles.suffix)))))'),'LIKE',"%".strtolower($searchTerm)."%");
            }

        ]);
      
        $this->crud->column('bdate');
        // $this->crud->addColumn([
        //     'name'=>'brgyID',
        //     'label' => "Barangay",
        //     'type'=>'select',
        //     'entity' => 'barangay',
        //     'attribute' => 'name',
        // ]);
        $this->crud->addColumn([
            // run a function on the CRUD model and show its return value
            'name'  => 'address',
            'label' => 'Address', // Table column heading
            'type'  => 'model_function',
            'function_name' => 'getAddressWithBaranggay', // the method in your Model
            // 'function_parameters' => [$one, $two], // pass one/more parameters to that method
            // 'limit' => 100, // Limit the number of characters shown
            // 'escaped' => false, // echo using {!! !!} instead of {{ }}, in order to render HTML
         ]);
        $this->crud->column('created_at');
        $this->crud->column('isActive');
        // $this->crud->column('placeOfOrigin');
        // $this->crud->column('purokID');
        $this->crud->column('sex');
        // $this->crud->column('updated_at');

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
   
        $this->crud->setValidation(CitizenProfileRequest::class);
        Widget::add([
            'type'     => 'script',
            'name'      => 'custom_script',
            'content'  => '/assets/js/citizenProfile_create.js',
        ]);
        Widget::add([
            'type'     => 'script',
            'name'      => 'getClusters',
            'content'  => '/assets/js/getClusters.js',
        ]);
        $brgys = Barangay::all();
        $brgy = [];
        foreach($brgys as $br){
            $brgy += [$br->id => $br->name];
        }
        $this->crud->addField([
            'name'=>'fName',
            'label'=>'First Name',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-3'
           ]
        ]);
        $this->crud->addField([
            'name'=>'mName',
            'label'=>'Middle Name',
            'allows_null' => true,
            'hint'=>'optional',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-3'
           ]
        ]);
        $this->crud->addField([
            'name'=>'lName',
            'label'=>'Last Name',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-3'
           ]
           
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
        ]);
        $this->crud->addField([   // select_from_array
            'name'        => 'civilStatus',
            'label'       => "Civil Status",
            'type'        => 'select_from_array',
            'options'     => ['Single' => 'Single', 
                              'Married' => 'Married',
                              'Widowed' => 'Widowed'],
            'allows_null' => false,
            'default'     => '1',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
            ]
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);
       
        $this->crud->addField([
            'name'=>'bdate',
            'label'=>'Birthday',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
           ]
           
        ]);
        
        $this->crud->addField([ 
            'name'        => 'sex',
            'label'       => "Sex",
            'type'        => 'select_from_array',
            'options'     => ['1' => 'Male', '0' => 'Female'],
            'allows_null' => false,
           
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
            ]
        ]);

        $this->crud->addField([   // select_from_array
            'name'        => 'brgyID',
            'label'       => "Baranggay",
            'type'        => 'select_from_array',
            'options'     => $brgy,
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
            ]
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);
        $this->crud->addField([   // select_from_array
            'name'        => 'purokID',
            'label'       => "Purok",
            'type'        => 'select_from_array',
            'options'     => ['N/A' => 'N/A'],
            'allows_null' => false,
            'default'     => '1',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
            ]
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);
       
         $this->crud->addField([   // Textarea
            'name'  => 'placeOfOrigin',
            'label' => 'Place of Origin',
            'type'  => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
            ]
        ]);
        $this->crud->addField([
            'name'=>'address',
            'label'=>'Address',
            'type'  => 'textarea',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-8'
           ]
           
        ]);
      
       
        $this->crud->addField([   
            'name'        => 'isActive',
            'label'       => "isActive",
            'type'        => 'select_from_array',
            'options'     => ['y'=>'TRUE','n'=>'FALSE'],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
            ]
        ]);
       
        
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - $this->crud->field('price')->type('number');
         * - $this->crud->addField(['name' => 'price', 'type' => 'number'])); 
         */
        CitizenProfile::creating(function($entry) {
            $count = CitizenProfile::select(DB::raw('count(*) as count'))->where('refID','like',"%".Date('mdY')."%")->first();
            $refId = 'CID'.Date('mdY').'-'.str_pad(($count->count), 4, "0", STR_PAD_LEFT);

            $entry->refID = $refId;
            TransactionLogs::create([
                'transId' =>$refId,
                'category' =>'citizen_profile',
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
        Widget::name('custom_script')->remove();
     
        CitizenProfile::updating(function($entry) {
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'citizen_profile',
                'type' =>'update',
            ]);
        });
       
    }
    public function getCluster(Request $req){
        if($req->selected == true){
            return response()->json([
                "data"=>Street::select('name','id')->where('barangayId',$req->barangay_id)->get(),
                'selected'=>CitizenProfile::select('purokID')->where('id',$req->id)->first()
            ]);
        }else{
            return response()->json(Street::select('name','id')->where('barangayId',$req->barangay_id)->get());
        }
        
    }
    public function checkDuplicate(Request $req){
        $input = $req->all();
     
        $count = CitizenProfile::select(DB::raw('count(*) as count'))
        ->where('fName',strtolower($req->fName))
        ->where('lName',strtolower($req->lName))
        ->where('suffix',strtolower($req->suffix))
        ->where('bdate',"{$req->bdate}")
        ->first();

        return response()->json($count);
    }
    /**
     * Define what happens when the api - /api/citizen-profile/ajaxsearch - has been called
     * 
     * 
     * @return void
     */
    public function ajaxsearch(Request $request){ // This is the function which I want to call from ajax
        //do something awesome with that post data 

        $search_term = $request->input('q');

        if ($search_term)
        {
            $results = CitizenProfile::select(DB::raw('CONCAT(fName," ",mName," ",lName) as fullname, id, refId, suffix, address, bdate, brgyID, civilStatus, placeOfOrigin, purokID, sex'))
                ->orWhereHas('barangay', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhere('refID', 'like', '%'.$search_term.'%')
                ->orWhere('fName', 'like', '%'.$search_term.'%')
                ->orWhere('mName', 'like', '%'.$search_term.'%')
                ->orWhere('lName', 'like', '%'.$search_term.'%')
                ->orWhere('suffix', 'like', '%'.$search_term.'%')
                ->orWhere('address', 'like', '%'.$search_term.'%')
                ->orWhereDate('bdate', '=', date($search_term))
                ->orderBy('fullname','ASC')
                ->get();
        }
        else
        {
            $results = CitizenProfile::orderBy('lName','ASC')->paginate(10);
        }

        return $results;
    }

}