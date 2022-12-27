<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TreasuryCtcRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Http\Request;
use App\Models\TreasuryCtc;
use App\Models\CitizenProfile;

/**
 * Class TreasuryCtcCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TreasuryCtcCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:Treasury > CTC', ['only' => ['index','show','create','store','edit','update','destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\TreasuryCtc::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/treasury-ctc');
        CRUD::setEntityNameStrings('treasury ctc', 'treasury ctcs');
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
            'label'     => 'OR No.',
            'type'      => 'text',
            'name'      => 'orNo',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('treasury-ctc.edit',$entry->id);
                },
            ],
        ]);
        $this->crud->column('ctcNumber')->label('CTC No.');

        CRUD::column('model_function')
        ->type('model_function')
        ->label('Name')
        ->function_name('getNameProfile')
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

        $this->crud->addColumn([
            'name'=>'businessProfileId',
            'label' => "Business",
            'type'=>'select',
            'entity' => 'business_profile',
            'attribute' => 'business_name',
            'limit' => 255,
        ]);

        $this->crud->addColumn([
            'name'=>'ctcType',
            'label' => "Type",
            'type'=>'select',
            'entity' => 'ctc_type',
            'attribute' => 'name',
        ]);

        $this->crud->column('totalFeesAmount')->label('Assessment Amount');
        $this->crud->column('created_at')->label('Payment Date');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(TreasuryCtcRequest::class);

        $this->crud->addField(
            [
                'name'=>'ctcNumber',
                'label'=>'CTC Number',
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-3'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'    => 'ctcType',
                'type'    => 'select',
                'label'   => 'Type',
                'model'     => "App\Models\CtcType",
                'attribute' => 'name',
                'attributes' => [
                    'class' => 'form-control ctcType',
                ],
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
        );
        $this->crud->addField([
            'name'=>'dateOfIssue',
            'label'=>'Date Of Issue',
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'yyyy-mm-dd',
                'language' => 'fr',
                'endDate' => '0d',
            ],
            'attributes' => [
                'class' => 'form-control dateOfIssue',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator0',
            'type'  => 'custom_html',
            'value' => '<hr>',
        ]);

        //
        $id = $this->crud->getCurrentEntryId();
        
        if ($id != false) {
            $data = TreasuryCtc::where('id', $id)->first();
            $individualProfileId = $data->individualProfileId;
            
            $ownerExist  = CitizenProfile::where("id", $individualProfileId)->count();
            if ($ownerExist == 0) {
                $this->crud->addField([
                    'label' => 'Name',
                    'type' => 'individual_profile_selection',
                    'name' => 'individualProfileId',
                    'entity' => 'name_profile',
                    'attribute' => 'full_name',
                    'data_source' => url('/admin/api/citizen-profile/search-primary-owner'),
                    'minimum_input_length' => 1,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-8 citizenAndNameProfileWrapper'
                    ],
                ]);
                
            }
            else {
                $this->crud->addField([
                    'label' => 'Name',
                    'type' => 'individual_profile_selection',
                    'name' => 'individualProfileId',
                    'entity' => 'citizen_profile',
                    'attribute' => 'full_name',
                    'data_source' => url('/admin/api/citizen-profile/search-primary-owner'),
                    'minimum_input_length' => 1,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-12 col-md-8 citizenAndNameProfileWrapper'
                    ],
                ]);
            }
        }
        else {
            $this->crud->addField([
                'label' => 'Name',
                'type' => 'individual_profile_selection',
                'name' => 'individualProfileId',
                'entity' => 'citizen_profile',
                'attribute' => 'full_name',
                'data_source' => url('/admin/api/citizen-profile/search-primary-owner'),
                'minimum_input_length' => 1,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-8 citizenAndNameProfileWrapper'
                ],
            ]);
        }

        $this->crud->addField([
            'name'  => 'separator5x',
            'type'  => 'custom_html',
            'value' => '
                <table class="table table-bordered citizenProfileTable" id="citizenProfileTable">
                    <thead>
                        <tr>
                            <th scope="col" width="20%">Details</th>
                            <th scope="col" width="80%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Address:</td>
                            <td id="address"></td>
                        </tr>
                        <tr>
                            <td>Gender:</td>
                            <td id="gender"></td>
                        </tr>
                        <tr>
                            <td>CitizenShip:</td>
                            <td id= "citizenShip"></td>
                        </tr>
                        <tr>
                            <td>Civil Status:</td>
                            <td id="civilStatus"></td>
                        </tr>
                        <tr>
                            <td>TIN:</td>
                            <td id="tin"></td>
                        </tr>
                        <tr>
                            <td>Birth Date:</td>
                            <td id="birthDate"></td>
                        </tr>
                        <tr>
                            <td>Birth Place:</td>
                            <td id= "birthPlace"></td>
                        </tr>
                        <tr>
                            <td>Height:</td>
                            <td id="height"></td>
                        </tr>
                        <tr>
                            <td>Weight:</td>
                            <td id="weight"></td>
                        </tr>
                        <tr>
                            <td>CTC No. / Ref. ID:</td>
                            <td id="refId"></td>
                        </tr>
                    </tbody>
                </table>'
            ,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-8 citizenProfileTableWrapper',
            ],
        ]);

        $this->crud->addField([
            'label' => 'Business Name',
            'type' => 'business_profile_selection',
            'name' => 'businessProfileId',
            'entity' => 'business_profile',
            'attribute' => 'business_name',
            'data_source' => url('/admin/api/business-profile/search-business-profile'),
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-8 businessProfileIdWrapper',
            ],
        ]);

        $this->crud->addField([
            'name'  => 'separator5y',
            'type'  => 'custom_html',
            'value' => '
                <table class="table table-bordered businessProfileTable" id="businessProfileTable">
                    <thead>
                        <tr>
                            <th scope="col" width="20%">Business Details</th>
                            <th scope="col" width="80%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Owner:</td>
                            <td id="owner"></td>
                        </tr>
                        <tr>
                            <td>Business Address:</td>
                            <td id="businessAddress"></td>
                        </tr>
                        <tr>
                            <td>Nature of Business:</td>
                            <td id= "naturOfBusiness"></td>
                        </tr>
                        <tr>
                            <td>CTC No. / Ref. ID:</td>
                            <td id="refId"></td>
                        </tr>
                    </tbody>
                </table>'
            ,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-8 Wrapper',
            ],
        ]);
        
        $this->crud->addField([
            'name'  => 'separator02i',
            'type'  => 'custom_html',
            'value' => '<hr>',
        ]);

        $this->crud->addField(
            [
                'name'=>'employmentStatus',
                'label'=>'Employment Status',
                'type' => 'select_from_array',
                'options' => [
                    'Employed' => 'Employed', 
                    'Unemployed' => 'Unemployed'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4 employmentStatusWrapper'
                ],
            ]
        );
        $this->crud->addField(
            [
                'name'=>'annualIncome',
                'label'=>'Annual Income',
                'attributes' => [
                    'class' => 'form-control text_input_mask_currency'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4 annualIncomeWrapper'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'profession',
                'label'=>'Profession',
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4 professionWrapper'
                ]
            ]
        );
        $this->crud->addField([
            'name'  => 'separator03',
            'type'  => 'custom_html',
            'value' => '<hr>',
        ]);
        $this->crud->addField([   
            'name'  => 'fees',
            'label' => 'Fees',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'particulars',
                    'type'    => 'select',
                    'label'   => 'Particulars',
                    'model'     => "App\Models\BusinessFees",
                    'attribute' => 'name',
                    'attributes' => [
                        'class' => 'form-control particulars',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-6'],
                ],
                [
                    'name'    => 'amount',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency amount',
                    ],
                    'label'   => 'Amount',
                    'wrapper' => ['class' => 'form-group col-md-6'],
                ],
            ],
            'new_item_label'  => 'New Item', 
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => false,
        ]);

        $this->crud->addField([
            'name'=>'totalFeesAmount',
            'label'=>'Total Fees Amount',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
        ]);

        /*$this->crud->addField([
            'name'  => 'separator5xx',
            'type'  => 'custom_html',
            'value' => '<label>Summary</label>
                <table class="table table-bordered summaryTable" id="summaryTable">
                    <thead>
                        <tr>
                            <th scope="col" width="70%">Particulars</th>
                            <th scope="col" width="30%">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>'
            ,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12',
            ],
        ]);

        $this->crud->addField([
            'name'=>'totalSummaryAmount',
            'label'=>'Total Summary Amount',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden'
            ],
        ]);*/

        $this->crud->addField([
            'name'  => 'separator03x',
            'type'  => 'custom_html',
            'value' => '<hr>',
        ]);

        $this->crud->addField(
            [
                'name'=>'remarks',
                'label'=>'Remarks',
                'type'=>'textarea',
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-12'
                ]
            ]
        );
        /*$this->crud->addField([
            'name'  => 'separator04',
            'type'  => 'custom_html',
            'value' => '<hr>',
        ]);*/
        $this->crud->addField([
            'name'=>'isActive',
            'label'=>'Status <span style="color:red;">*</span>',
            'type' => 'select_from_array',
            'options' => [
                1 => 'Active', 
                0 => 'Inactive'
            ],
            'allows_null' => false,
            'default'     => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden'
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
    }

    public function create()
    {
        Widget::add()->type('script')->content('assets/js/treasury/create-treasury-ctc-functions.js');
        $this->crud->hasAccessOrFail('create');
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;
        return view('treasury.ctc.create', $this->data);
    }

    public function edit($id)
    {
        Widget::add()->type('script')->content('assets/js/treasury/edit-treasury-ctc-functions.js');
        $this->crud->hasAccessOrFail('update');
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntryWithLocale($id);
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id'] = $id;

        return view('treasury.ctc.edit', $this->data);
    }
}
