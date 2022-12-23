<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TreasuryBusinessRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Http\Request;
use App\Models\BussTaxAssessments;
use Illuminate\Support\Facades\DB;

/**
 * Class TreasuryBusinessCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TreasuryBusinessCrudController extends CrudController
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
        CRUD::setModel(\App\Models\TreasuryBusiness::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/treasury-business');
        CRUD::setEntityNameStrings('treasury business', 'treasury businesses');
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
                    return route('treasury-business.edit',$entry->id);
                },
            ],
        ]);

        $this->crud->addColumn([
            'label'     => 'Assessment Ref. ID',
            'type'      => 'text',
            'name'      => 'business_tax_assessment.refID'
        ]);

        $this->crud->addColumn([
            'label'     => 'Business Ref. ID',
            'type'      => 'text',
            'name'      => 'business_tax_assessment.bussProf.refID'
        ]);

        $this->crud->addColumn([
            'label'     => 'Business Name',
            'type'      => 'text',
            'name'      => 'business_tax_assessment.bussProf.business_name'
        ]);

        $this->crud->addColumn([
            'label'     => 'Business Name',
            'type'      => 'text',
            'name'      => 'business_tax_assessment.bussProf.main_office.ownerAddress',
            'limit' => 255
        ]);

        $this->crud->addColumn([
            'label'     => 'Kind',
            'type'      => 'text',
            'name'      => 'business_tax_assessment.bussType.name',
            'limit' => 255
        ]);

        // $this->crud->addColumn([
        //     'label'     => 'Reference ID',
        //     'type'      => 'text',
        //     'name'      => 'refID',
        //     'wrapper'   => [
        //         'href' => function ($crud, $column, $entry, ) {
        //             return route('treasury-business.edit',$entry->id);
        //         },
        //     ],
        //   ]);

        // $this->crud->column('or_no');
        // $this->crud->column('businessId');
        // $this->crud->column('isActive')->label('Status');
        $this->crud->column('totalSummaryAmount')->label('Assessment Amount');
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
        CRUD::setValidation(TreasuryBusinessRequest::class);

        /*Search Fields*/
        $this->crud->addField([
            'name' => 'searchByReferenceId', 
            'label' => 'Search by Reference ID', 
            'type' => 'text',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([
            'name' => 'searchByName', 
            'label' => 'Search by Name', 
            'type' => 'text',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([
            'name' => 'searchByOwner', 
            'label' => 'Search by Owner', 
            'type' => 'text',
            'fake' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator01x',
            'type'  => 'custom_html',
            'value' => '<br>',
        ]);
        $this->crud->addField([
            'name'  => 'separator02x',
            'type'  => 'custom_html',
            'value' => '',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-10',
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator04x',
            'type'  => 'custom_html',
            'value' => '<a href="javascript:void(0)" id="btnSearch" class="btn btn-primary" data-style="zoom-in" style="width:110px;"><span class="ladda-label"><i class="la la-search"></i> Search</span></a>
                <a href="javascript:void(0)" id="btnClear" class="btn btn-default" data-style="zoom-in" style="width:110px;"><span class="ladda-label"><i class="la la-eraser"></i> Clear</span></a>',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-2',
            ],
        ]);
        $this->crud->addField(
            [
                'name'=>'businessTaxAssessmentId',
                'type'=>'hidden',
            ]
        );
        
        /*Details*/
        $this->crud->addField(
            [
                'name'=>'businessName',
                'label'=>'Business Name',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
                ],
                'tab' => 'Details',
            ]
        );
        /*$this->crud->addField(
            [
                'name'=>'owner',
                'label'=>'Owner',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
                ],
                'tab' => 'Details',
            ]
        );*/
        $this->crud->addField(
            [
                'name'=>'mainOfficeAddress',
                'label'=>'Business Address',
                'type'=>'textarea',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-12'
                ],
                'tab' => 'Details',
            ]
        );
        $this->crud->addField(
            [
                'name'=>'owner',
                'label'=>'Owner',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
                ],
                'tab' => 'Details',
            ]
        );
        $this->crud->addField(
            [
                'name'=>'ownerAddress',
                'label'=>'Address',
                'type'=>'textarea',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-12'
                ],
                'tab' => 'Details',
            ]
        );
        $this->crud->addField([
            'name'  => 'separator02',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Details',
        ]);
        $this->crud->addField([   
            'name'  => 'otherFees',
            'label' => 'Other Fees',
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
            'tab' => 'Details',
        ]);

        $this->crud->addField([
            'name'=>'totalOtherFeesAmount',
            'label'=>'Total Other Fees Amount',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Details',
        ]);

        $this->crud->addField([
            'name'  => 'separator03',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Details',
        ]);

        /*$this->crud->addField([   
            'name'  => 'details',
            'label' => 'Details',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'particulars',
                    'type'    => 'text',
                    'label'   => 'Particulars',
                    'attributes' => [
                        'class' => 'form-control particulars',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-6'],
                ],
                [
                    'name'    => 'previousYear',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency previousYear',
                    ],
                    'label'   => 'Previous Year',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name'    => 'currentYear',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency currentYear',
                    ],
                    'label'   => 'Current Year',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
            ],
            'new_item_label'  => 'New Item', 
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => false,
            'tab' => 'Details',
        ]);*/

        $this->crud->addField([
            'name'  => 'separator5x',
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
            'tab' => 'Details',
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
            'tab' => 'Details',
        ]);

        /*$this->crud->addField([
            'name'  => 'separator04',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Details',
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
            'tab' => 'Details',
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
        Widget::add()->type('script')->content('assets/js/treasury/create-treasury-business-functions.js');
        $this->crud->hasAccessOrFail('create');
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;
        return view('treasury.business.create', $this->data);
    }

    public function edit($id)
    {
        Widget::add()->type('script')->content('assets/js/treasury/edit-treasury-business-functions.js');
        $this->crud->hasAccessOrFail('update');
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntryWithLocale($id);
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id'] = $id;

        return view('treasury.business.edit', $this->data);
    }

    public function applySearchFilters(Request $request){
        $searchByReferenceId = $request->input('searchByReferenceId');
        $searchByName = $request->input('searchByName');
        $searchByOwner = $request->input('searchByOwner');

        $results = [];

        $citizenProfile = BussTaxAssessments::select('buss_tax_assessments.*',
            'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'),
            'business_profiles.refID as businessRefID', 'business_profiles.business_name')
            ->join('business_profiles', 'buss_tax_assessments.business_profiles_id', '=', 'business_profiles.id')
            ->join('citizen_profiles', 'business_profiles.owner_id', '=', 'citizen_profiles.id')
            ->with('bussProf')
            ->with('bussProf.main_office')
            ->with('bussType');

        $nameProfile = BussTaxAssessments::select('buss_tax_assessments.*',
            'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'),
            'business_profiles.refID as businessRefID', 'business_profiles.business_name')
            ->join('business_profiles', 'buss_tax_assessments.business_profiles_id', '=', 'business_profiles.id')
            ->join('name_profiles', 'business_profiles.owner_id', '=', 'name_profiles.id')
            ->with('bussProf')
            ->with('bussProf.main_office')
            ->with('bussType');

        if (!empty($searchByReferenceId)) { 
            $citizenProfile->where('buss_tax_assessments.refID', 'like', '%'.$searchByReferenceId.'%');
            $nameProfile->where('buss_tax_assessments.refID', 'like', '%'.$searchByReferenceId.'%');
        }

        if (!empty($searchByName)) { 
            $citizenProfile->where('business_profiles.business_name', 'like', '%'.$searchByName.'%');
            $nameProfile->where('business_profiles.business_name', 'like', '%'.$searchByName.'%');
        }

        if (!empty($searchByOwner)) { 
            $citizenProfile->where(DB::raw('CONCAT(citizen_profiles.fName," ",citizen_profiles.mName," ",citizen_profiles.lName)'), 'like', '%'.$searchByOwner.'%');
            $nameProfile->where(DB::raw('CONCAT(name_profiles.first_name," ",name_profiles.middle_name," ",name_profiles.last_name)'), 'like', '%'.$searchByOwner.'%');
        }

        $citizenProfiles = $citizenProfile->where('buss_tax_assessments.isActive', '=', 'Y')->orderBy('buss_tax_assessments.refID','ASC')->get();
        $nameProfiles = $nameProfile->where('buss_tax_assessments.isActive', '=', 'Y')->orderBy('buss_tax_assessments.refID','ASC')->get();

        $results = $citizenProfiles->merge($nameProfiles);
        return $results;
    }
}
