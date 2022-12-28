<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests\BusinessProfilesRequest;
use App\Models\BusinessCategory;
use App\Models\BusinessJobCategories;
use App\Models\BusinessProfiles;
use App\Models\BusinessTaxCode;
use App\Models\BusinessTaxFees;
use App\Models\BussTaxAssessments;
use App\Models\CitizenProfile;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Database\Seeders\BusTaxFeesSeeder;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
/**
 * Class BusinessProfilesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BusinessProfilesCrudController extends CrudController {
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    public function __construct() {
        parent::__construct();
        $this->middleware('can:Business > Business Profile', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup() {
        $this
            ->crud
            ->setModel(\App\Models\BusinessProfiles::class);
        $this
            ->crud
            ->setRoute(config('backpack.base.route_prefix') . '/business-profiles');
        $this
            ->crud
            ->setEntityNameStrings('business profiles', 'business profiles');
        $this
            ->crud
            ->setCreateView('business/profiles/crud/create');
        $this
            ->crud
            ->setEditView('business/profiles/crud/edit');
        $this
            ->crud
            ->removeButton('delete');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation() {
        $this
            ->crud
            ->enableExportButtons();
        $this
            ->crud
            ->removeButton('delete');
        $this
            ->crud
            ->removeButton('show');
        $this
            ->crud
            ->removeButton('update');

        $this
            ->crud->addColumn(['label' => 'Reference ID', 'type' => 'text', 'name' => 'refID', 'wrapper' => ['href' => function ($crud, $column, $entry,) {
            return route('business-profiles.edit', $entry->id);
        }
        , ], ]);

        $this
            ->crud
            ->column('business_name')
            ->label('Business Name');
        $this
            ->crud
            ->addColumn(['name' => 'main_office_address', 'label' => 'Address', // Table column heading
        'type' => 'model_function', 'function_name' => 'getFullAddress', ]);
        $this
            ->crud
            ->addColumn(['name' => 'owner_id', 'label' => 'Owner', // Table column heading
        'type' => 'model_function', 'function_name' => 'getOwner', ]);

        $this
            ->crud
            ->column('sec_reg_date');
        $this
            ->crud
            ->column('isActive')
            ->label('Status');

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
    protected function setupCreateOperation() {
        $this
            ->crud
            ->setValidation(BusinessProfilesRequest::class);

        $this
            ->crud
            ->addField(['name' => 'business_name', 'type' => 'text', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-12 mt-3'], 'tab' => 'Details', ]);

        $id = $this
            ->crud
            ->getCurrentEntryId();
        if ($id != false) {
            $data = BusinessProfiles::where('id', $id)->first();
            $owner_id = $data->owner_id;
            $ownerExist = CitizenProfile::where("id", $owner_id)->count();
            if ($ownerExist == 0) {
                $this
                    ->crud
                    ->addField(['label' => 'Owner/Pres/OIC', 'type' => 'business_owner', 'name' => 'owner_id', 'entity' => 'names', 'attribute' => 'full_name', 'data_source' => url('/admin/api/citizen-profile/search-business-owner') , 'minimum_input_length' => 1, 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-12 ', ], 'tab' => 'Details', ]);
            }
            else {
                $this
                    ->crud
                    ->addField(['label' => 'Owner/Pres/OIC', 'type' => 'business_owner', 'name' => 'owner_id', 'entity' => 'owner', 'attribute' => 'full_name', 'data_source' => url('/admin/api/citizen-profile/search-business-owner') , 'minimum_input_length' => 1, 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-12 ', ], 'tab' => 'Details', ]);
            }
        }
        else {
            $this
                ->crud
                ->addField(['label' => 'Owner/Pres/OIC', 'type' => 'business_owner', 'name' => 'owner_id', 'entity' => 'owner', 'attribute' => 'full_name', 'data_source' => url('/admin/api/citizen-profile/search-business-owner') , 'minimum_input_length' => 1, 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-12 ', ], 'tab' => 'Details', ]);
        }

        $this
            ->crud
            ->addField([

        'label' => 'Main Office Business Address', 'type' => 'business_main_office', 'name' => 'main_office_address', 'entity' => 'main_office',

        'attribute' => 'full_name', 'data_source' => url('/admin/api/faas-land/search') , 'minimum_input_length' => 2, 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-12 ', ], 'tab' => 'Details',

        ]);

        $this
            ->crud
            ->addField(['name' => 'property_owner', 'label' => "Property Owner (Y/N)", 'type' => 'select_from_array', 'options' => ['Y' => 'Yes', 'N' => 'No'], 'allows_null' => false, 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-6']]);

        if ($id != false) {
            $data = BusinessProfiles::where('id', $id)->first();
            $owner_id = $data->owner_id;
            $ownerExist = CitizenProfile::where("id", $owner_id)->count();
            if ($ownerExist == 0) {
                $this
                    ->crud
                    ->addField(['label' => 'Lessor Name', 'type' => 'lessor_name', 'name' => 'lessor_name', 'entity' => 'lessor_owner', 'attribute' => 'full_name', 'data_source' => url('/admin/api/citizen-profile/search-business-owner') , 'minimum_input_length' => 1, 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-6 ', ], 'tab' => 'Details', ]);
            }
            else {
                $this
                    ->crud
                    ->addField(['label' => 'Lessor Name', 'type' => 'lessor_name', 'name' => 'lessor_name', 'entity' => 'lessor_owner', 'attribute' => 'full_name', 'data_source' => url('/admin/api/citizen-profile/search-business-owner') , 'minimum_input_length' => 1, 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-6 ', ], 'tab' => 'Details', ]);
            }
        }
        else {
            $this
                ->crud
                ->addField(['label' => 'Lessor Name', 'type' => 'lessor_name', 'name' => 'lessor_name', 'entity' => 'lessor_owner', 'attribute' => 'full_name', 'data_source' => url('/admin/api/citizen-profile/search-business-owner') , 'minimum_input_length' => 1, 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-6 ', ], 'tab' => 'Details', ]);
        }
        $this
            ->crud
            ->addField([ // phone
        'name' => 'tel', // db column for phone
        'label' => 'Telephone Phone', 'type' => 'text', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-3 ', ], 'tab' => 'Details',

        ]);
        $this
            ->crud
            ->addField([ // phone
        'name' => 'mobile', // db column for phone
        'label' => 'Mobile No', 'type' => 'text', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-3 ', ], 'tab' => 'Details',

        ]);
        $this
            ->crud
            ->addField([ // phone
        'name' => 'email', // db column for phone
        'label' => 'Email', 'type' => 'text', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-3 ', ], 'tab' => 'Details',

        ]);
        $this
            ->crud
            ->addField([ // phone
        'name' => 'tin', // db column for phone
        'label' => 'TIN', 'type' => 'text', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-3 ', ], 'tab' => 'Details',

        ]);
        $this
            ->crud->addField(['label' => "Business Type", 'type' => 'select2', 'name' => 'bus_type', 'entity' => 'bus_type', 'model' => "App\Models\BusinessType", 'attribute' => 'name', 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-3'], 'options' => (function ($query) {
            return $query->orderBy('name', 'ASC')
                ->get();
        }) , ]);
        $this
            ->crud
            ->addField(['name' => 'corp_type', 'label' => "Corp. Type(Fil/Foreign)", 'type' => 'select_from_array', 'options' => ['0' => 'Fil', '1' => 'Foreign'], 'allows_null' => true, 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-3']]);
        $this
            ->crud
            ->addField(['name' => 'trade_name_franchise', 'label' => "Trade Name/Franchise", 'type' => 'text', 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-6']]);
        $this
            ->crud
            ->addField([ // CustomHTML
        'name' => 'separator', 'type' => 'custom_html', 'value' => '<div class=""></div>', 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-8 d-none d-md-block  mb-0']]);
        $this
            ->crud
            ->addField([

        'label' => 'Same as head office', 'type' => 'checkbox', 'name' => 'same_as_head_office', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-4 same_head_office text-right order-1 order-sm-0 mb-0', ], 'tab' => 'Details', ]);
        $this
            ->crud->addField(['label' => "Business Activity", 'type' => 'select2', 'name' => 'business_activity_id', 'entity' => 'bus_activity', 'model' => "App\Models\BusinessActivity", 'attribute' => 'name', 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-3'], 'options' => (function ($query) {
            return $query->orderBy('name', 'ASC')
                ->get();
        }) , ]);
        $this
            ->crud
            ->addField(['name' => 'other_buss_type', 'label' => "Other Buss. Type", 'type' => 'text', 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-3']]);

        $this
            ->crud
            ->addField([

        'label' => 'Business Activity Address (Land Profile)', 'type' => 'business_main_office', 'name' => 'buss_activity_address_id', 'entity' => 'bus_act_address',

        'attribute' => 'full_name', 'data_source' => url('/admin/api/faas-land/search') , 'minimum_input_length' => 2, 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-6 buss_act_add position-relative order-2 order-sm-0 ', ], 'tab' => 'Details', ]);
        $this
            ->crud
            ->addField([ // phone
        'name' => 'sec_no', // db column for phone
        'label' => 'Sec No', 'type' => 'text', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-3 order-last', ], 'tab' => 'Details',

        ]);
        $this
            ->crud
            ->addField(['name' => 'sec_reg_date', 'type' => 'date_picker', 'label' => 'Sec Reg Date', 'date_picker_options' => ['todayBtn' => 'linked', 'format' => 'yyyy-mm-dd', 'language' => 'fr', 'endDate' => '0d', 'startDate' => Carbon::now()
            ->subYears(130)
            ->format('Y-m-d') , ], 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-3 order-last']]);
        $this
            ->crud
            ->addField([ // phone
        'name' => 'dti_no', // db column for phone
        'label' => 'DTI No', 'type' => 'text', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-3 order-last', ], 'tab' => 'Details',

        ]);

        $this
            ->crud
            ->addField(['name' => 'dti_reg_date', 'type' => 'date_picker', 'label' => 'DTI Reg Date', 'date_picker_options' => ['todayBtn' => 'linked', 'format' => 'yyyy-mm-dd', 'language' => 'fr', 'endDate' => '0d', 'startDate' => Carbon::now()
            ->subYears(130)
            ->format('Y-m-d') , ], 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-3 order-last']]);
        $this
            ->crud
            ->addField(['name' => 'weight_and_measure', 'label' => "Weight & Measure (Y/N)", 'type' => 'select_from_array', 'options' => ['N' => 'No', 'Y' => 'Yes'], 'allows_null' => false, 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-4 order-last']]);
        $this
            ->crud
            ->addField(['name' => 'unit_of_measurement', 'label' => "UOM(liter/kilogram)", 'type' => 'select_from_array', 'options' => ['L' => 'Liter', 'K' => 'Kilogram'], 'allows_null' => true, 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-4 order-last']]);
        $this
            ->crud
            ->addField(['name' => 'weight_and_measure_value', 'label' => "Weight & Measure Value*", 'type' => 'text', 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-4 order-last']]);

        $this
            ->crud
            ->addField(['name' => 'area', 'label' => "Area (sq.)", 'type' => 'number', 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-4 order-last']]);
        $this
            ->crud
            ->addField([ // CustomHTML
        'name' => 'separator2', 'type' => 'custom_html', 'value' => '<div class=""></div>', 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-8  d-md-block  mb-0 order-last']]);

        $this
            ->crud
            ->addField(['name' => 'tax_incentives', 'label' => "Tax Incentives (Y/N)", 'type' => 'select_from_array', 'options' => ['Y' => 'Yes', 'N' => 'No'], 'allows_null' => false, 'tab' => 'Details', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-6 order-last']]);
        $this
            ->crud
            ->addField([ // Upload
        'name' => 'certificate', 'label' => 'Upload Certificate', 'type' => 'upload', 'upload' => true, 'tab' => 'Details', 'disk' => 'public', 'wrapperAttributes' => ['class' => 'form-group col-12 col-md-6 order-last']]);
        $buscat = BusinessCategory::all();
        $cat = [];
        foreach ($buscat as $busc) {
            $cat += [$busc->id => $busc->name];
        }

        $this
            ->crud
            ->addField([ // repeatable
        'name' => 'line_of_business', 'label' => '', 'type' => 'repeatable', 'tab' => 'Details', 'wrapper' => ['class' => 'form-group col-12 col-md-12 p-0'], 'subfields' => [ // also works as: "fields"
        ['name' => 'particulars', 'type' => 'select', 'entity' => 'businessCategory', 'attribute' => 'name', 'allows_null' => false,

        'wrapper' => ['class' => 'form-group col-12 col-md-8 ']], ['name' => 'capital', 'type' => 'text', 'label' => 'Capital', 'wrapper' => ['class' => 'form-group col-md-4'], ]],

        // optional
        'new_item_label' => 'Add', // customize the text of the button
        'init_rows' => 1, // number of empty rows to be initialized, by default 1
        'min_rows' => 1, // minimum rows allowed, when reached the "delete" buttons will be hidden
        'max_rows' => 10, // maximum rows allowed, when reached the "new item" button will be hidden
        // allow reordering?
        'reorder' => false, // hide up&down arrows next to each row (no reordering)
        ]);

        $this
            ->crud
            ->addField([ // repeatable
        'name' => 'number_of_employees', 'label' => '', 'type' => 'repeatable', 'tab' => 'Details', 'wrapper' => ['class' => 'form-group col-12 col-md-12 p-0 '], 'subfields' => [ // also works as: "fields"
        ['name' => 'sex', 'label' => "Sex", 'type' => 'select_from_array', 'options' => ['1' => 'Male', '0' => 'Female'], 'allows_null' => false,

        'wrapperAttributes' => ['class' => 'form-group col-12 col-md-7']], ['name' => 'number', 'type' => 'number', 'label' => 'No', 'wrapper' => ['class' => 'form-group col-md-5'], ]],

        // optional
        'new_item_label' => 'Add', // customize the text of the button
        'init_rows' => 1, // number of empty rows to be initialized, by default 1
        'min_rows' => 1, // minimum rows allowed, when reached the "delete" buttons will be hidden
        // allow reordering?
        'max_rows' => 2, 'reorder' => false, // hide up&down arrows next to each row (no reordering)
        ]);
        $this
            ->crud
            ->addField([ // repeatable
        'name' => 'vehicles', 'label' => '', 'type' => 'repeatable', 'tab' => 'Details', 'wrapper' => ['class' => 'form-group col-12 col-md-12 p-0 '], 'subfields' => [ // also works as: "fields"
        ['name' => 'type', 'type' => 'select', 'entity' => "vehicleType", 'attribute' => "name", 'wrapper' => ['class' => 'form-group col-12 col-md-8 ']], ['name' => 'number', 'type' => 'number', 'label' => 'No', 'wrapper' => ['class' => 'form-group col-md-4'], ]],

        // optional
        'new_item_label' => 'Add', // customize the text of the button
        'init_rows' => 1, // number of empty rows to be initialized, by default 1
        'min_rows' => 1, // minimum rows allowed, when reached the "delete" buttons will be hidden
        // allow reordering?
        'reorder' => false, // hide up&down arrows next to each row (no reordering)
        ]);

    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */

    protected function setupUpdateOperation() {
        $this->setupCreateOperation();
    }

    public function selectionSearch(Request $request) { // This is the function which I want to call from ajax
        //do something awesome with that post data
        $search_term = $request->input('q');

        if ($search_term) {
            $results = BusinessProfiles::select(DB::raw('id, refID, business_name'))->orWhere('refID', 'like', '%' . $search_term . '%')->orWhere('business_name', 'like', '%' . $search_term . '%')->where('isActive', '=', 'Y')
                ->orderBy('business_name', 'ASC')
                ->get();
        }
        else {
            $results = BusinessProfiles::select(DB::raw('id, refID, business_name'))->where('isActive', '=', 'Y')
                ->orderBy('business_name', 'ASC')
                ->paginate(10);
        }

        return $results;
    }

    public function searchBusinessProfile(Request $request) { // This is the function which I want to call from ajax
        //do something awesome with that post data
        $search_term = $request->input('q');

        if ($search_term) {
            $citizenProfile = BusinessProfiles::select('business_profiles.*', 'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'))->join('citizen_profiles', 'business_profiles.owner_id', '=', 'citizen_profiles.id')
                ->with('owner')
                ->with('main_office')
                ->orWhere('business_profiles.refID', 'like', '%' . $search_term . '%')->orWhere('business_profiles.business_name', 'like', '%' . $search_term . '%');

            $nameProfile = BusinessProfiles::select('business_profiles.*', 'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'))->join('name_profiles', 'business_profiles.owner_id', '=', 'name_profiles.id')
                ->with('names')
                ->with('main_office')
                ->with('businessCategory')
                ->orWhere('business_profiles.refID', 'like', '%' . $search_term . '%')->orWhere('business_profiles.business_name', 'like', '%' . $search_term . '%');

            $citizenProfiles = $citizenProfile->where('business_profiles.isActive', '=', 'Y')
                ->orderBy('business_profiles.refID', 'ASC')
                ->get();
            $nameProfiles = $nameProfile->where('business_profiles.isActive', '=', 'Y')
                ->orderBy('business_profiles.refID', 'ASC')
                ->get();

            $results = $citizenProfiles->merge($nameProfiles);
        }

        return $results;
    }

    public function getDetails(Request $request) {
        $id = $request->input('id');
        $results = [];

        if (!empty($id)) {
            $citizenProfile = BusinessProfiles::select('business_profiles.*', 'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'))->join('citizen_profiles', 'business_profiles.owner_id', '=', 'citizen_profiles.id')
                ->with('owner')
                ->with('main_office')
                ->with('main_office.barangay');

            $nameProfile = BusinessProfiles::select('business_profiles.*', 'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'))->join('name_profiles', 'business_profiles.owner_id', '=', 'name_profiles.id')
                ->with('names')
                ->with('main_office')
                ->with('main_office.barangay');

            $citizenProfiles = $citizenProfile->where('business_profiles.id', '=', $id)->where('business_profiles.isActive', '=', 'Y')
                ->orderBy('business_profiles.refID', 'ASC')
                ->get();
            $nameProfiles = $nameProfile->where('business_profiles.id', '=', $id)->where('business_profiles.isActive', '=', 'Y')
                ->orderBy('business_profiles.refID', 'ASC')
                ->get();

            $results = $citizenProfiles->merge($nameProfiles);
        }

        return $results;
    }
    public function getLineOfBusiness(Request $request) {
        $id = $request->input('id');
        $BusinessProfiles = BusinessProfiles::where("id", $id)->where('isActive', 'y')
            ->get()
            ->first();
        $lineOfBusiness = [];

        $results = [];
        if (!empty($id)) {

            foreach ($BusinessProfiles->line_of_business as $lineOB) {
                array_push($lineOfBusiness, ['capital' => $lineOB['capital'], 'particulars' => BusinessCategory::select(['name', 'id'])->where("id", $lineOB['particulars'])->get() ]);
            }
            $results = ['line_of_business' => $lineOfBusiness, ];
        }

        return $results;
    }
    public function getDetailsV2(Request $request) {
        $id = $request->input('id');
        $lineofbusiness = $request->input('lineofbusiness');
        $results = [];
        if (!empty($id)) {
            $BusinessProfiles = BusinessProfiles::where("id", $id)->where('isActive', 'y')
                ->get()
                ->first();
            $categories = $BusinessProfiles->businessCategory;
            $isRenewal = BussTaxAssessments::where('business_profiles_id', $id)->count() > 0;

            $activeCategory = [];
            foreach ($categories as $cat) {
                if ($cat->count() > 0) {

                    array_push($activeCategory, $cat->first());
                }
            }
            $activeFees = [];
            foreach ($activeCategory as $ac) {
                foreach ($ac->business_tax_fees as $tax) {
                    array_push($activeFees, $tax);
                }
            }

            $grouped = collect($activeFees)->groupBy('business_fees_name');

            $taxFeeCollections = [];
            $totalCollection = [];
            if (collect($grouped)->count() > 0) {
                foreach ($grouped as $d) {

                    $data = collect($d)->sortBy([['business_fees_name', 'desc']])
                        ->first();

                    $range = collect($data)->get('range_box');

                    switch ($data->basis) {
                        case '01':

                            // Capital/Net Profit
                            $amount = 0;
                            $tmp = [];
                            if ($isRenewal) {

                            }
                            else {
                                $lineofbusiness = $BusinessProfiles->line_of_business;
                                if (collect($lineofbusiness)->count() > 0) {
                                    foreach ($lineofbusiness as $lob) {
                                        $capital = $lob['capital'];
                                        if (isset($lob['particulars'])) {
                                            $lob_name = BusinessCategory::where('id', $lob['particulars'])->first()->name;

                                            foreach ($d as $x) {
                                                $amount = $x->amount_value;
                                                $i = collect($x->range_box)
                                                    ->first();
                                                if ($x->type == "02") {
                                                    // if is range
                                                    if ($capital >= ($i['from']) && $capital < ($i['to'])) {
                                                      array_push($tmp, ['categories'=>$lob_name,'name' => $x->business_fees_name, 'amount' => $amount]);
                                                    }
                                                    elseif ($capital >= ($i['from']) && $i['infinite'] == 1) {
                                                        if ($x->computation == '02') {
                                                            $excess = $capital - $i['PAmount'];
                                                            $amount = ($excess * ($i['pp1'] / 100) * ($i['pp2'] / 100));
                                                        }
                                                        array_push($tmp, ['categories'=>$lob_name,'name' => $x->business_fees_name, 'amount' => $amount]);
                                                    }
                                                }
                                            }
                                        }
													
													
                                    }
												
												if (collect($tmp)->count() > 0) {
													 $tmp = collect($tmp)->groupBy('categories')->all();
													$tmp = collect($tmp)->sortBy([['amount', 'desc']])
														 ->all();
														$t = [];
														foreach($tmp as $tp){
															array_push($t, $tp->first());	
														}
								 
													array_push($taxFeeCollections, ['name'=>'bustax','data'=>$t,'amount'=>collect($t)->sum('amount')]);
											  }
                                }

                            }

                        break;
                        case '02':
                            // Business Area
                            $amount = 0;
                            $area = $BusinessProfiles->area;
                            $tmp = [];
                            if (isset($area)) {
                                foreach ($d as $x) {
                                    $amount = $x->amount_value;
                                    $i = collect($x->range_box)
                                        ->first();
                                    if ($area >= ($i['from']) && $area < ($i['to'])) {
                                        array_push($tmp, ['name' => $x->business_fees_name, 'amount' => $amount]);
                                    }
                                    elseif ($area >= ($i['from']) && $i['infinite'] == 1) {

                                        array_push($tmp, ['name' => $x->business_fees_name, 'amount' => $amount]);
                                    }
                                }
                                if (collect($tmp)->count() > 0) {
                                    $tmp = collect($tmp)->sortBy([['amount', 'desc']])
                                        ->first();
													
                                    array_push($taxFeeCollections, $tmp);
                                }
                            }

                        break;
                        case '03':
                            // No of Employee
                            $amount = 0;
                            $totalSubject = collect($BusinessProfiles->number_of_employees)
                                ->sum('number');
                            $amount = $data->amount_value * $totalSubject;

                            array_push($taxFeeCollections, ['name' => $data->business_fees_name, 'amount' => $amount]);
                        break;
                        case '04':
                            // Weight & Measure
                            $amount = 0;
                            $weight_and_measure_value = $BusinessProfiles->weight_and_measure_value;
                            if (isset($weight_and_measure_value)) {

                                $totalSubject = abs($weight_and_measure_value);
                                $amount = $data->amount_value * $totalSubject;
                                array_push($taxFeeCollections, ['name' => $data->business_fees_name, 'amount' => $amount]);

                            }

                        break;
                        case '05':
                            // No & Type of Vehicle
                            $amount = 0;
                            $vehicles = $BusinessProfiles->vehicles;
                            if (collect($vehicles)->count() > 0) {
                                $totalSubject = collect($vehicles)->sum('number');
                                $amount = $data->amount_value * $totalSubject;
                                array_push($taxFeeCollections, ['name' => $data->business_fees_name, 'amount' => $amount]);

                            }
                        break;

                        default:

                    }

            }

        }
        $results = ['taxFeeCollection' => $taxFeeCollections, 'total' => collect($taxFeeCollections)->sum('amount') ];
    }

    return $results;
}

public function getLineOfBusinessesCategories(Request $request) {
    $id = $request->input('id');
    $results = [];

    if (!empty($id)) {
        $results = BusinessCategory::select('business_categories.id as businessCategoryId', 'business_tax_fees.*')->join('business_tax_fees', 'business_categories.id', '=', 'business_tax_fees.business_categories_id')
            ->where('business_tax_fees.business_categories_id', '=', $id)->where('business_tax_fees.isActive', '=', 'Y')
            ->get();
    }

    return $results;
}
}

