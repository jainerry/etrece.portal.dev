<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FaasLandRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use App\Models\FaasLand;
use App\Models\FaasLandSecondaryOwners;
use Backpack\CRUD\app\Library\Widget;
use App\Models\TransactionLogs;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Http\Request as HttpRequest;
use App\Models\CitizenProfile;

/**
 * Class FaasLandCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FaasLandCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view-faas-lands', ['only' => ['index','show']]);
        $this->middleware('can:create-faas-lands', ['only' => ['create','store']]);
        $this->middleware('can:edit-faas-lands', ['only' => ['edit','update']]);
        $this->middleware('can:delete-faas-lands', ['only' => ['destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
       $this->crud->setModel(\App\Models\FaasLand::class);
       $this->crud->setRoute(config('backpack.base.route_prefix') . '/faas-land');
       $this->crud->setEntityNameStrings('land', 'lands');
        $this->crud->removeButton('delete');

        Widget::add()->type('style')->content('assets/css/faas/styles.css');
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');
        Widget::add()->type('script')->content('assets/js/jquery.inputmask.bundle.min.js');
        Widget::add()->type('script')->content('assets/js/backpack/crud/inputmask.js');
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
                    return route('faas-land.edit',$entry->id);
                },
            ]
        ]);
        CRUD::column('model_function')
        ->type('model_function')
        ->label('Primary Owner')
        ->function_name('getPrimaryOwner')
        ->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhereHas('citizen_profile', function ($q) use ($column, $searchTerm) {
                $q->where('fName', 'like', '%'.$searchTerm.'%');
                $q->orWhere('mName', 'like', '%'.$searchTerm.'%');
                $q->orWhere('lName', 'like', '%'.$searchTerm.'%');
            })
            ->orWhereHas('name_profile', function ($q) use ($column, $searchTerm) {
                $q->where('first_name', 'like', '%'.$searchTerm.'%');
                $q->orWhere('middle_name', 'like', '%'.$searchTerm.'%');
                $q->orWhere('last_name', 'like', '%'.$searchTerm.'%');
            });
        });
        $this->crud->column('ownerAddress')->limit(255)->label('Owner Address');
        $this->crud->addColumn([
            'name'  => 'isActive',
            'label' => 'Status',
            'type'  => 'boolean',
            'options' => [0 => 'Inactive', 1 => 'Active'],
        ]);
        $this->crud->column('created_at')->label('Date Created');
        $this->crud->orderBy('refID','ASC');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(FaasLandRequest::class);
        /*Main Information*/
        $this->crud->addField([
            'name'=>'pin',
            'type'=>'text',
            'label'=>'PIN',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'octTctNo',
            'type'=>'text',
            'label'=>'OCT/TCT No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'survey_no',
            'type'=>'text',
            'label'=>'Survey No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator00',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'  => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'lotNo',
            'type'=>'text',
            'label'=>'Lot No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'blkNo',
            'type'=>'text',
            'label'=>'Block No.',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'totalArea',
            'type'=>'text',
            'label'=>'Area',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'attributes' => [
                'class' => 'form-control text_input_mask_currency area',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator0001',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'  => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'noOfStreet',
            'label'=>'No. of Street',
            'type'=>'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'barangayId',
            'label'=>'Barangay/District',
            'type'=>'select',
            'entity' => 'barangay',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'cityId_fake',
            'label' => "Municipality",
            'type'=>'text',
            'value' => 'Trece Martires City',
            'fake' => true,
            'attributes' => [
                'readonly' => 'readonly',
            ], 
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'provinceId_fake',
            'label' => "Province",
            'type'=>'text',
            'value' => 'Cavite',
            'fake' => true,
            'attributes' => [
                'readonly' => 'readonly',
            ], 
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'label' => "Barangay/District",
            'type'=>'select',
            'name'=>'barangay_code',
            'entity' => 'barangay',
            'attribute' => 'code',
            'attributes' => [
                'class' => 'form-control',
                'readonly' => 'readonly',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden',
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator000',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'  => 'Main Information',
        ]);
        /*Property Boundaries*/
        $this->crud->addField([
            'name'=>'propertyBoundaryNorth',
            'label'=>'North',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'propertyBoundaryEast',
            'label'=>'East',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'propertyBoundarySouth',
            'label'=>'South',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'propertyBoundaryWest',
            'label'=>'West',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'label' => "Land Sketch",
            'name' => "landSketch",
            'type' => 'image',
            'crop' => true,
            'aspect_ratio' => 0,
            'hint'=>'(Not necessary drawn to scale)',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator0',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab'  => 'Main Information',
        ]);
        $id = $this->crud->getCurrentEntryId();
        if ($id != false) {
            $data = FaasLand::where('id', $id)->first();
            $primaryOwnerId = $data->primaryOwnerId;
            $ownerExist  = CitizenProfile::where("id", $primaryOwnerId)->count();
            if ($ownerExist == 0) {
                $this->crud->addField([
                    'label' => 'Primary Owner <span style="color:red;">*</span>',
                    'type' => 'primary_owner_union',
                    'name' => 'primaryOwnerId',
                    'entity' => 'name_profile',
                    'attribute' => 'full_name',
                    'data_source' => url('/admin/api/citizen-profile/search-primary-owner'),
                    'minimum_input_length' => 1,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-6 primaryOwnerId_select'
                    ],
                    'tab' => 'Main Information',
                ]);
            }
            else {
                $this->crud->addField([
                    'label' => 'Primary Owner <span style="color:red;">*</span>',
                    'type' => 'primary_owner_union',
                    'name' => 'primaryOwnerId',
                    'entity' => 'citizen_profile',
                    'attribute' => 'full_name',
                    'data_source' => url('/admin/api/citizen-profile/search-primary-owner'),
                    'minimum_input_length' => 1,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-6 primaryOwnerId_select'
                    ],
                    'tab' => 'Main Information',
                ]);
            }
        }
        else {
            $this->crud->addField([
                'label' => 'Primary Owner <span style="color:red;">*</span>',
                'type' => 'primary_owner_union',
                'name' => 'primaryOwnerId',
                'entity' => 'citizen_profile',
                'attribute' => 'full_name',
                'data_source' => url('/admin/api/citizen-profile/search-primary-owner'),
                'minimum_input_length' => 1,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6 primaryOwnerId_select'
                ],
                'tab' => 'Main Information',
            ]);
        }
        $this->crud->addField([
            'name' => 'land_owner',
            'label' => 'Secondary Owner/s',
            'type' => 'secondary_owner',
            'entity' => 'land_owner',
            'data_source' => url('/admin/api/citizen-profile/search-secondary-owners'),
            'attribute' => 'full_name',
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 land_owner_select'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'secondaryOwnersText',
            'label'=>'Secondary Owner/s',
            'type'=>'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 secondaryOwnersText hidden'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerAddress',
            'label'=>'Address',
            'type'=>'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerTelephoneNo',
            'label'=>'Telephone No.',
            'type'=>'text',
            'attributes' => [
                'class' => 'form-control',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'ownerTinNo',
            'label'=>'TIN No.',
            'type'=>'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_tin',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator1',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administrator',
            'label'=>'Administrator/Occupant',
            'type'=>'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorAddress',
            'label'=>'Address',
            'type'=>'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorTelephoneNo',
            'label'=>'Telephone No.',
            'type'=>'text',
            'attributes' => [
                'class' => 'form-control',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'administratorTinNo',
            'label'=>'TIN No.',
            'type'=>'text',
            'attributes' => [
                'class' => 'form-control text_input_mask_tin',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'  => 'separator1a',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Main Information',
        ]);
        $this->crud->addField([
            'name'=>'isActive',
            'label'=>'Status',
            'type' => 'select_from_array',
            'options' => [
                1 => 'Active', 
                0 => 'Inactive'
            ],
            'allows_null' => false,
            'default'     => 'Y',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Main Information',
        ]);

        FaasLand::creating(function($entry) {
            $count = FaasLand::count();
            $refID = 'LAND-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $entry->cityId = 'db3510e6-3add-4d81-8809-effafbbaa6fd';
            $entry->provinceId = 'eb9e8c56-957b-4084-b5ae-904054d2a1b3';

            $request = app(FaasLandRequest::class);

            TransactionLogs::create([
                'transId' =>$refID,
                'category' =>'faas_land',
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

        FaasLand::updating(function($entry) {
          
            TransactionLogs::create([
                'transId' =>$entry->refID,
                'category' =>'faas_land',
                'type' =>'update',
            ]);
        });
    }

    public function ajaxsearch(Request $request){
        $searchTxt = $request->q;
        $searchQuery = FaasLand::select('faas_lands.*')
        ->where('faas_lands.refID','like','%'.$searchTxt.'%')
        ->leftJoin('municipalities','municipalities.id','=','faas_lands.cityId')
        ->leftJoin('citizen_profiles','citizen_profiles.id','=','faas_lands.primaryOwnerId')
        ->orWhere('municipalities.name','like','%'.$searchTxt.'%')
        ->orWhere('faas_lands.ownerAddress','like','%'.$searchTxt.'%')
        ->orWhere(DB::raw('CONCAT(TRIM(citizen_profiles.fName)," ",
        (IF(citizen_profiles.mName IS NULL OR citizen_profiles.mName = ""  , "",CONCAT(citizen_profiles.mName," "))),
        TRIM(citizen_profiles.lName),
        (IF(citizen_profiles.suffix IS NULL OR citizen_profiles.suffix = ""  , "",CONCAT(" ",TRIM(citizen_profiles.suffix)))))'),'LIKE',"%".strtolower($searchTxt)."%")
        ->orWhereHas('land_owner', function( $query) use($searchTxt){
            return  $query->where(DB::raw('CONCAT(TRIM(citizen_profiles.fName)," ",
            (IF(citizen_profiles.mName IS NULL OR citizen_profiles.mName = ""  , "",CONCAT(citizen_profiles.mName," "))),
            TRIM(citizen_profiles.lName),
            (IF(citizen_profiles.suffix IS NULL OR citizen_profiles.suffix = ""  , "",CONCAT(" ",TRIM(citizen_profiles.suffix)))))'),'like',"%".strtolower($searchTxt)."%");
            
        })
        ->orWhereHas('barangay', function( $query) use($searchTxt){
            return  $query->where('name','like',"%".strtolower($searchTxt)."%")
            ->orWhere('refID','like',"%".strtolower($searchTxt)."%");
        })
        
        ->with('citizen_profile')
        ->with('municipality')
        ->with('barangay')
        ->with('land_owner')->get();

    


        return $searchQuery;

    }

    public function getDetails(Request $request){
        $id = $request->input('id');
        $results = [];
        if (!empty($id))
        {
            $citizenProfiles = FaasLand::select('faas_lands.*', 'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'))
            ->join('citizen_profiles', 'faas_lands.primaryOwnerId', '=', 'citizen_profiles.id')
            ->with('citizen_profile')
            ->with('barangay')
            ->with('land_owner')
            ->where('faas_lands.isActive', '=', '1')
            ->where('faas_lands.id', '=', $id)
            ->get();

            $nameProfiles = FaasLand::select('faas_lands.*', 'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'))
            ->join('name_profiles', 'faas_lands.primaryOwnerId', '=', 'name_profiles.id')
            ->with('name_profile')
            ->with('barangay')
            ->with('land_owner')
            ->where('faas_lands.isActive', '=', '1')
            ->where('faas_lands.id', '=', $id)
            ->get();

            $results = $citizenProfiles->merge($nameProfiles);
        }

        return $results;
    }

    public function getSecondaryOwners(Request $request){
        $land_profile_id = $request->input('land_profile_id');
        $results = [];
        if (!empty($land_profile_id))
        {
            $results = DB::table('faas_land_secondary_owners')
            ->join('citizen_profiles', 'faas_land_secondary_owners.citizen_profile_id', '=', 'citizen_profiles.id')
            ->select('faas_land_secondary_owners.*', 'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address')
            ->where('faas_land_secondary_owners.land_profile_id', '=', $land_profile_id)
            ->get();
        }

        return $results;
    }

    public function searchLandProfile(Request $request){ // This is the function which I want to call from ajax
        //do something awesome with that post data 

        $search_term = $request->input('q');

        if ($search_term)
        {
            $citizenProfiles = FaasLand::select(DB::raw('id, refID, pin, octTctNo, survey_no, lotNo, blkNo, primaryOwnerId, noOfStreet, barangayId, cityId, provinceId, ownerAddress, "CitizenProfile" as ownerType'))   
                ->orWhereHas('barangay', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhereHas('municipality', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhereHas('province', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhereHas('citizen_profile', function ($q) use ($search_term) {
                    $q->where(DB::raw('CONCAT(fName," ",mName," ",lName)'), 'like', '%'.$search_term.'%');
                })
                ->with('barangay')
                ->with('municipality')
                ->with('province')
                ->with('citizen_profile') 
                ->orWhere('refID', 'like', '%'.$search_term.'%')
                ->orWhere('pin', 'like', '%'.$search_term.'%')
                ->orWhere('octTctNo', 'like', '%'.$search_term.'%')
                ->orWhere('survey_no', 'like', '%'.$search_term.'%')
                // ->orWhere('lotNo', 'like', '%'.$search_term.'%')
                // ->orWhere('blkNo', 'like', '%'.$search_term.'%')
                ->orWhere('noOfStreet', 'like', '%'.$search_term.'%')
                ->where('isActive', '=', '1') 
                ->orderBy('refID','ASC')
                ->get();

            $nameProfiles = FaasLand::select(DB::raw('id, refID, pin, octTctNo, survey_no, lotNo, blkNo, primaryOwnerId, noOfStreet, barangayId, cityId, provinceId, ownerAddress, "NameProfile" as ownerType'))
                ->orWhereHas('barangay', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhereHas('municipality', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhereHas('province', function ($q) use ($search_term) {
                    $q->where('name', 'like', '%'.$search_term.'%');
                })
                ->orWhereHas('name_profile', function ($q) use ($search_term) {
                    $q->where(DB::raw('CONCAT(first_name," ",middle_name," ",last_name)'), 'like', '%'.$search_term.'%');
                })
                ->with('barangay')
                ->with('municipality')
                ->with('province')
                ->with('name_profile')
                ->orWhere('refID', 'like', '%'.$search_term.'%')
                ->orWhere('pin', 'like', '%'.$search_term.'%')
                ->orWhere('octTctNo', 'like', '%'.$search_term.'%')
                ->orWhere('survey_no', 'like', '%'.$search_term.'%')
                // ->orWhere('lotNo', 'like', '%'.$search_term.'%')
                // ->orWhere('blkNo', 'like', '%'.$search_term.'%')
                ->orWhere('noOfStreet', 'like', '%'.$search_term.'%')
                ->where('isActive', '=', '1') 
                ->orderBy('refID','ASC')
                ->get();

            $results = $citizenProfiles->merge($nameProfiles);
        }
        else
        {
            $citizenProfiles = FaasLand::select(DB::raw('id, refID, octTctNo, survey_no, lotNo, blkNo, primaryOwnerId, noOfStreet, barangayId, cityId, provinceId, "CitizenProfile" as ownerType'))
                ->with('barangay')
                ->with('municipality')
                ->with('province')
                ->with('citizen_profile')   
                ->where('isActive', '=', '1')
                ->orderBy('refID','ASC')->paginate(5);

            $nameProfiles = FaasLand::select(DB::raw('id, refID, octTctNo, survey_no, lotNo, blkNo, primaryOwnerId, noOfStreet, barangayId, cityId, provinceId, "NameProfile" as ownerType'))
                ->with('barangay')
                ->with('municipality')
                ->with('province')
                ->with('name_profile')    
                ->where('isActive', '=', '1')
                ->orderBy('refID','ASC')->paginate(5);

            $results = $citizenProfiles->merge($nameProfiles);
        }

        return $results;
    }
    
}
