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
use App\Models\BusinessProfiles;
use App\Models\NameProfiles;

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
        $this->crud->orderBy('refID','desc');
        $this->crud->addColumn([
            'label'     => 'Reference ID',
            'type'      => 'text',
            'name'      => 'refID',
            'wrapper'   => [
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
            function ($value) {
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
        function ($value) {
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
        $this->crud->addColumn([
            'name'  => 'address',
            'label' => 'Address',
            'type'  => 'model_function',
            'function_name' => 'getAddressWithBaranggay',
         ]);
         $this->crud->addColumn([
            'name'  => 'barangay',
            'label' => 'Barangay',
            'type'  => 'select',
            'entity'    => 'barangay',
            'attribute' => 'name'
        ]);
        $this->crud->column('sex');
        $this->crud->column('isActive')->label('Status');
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
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name'=>'mName',
            'label'=>'Middle Name',
            'allows_null' => true,
            'hint'=>'optional',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name'=>'lName',
            'label'=>'Last Name',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
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
        $this->crud->addField([
            'name'        => 'civilStatus',
            'label'       => "Civil Status",
            'type'        => 'select_from_array',
            'options'     => ['Single' => 'Single', 
                              'Married' => 'Married',
                              'Widowed' => 'Widowed'],
            'allows_null' => false,
            'default'     => '1',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ]
        ]);
        $this->crud->addField([
            'name'  => 'bdate',
            'type'  => 'date_picker',
            'label' => 'Birthdate',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'yyyy-mm-dd',
                'language' => 'fr',
                'endDate' => '0d',
                'startDate' => Carbon::now()->subYears(130)->format('Y-m-d'),
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ]
         ]);
        
        $this->crud->addField([ 
            'name'        => 'sex',
            'label'       => "Sex",
            'type'        => 'select_from_array',
            'options'     => ['1' => 'Male', '0' => 'Female'],
            'allows_null' => false,
           
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name'        => 'brgyID',
            'label'       => "Baranggay",
            'type'        => 'select_from_array',
            'options'     => $brgy,
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ]
        ]);
        $this->crud->addField([
            'name'        => 'purokID',
            'label'       => "Purok",
            'type'        => 'select_from_array',
            'options'     => ['N/A' => 'N/A'],
            'allows_null' => false,
            'default'     => '1',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4'
            ]
        ]);
       
         $this->crud->addField([
            'name'  => 'placeOfOrigin',
            'label' => 'Place of Origin',
            'type'  => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
            ]
        ]);

        $this->crud->addField([
            'name'=>'address',
            'label'=>'Address',
            'type'  => 'textarea',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
           ]
           
        ]);
      
        $this->crud->addField([
            'name'  => 'separator2a',
            'type'  => 'custom_html',
            'value' => '<hr>',
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
                'class' => 'form-group col-12 col-md-3'
            ],
        ]);

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
                "data"=>Street::select('name','id')->where('barangay_id',$req->barangay_id)->get(),
                'selected'=>CitizenProfile::select('purokID')->where('id',$req->id)->first()
            ]);
        }else{
            return response()->json(Street::select('name','id')->where('barangay_id',$req->barangay_id)->get());
        }
        
    }
    public function checkDuplicate(Request $req){
        $input = $req->all();
     
        $count = CitizenProfile::select(DB::raw('count(*) as count'))
        ->where('fName',strtolower($req->fName))
        ->where('lName',strtolower($req->lName))
        ->where('bdate',"{$req->bdate}");

        if(isset($req->mName)){
            $count->where('mName',strtolower($req->mName));
        }
        if(isset($req->suffix)){
            $count->where('suffix',strtolower($req->suffix));
        }
        if(isset($req->id)){
            $count->where('id',"<>",strtolower($req->id));
        }
        return response()->json($count->first());
    }
    
    /**
     * Define what happens when the api - /api/citizen-profile/search-primary-owner - has been called
     * 
     * 
     * @return void
     */
    public function searchPrimaryOwner(Request $request){ // This is the function which I want to call from ajax
        //do something awesome with that post data 

        $search_term = $request->input('q');

        if ($search_term)
        {
            $citizenProfiles = CitizenProfile::select(DB::raw('CONCAT(fName," ",mName," ",lName) as fullname, id, refID, suffix, address, bdate, brgyID, civilStatus, placeOfOrigin, purokID, sex, "CitizenProfile" as ownerType'))
                ->with('barangay', function ($q) use ($search_term) {
                    $q->orWhere('name', 'like', '%'.$search_term.'%');
                })
                ->orWhere('refID', 'like', '%'.$search_term.'%')
                ->orWhere('fName', 'like', '%'.$search_term.'%')
                ->orWhere('mName', 'like', '%'.$search_term.'%')
                ->orWhere('lName', 'like', '%'.$search_term.'%')
                ->orWhere('suffix', 'like', '%'.$search_term.'%')
                ->orWhere('address', 'like', '%'.$search_term.'%')
                ->orWhereDate('bdate', '=', date($search_term))
                ->where('isActive', '=', 'Y') 
                ->orderBy('fullname','ASC')
                ->get();

            $nameProfiles = NameProfiles::select(DB::raw('CONCAT(first_name," ",middle_name," ",last_name) as fullname, id,first_name,middle_name,last_name,id, refID, suffix, address, bdate,  "NameProfile" as ownerType'))
                ->with('municipality', function ($q) use ($search_term) {
                    $q->orWhere('name', 'like', '%'.$search_term.'%');
                })
                ->orWhere('refID', 'like', '%'.$search_term.'%')
                ->orWhere('first_name', 'like', '%'.$search_term.'%')
                ->orWhere('middle_name', 'like', '%'.$search_term.'%')
                ->orWhere('last_name', 'like', '%'.$search_term.'%')
                ->orWhere('suffix', 'like', '%'.$search_term.'%')
                ->orWhere('address', 'like', '%'.$search_term.'%')
                ->orWhereDate('bdate', '=', date($search_term))
                ->where('isActive', '=', 'Y') 
                ->orderBy('fullname','ASC')
                ->get();

            $results = $citizenProfiles->merge($nameProfiles);
        }
        else
        {
            $citizenProfiles = CitizenProfile::select(DB::raw('CONCAT(fName," ",mName," ",lName) as fullname, id, refID, suffix, address, bdate, brgyID, civilStatus, placeOfOrigin, purokID, sex, "CitizenProfile" as ownerType'))
                ->with('barangay')
                ->where('isActive', '=', 'Y')
                ->orderBy('fullname','ASC')->paginate(10);
            
            $nameProfiles = NameProfiles::select(DB::raw('CONCAT(first_name," ",middle_name," ",last_name) as fullname, id,first_name,middle_name,last_name,id, refID, suffix, address, bdate,  "NameProfile" as ownerType'))
                ->with('municipality')
                ->where('isActive', '=', 'Y')    
                ->orderBy('fullname','ASC')->paginate(10);
            
            $results = $citizenProfiles->merge($nameProfiles);
        }

        return $results;
    }

    /**
     * Define what happens when the api - /api/citizen-profile/search-secondary-owners - has been called
     * 
     * 
     * @return void
     */
    public function searchSecondaryOwners(Request $request){ // This is the function which I want to call from ajax
        //do something awesome with that post data 

        $search_term = $request->input('q');

        if ($search_term)
        {
            $citizenProfiles = CitizenProfile::select(DB::raw('CONCAT(fName," ",mName," ",lName) as fullname, id, refID, suffix, address, bdate, brgyID, civilStatus, placeOfOrigin, purokID, sex, "CitizenProfile" as ownerType'))
                ->with('barangay', function ($q) use ($search_term) {
                    $q->orWhere('name', 'like', '%'.$search_term.'%');
                })
                ->orWhere('refID', 'like', '%'.$search_term.'%')
                ->orWhere('fName', 'like', '%'.$search_term.'%')
                ->orWhere('mName', 'like', '%'.$search_term.'%')
                ->orWhere('lName', 'like', '%'.$search_term.'%')
                ->orWhere('suffix', 'like', '%'.$search_term.'%')
                ->orWhere('address', 'like', '%'.$search_term.'%')
                ->orWhereDate('bdate', '=', date($search_term))
                ->where('isActive', '=', 'Y') 
                ->orderBy('fullname','ASC')
                ->get();
        }
        else
        {
            $citizenProfiles = CitizenProfile::select(DB::raw('CONCAT(fName," ",mName," ",lName) as fullname, id, refID, suffix, address, bdate, brgyID, civilStatus, placeOfOrigin, purokID, sex, "CitizenProfile" as ownerType'))
                ->where('isActive', '=', 'Y')
                ->orderBy('fullname','ASC')->paginate(10);
        }

        return $citizenProfiles;
    }

    public function searchBusinessOwner(Request $request){

        $search_term = $request->input('q');

        if ($search_term)
        {
            $citizenProfiles = CitizenProfile::select(DB::raw('CONCAT(fName," ",mName," ",lName) as fullname, id, refID, suffix, address, bdate, brgyID, civilStatus, placeOfOrigin, purokID, sex, "CitizenProfile" as ownerType'))
            ->with('barangay', function ($q) use ($search_term) {
                $q->orWhere('name', 'like', '%'.$search_term.'%');
            })
            ->orWhere('refID', 'like', '%'.$search_term.'%')
            ->orWhere('fName', 'like', '%'.$search_term.'%')
            ->orWhere('mName', 'like', '%'.$search_term.'%')
            ->orWhere('lName', 'like', '%'.$search_term.'%')
            ->orWhere(DB::raw('CONCAT(TRIM(citizen_profiles.fName)," ",
                (IF(citizen_profiles.mName IS NULL OR citizen_profiles.mName = ""  , "",CONCAT(citizen_profiles.mName," "))),
                TRIM(citizen_profiles.lName),
                (IF(citizen_profiles.suffix IS NULL OR citizen_profiles.suffix = ""  , "",CONCAT(" ",TRIM(citizen_profiles.suffix)))))'),'LIKE',"%".strtolower($search_term)."%")
            ->orWhere('suffix', 'like', '%'.$search_term.'%')
            ->orWhere('address', 'like', '%'.$search_term.'%')
            ->orWhereDate('bdate', '=', date($search_term))
            ->where('isActive', '=', 'Y') 
            ->orderBy('fullname','ASC')
            ->get();

            $businessNames = NameProfiles::select(DB::raw('CONCAT(first_name," ",middle_name," ",last_name) as fullname, id,first_name,middle_name,last_name,id, refID, suffix, address, bdate,  "BussNameProfile" as ownerType'))
            ->orWhere('refID', 'like', '%'.$search_term.'%')
            ->orWhere('first_name', 'like', '%'.$search_term.'%')
            ->orWhere('middle_name', 'like', '%'.$search_term.'%')
            ->orWhere('last_name', 'like', '%'.$search_term.'%')
            ->orWhere(DB::raw('CONCAT(TRIM(name_profiles.first_name)," ",
                (IF(name_profiles.middle_name IS NULL OR name_profiles.middle_name = ""  , "",CONCAT(name_profiles.middle_name," "))),
                TRIM(name_profiles.last_name),
                (IF(name_profiles.suffix IS NULL OR name_profiles.suffix = ""  , "",CONCAT(" ",TRIM(name_profiles.suffix)))))'),'LIKE',"%".strtolower($search_term)."%")

            ->orWhere('suffix', 'like', '%'.$search_term.'%')
            ->orWhere('address', 'like', '%'.$search_term.'%')
            ->orWhereDate('bdate', '=', date($search_term))
            ->where('isActive', '=', 'Y') 
            ->orderBy('fullname','ASC')
            ->get();
            $results = $citizenProfiles->merge($businessNames);
            return response()->json($results);
        }



    }

}