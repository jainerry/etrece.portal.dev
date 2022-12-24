<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TreasuryRptRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Http\Request;
use App\Models\BussTaxAssessments;
use Illuminate\Support\Facades\DB;
use App\Models\RptLands;
use App\Models\RptBuildings;
use App\Models\RptMachineries;

/**
 * Class TreasuryRptCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TreasuryRptCrudController extends CrudController
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
        CRUD::setModel(\App\Models\TreasuryRpt::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/treasury-rpt');
        CRUD::setEntityNameStrings('treasury rpt', 'treasury rpts');
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
                    return route('treasury-rpt.edit',$entry->id);
                },
            ],
        ]);

        $this->crud->addColumn([
            'type' => 'model_function',
            'label' => 'TD No.',
            'function_name' => 'getRPTTDNo',
        ]);

        $this->crud->addColumn([
            'type' => 'model_function',
            'label' => 'Primary Owner',
            'function_name' => 'getPrimaryOwner',
        ]);

        $this->crud->addColumn([
            'type' => 'model_function',
            'label' => 'Primary Address',
            'function_name' => 'getAddress',
            'limit' => 255
        ]);

        $this->crud->column('rptType')->label('Classification');
        $this->crud->column('totalSummaryAmount')->label('Assessment Amount');
        //$this->crud->column('isActive')->label('Status');
        //$this->crud->column('paymentDate')->label('Payment Date');
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
        CRUD::setValidation(TreasuryRptRequest::class);

        /*Search Fields*/
        $this->crud->addField(
            [
                'name'=>'searchByType',
                'label'=>'Search by Type',
                'type' => 'select_from_array',
                'fake' => true,
                'options' => [
                    'Land' => 'Land',
                    'Building' => 'Building', 
                    'Machinery' => 'Machinery'
                ],
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-3'
                ],
            ]
        );
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
            'name' => 'searchByTDNo', 
            'label' => 'Search by TD No.', 
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
                'name'=>'rptId',
                'type'=>'hidden',
            ]
        );

        /*Details*/
        $this->crud->addField(
            [
                'name'=>'rptType',
                'label'=>'Type',
                'type' => 'select_from_array',
                'options' => [
                    'Land' => 'Land',
                    'Building' => 'Building', 
                    'Machinery' => 'Machinery'
                ],
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-3 hidden'
                ],
                'tab' => 'Details',
            ]
        );
        $this->crud->addField(
            [
                'name'=>'TDNo',
                'label'=>'TD No.',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-3'
                ],
                'tab' => 'Details',
            ]
        );
        /*$this->crud->addField(
            [
                'name'=>'ARPNo',
                'label'=>'ARP No.',
                'fake'=>true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-3'
                ],
                'tab' => 'Details',
            ]
        );*/
        $this->crud->addField([
            'name'  => 'separator01',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Details',
        ]);
        $this->crud->addField(
            [
                'name'=>'primaryOwner',
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
        /*$this->crud->addField(
            [
                'name'=>'administrator',
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
                'name'=>'administratorAddress',
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
        );*/
        $this->crud->addField(
            [
                'name'=>'assessedValue',
                'label'=>'Assessed Value',
                'fake'=>true,
                'attributes' => [
                    'class' => 'form-control text_input_mask_currency',
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
                'name'=>'dateAssessed',
                'label'=>'Date Assessed',
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
                'name'=>'area',
                'label'=>'Area',
                'fake'=>true,
                'attributes' => [
                    'class' => 'form-control text_input_mask_currency',
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
                'name'=>'lotNo',
                'label'=>'Lot Number',
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
                'name'=>'title',
                'label'=>'Title',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6 hidden'
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
        $this->crud->addField(
            [
                'name'=>'year',
                'label'=>'Year',
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-3'
                ],
                'tab' => 'Details',
            ]
        );
        $this->crud->addField([
            'name'=>'periodCovered',
            'label'=>'Period Covered',
            'type' => 'select_from_array',
            'options' => [
                'Quarterly' => 'Quarterly', 
                'Semi-Annually' => 'Semi-Annually',
                'Annually' => 'Annually',
            ],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Details',
        ]);
        
        /*$this->crud->addField([
            'name'  => 'separator02a',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Details',
        ]);*/

        /*$this->crud->addField([   
            'name'  => 'otherFees',
            'label' => 'Other Fees',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'particulars',
                    'type'    => 'text',
                    'label'   => 'Particulars',
                    'attributes' => [
                        'class' => 'form-control particulars',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-9'],
                ],
                [
                    'name'    => 'amount',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency amount',
                    ],
                    'label'   => 'Amount',
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

        /*$this->crud->addField([
            'name'  => 'separator03',
            'type'  => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Details',
        ]);
        $this->crud->addField([   
            'name'  => 'summary',
            'label' => 'Summary',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'particulars',
                    'type'    => 'text',
                    'label'   => 'Particulars',
                    'attributes' => [
                        'class' => 'form-control particulars',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-8'],
                ],
                [
                    'name'    => 'amount',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency amount',
                    ],
                    'label'   => 'Amount',
                    'wrapper' => ['class' => 'form-group col-md-4'],
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
            'name'=>'basic_amount',
            'label'=>'Basic',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden'
            ],
            'tab' => 'Details',
        ]);
        $this->crud->addField([
            'name'=>'basicPenalty_amount',
            'label'=>'Penalty',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden'
            ],
            'tab' => 'Details',
        ]);
        $this->crud->addField([
            'name'=>'basicDiscount_amount',
            'label'=>'Discount',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden'
            ],
            'tab' => 'Details',
        ]);
        $this->crud->addField([
            'name'=>'totalBasic_amount',
            'label'=>'Total Basic',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden'
            ],
            'tab' => 'Details',
        ]);
        $this->crud->addField([
            'name'=>'sef_amount',
            'label'=>'SEF',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden'
            ],
            'tab' => 'Details',
        ]);
        $this->crud->addField([
            'name'=>'sefPenalty_amount',
            'label'=>'Penalty',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden'
            ],
            'tab' => 'Details',
        ]);
        $this->crud->addField([
            'name'=>'sefDiscount_amount',
            'label'=>'Discount',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden'
            ],
            'tab' => 'Details',
        ]);
        $this->crud->addField([
            'name'=>'totalSef_amount',
            'label'=>'Total SEF',
            'attributes' => [
                'class' => 'form-control text_input_mask_currency',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 hidden'
            ],
            'tab' => 'Details',
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
                        <tr>
                            <td>Basic</td>
                            <td id="basic_amount"></td>
                        </tr>
                        <tr>
                            <td>Penalty</td>
                            <td id="basicPenalty_amount"></td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td id= "basicDiscount_amount"></td>
                        </tr>
                        <tr style="background-color: #fafafa; font-weight: 600;">
                            <td>Total Basic</td>
                            <td id="totalBasic_amount"></td>
                        </tr>
                        <tr>
                            <td>SEF</td>
                            <td id="sef_amount"></td>
                        </tr>
                        <tr>
                            <td>Penalty</td>
                            <td id="sefPenalty_amount"></td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td id= "sefDiscount_amount"></td>
                        </tr>
                        <tr style="background-color: #fafafa; font-weight: 600;">
                            <td>Total SEF</td>
                            <td id="totalSef_amount"></td>
                        </tr>
                        <tr style="background-color: #fafafa; font-weight: 600; font-size: 20px; text-transform: uppercase;">
                            <td>Total</td>
                            <td id="totalSummaryAmount"></td>
                        </tr>
                    </tbody>
                </table>'
            ,
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12',
            ],
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
        Widget::add()->type('script')->content('assets/js/treasury/create-treasury-rpt-functions.js');
        $this->crud->hasAccessOrFail('create');
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;
        return view('treasury.rpt.create', $this->data);
    }

    public function edit($id)
    {
        Widget::add()->type('script')->content('assets/js/treasury/edit-treasury-rpt-functions.js');
        $this->crud->hasAccessOrFail('update');
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntryWithLocale($id);
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id'] = $id;

        return view('treasury.rpt.edit', $this->data);
    }

    public function applySearchFilters(Request $request){
        $searchByType = $request->input('searchByType');
        $searchByReferenceId = $request->input('searchByReferenceId');
        $searchByTDNo = $request->input('searchByTDNo');
        $searchByOwner = $request->input('searchByOwner');

        $results = [];

        if($searchByType === 'Land') {
            $citizenProfile = RptLands::select('rpt_lands.id', 'rpt_lands.refID', 'rpt_lands.faasId', 'rpt_lands.isActive', 'rpt_lands.TDNo',
                'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'),
                'faas_lands.refID as faasRefId', 'faas_lands.primaryOwnerId', 'faas_lands.ownerAddress')
                ->join('faas_lands', 'rpt_lands.faasId', '=', 'faas_lands.id')
                ->join('citizen_profiles', 'faas_lands.primaryOwnerId', '=', 'citizen_profiles.id')
                ->with('citizen_profile');

            $nameProfile = RptLands::select('rpt_lands.id', 'rpt_lands.refID', 'rpt_lands.faasId', 'rpt_lands.isActive', 'rpt_lands.TDNo',
                'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'),
                'faas_lands.refID as faasRefId', 'faas_lands.primaryOwnerId', 'faas_lands.ownerAddress')
                ->join('faas_lands', 'rpt_lands.faasId', '=', 'faas_lands.id')
                ->join('name_profiles', 'faas_lands.primaryOwnerId', '=', 'name_profiles.id')
                ->with('name_profile');

            if (!empty($searchByReferenceId)) { 
                $citizenProfile->where('rpt_lands.refID', 'like', '%'.$searchByReferenceId.'%');
                $nameProfile->where('rpt_lands.refID', 'like', '%'.$searchByReferenceId.'%');
            }
    
            if (!empty($searchByTDNo)) { 
                $citizenProfile->where('rpt_lands.TDNo', 'like', '%'.$searchByTDNo.'%');
                $nameProfile->where('rpt_lands.TDNo', 'like', '%'.$searchByTDNo.'%');
            }
    
            if (!empty($searchByOwner)) { 
                $citizenProfile->where(DB::raw('CONCAT(citizen_profiles.fName," ",citizen_profiles.mName," ",citizen_profiles.lName)'), 'like', '%'.$searchByOwner.'%');
                $nameProfile->where(DB::raw('CONCAT(name_profiles.first_name," ",name_profiles.middle_name," ",name_profiles.last_name)'), 'like', '%'.$searchByOwner.'%');
            }
    
            $citizenProfiles = $citizenProfile->where('rpt_lands.isActive', '=', '1')->orderBy('rpt_lands.refID','ASC')->get();
            $nameProfiles = $nameProfile->where('rpt_lands.isActive', '=', '1')->orderBy('rpt_lands.refID','ASC')->get();
            $results = $citizenProfiles->merge($nameProfiles);
        }
        else if($searchByType === 'Building') {
            $citizenProfile = RptBuildings::select('rpt_buildings.id', 'rpt_buildings.refID', 'rpt_buildings.faasId', 'rpt_buildings.isActive', 'rpt_buildings.TDNo',
                'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'),
                'faas_building_profiles.refID as faasRefId', 'faas_building_profiles.primary_owner', 'faas_building_profiles.ownerAddress')
                ->join('faas_building_profiles', 'rpt_buildings.faasId', '=', 'faas_building_profiles.id')
                ->join('citizen_profiles', 'faas_building_profiles.primary_owner', '=', 'citizen_profiles.id')
                ->with('citizen_profile');

            $nameProfile = RptBuildings::select('rpt_buildings.id', 'rpt_buildings.refID', 'rpt_buildings.faasId', 'rpt_buildings.isActive', 'rpt_buildings.TDNo',
                'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'),
                'faas_building_profiles.refID as faasRefId', 'faas_building_profiles.primary_owner', 'faas_building_profiles.ownerAddress')
                ->join('faas_building_profiles', 'rpt_buildings.faasId', '=', 'faas_building_profiles.id')
                ->join('name_profiles', 'faas_building_profiles.primary_owner', '=', 'name_profiles.id')
                ->with('name_profile');

            if (!empty($searchByReferenceId)) { 
                $citizenProfile->where('rpt_buildings.refID', 'like', '%'.$searchByReferenceId.'%');
                $nameProfile->where('rpt_buildings.refID', 'like', '%'.$searchByReferenceId.'%');
            }
    
            if (!empty($searchByTDNo)) { 
                $citizenProfile->where('rpt_buildings.TDNo', 'like', '%'.$searchByTDNo.'%');
                $nameProfile->where('rpt_buildings.TDNo', 'like', '%'.$searchByTDNo.'%');
            }
    
            if (!empty($searchByOwner)) { 
                $citizenProfile->where(DB::raw('CONCAT(citizen_profiles.fName," ",citizen_profiles.mName," ",citizen_profiles.lName)'), 'like', '%'.$searchByOwner.'%');
                $nameProfile->where(DB::raw('CONCAT(name_profiles.first_name," ",name_profiles.middle_name," ",name_profiles.last_name)'), 'like', '%'.$searchByOwner.'%');
            }
    
            $citizenProfiles = $citizenProfile->where('rpt_buildings.isActive', '=', '1')->orderBy('rpt_buildings.refID','ASC')->get();
            $nameProfiles = $nameProfile->where('rpt_buildings.isActive', '=', '1')->orderBy('rpt_buildings.refID','ASC')->get();
            $results = $citizenProfiles->merge($nameProfiles);
        }
        else if($searchByType === 'Machinery') {
            $citizenProfile = RptMachineries::select('rpt_machineries.id', 'rpt_machineries.refID', 'rpt_machineries.faasId', 'rpt_machineries.isActive', 'rpt_machineries.TDNo',
                'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'),
                'faas_machineries.refID as faasRefId', 'faas_machineries.primaryOwnerId', 'faas_machineries.ownerAddress')
                ->join('faas_machineries', 'rpt_machineries.faasId', '=', 'faas_machineries.id')
                ->join('citizen_profiles', 'faas_machineries.primaryOwnerId', '=', 'citizen_profiles.id')
                ->with('citizen_profile');

            $nameProfile = RptMachineries::select('rpt_machineries.id', 'rpt_machineries.refID', 'rpt_machineries.faasId', 'rpt_machineries.isActive', 'rpt_machineries.TDNo',
                'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'),
                'faas_machineries.refID as faasRefId', 'faas_machineries.primaryOwnerId', 'faas_machineries.ownerAddress')
                ->join('faas_machineries', 'rpt_machineries.faasId', '=', 'faas_machineries.id')
                ->join('name_profiles', 'faas_machineries.primaryOwnerId', '=', 'name_profiles.id')
                ->with('name_profile');
            
            if (!empty($searchByReferenceId)) { 
                $citizenProfile->where('rpt_machineries.refID', 'like', '%'.$searchByReferenceId.'%');
                $nameProfile->where('rpt_machineries.refID', 'like', '%'.$searchByReferenceId.'%');
            }
    
            if (!empty($searchByTDNo)) { 
                $citizenProfile->where('rpt_machineries.TDNo', 'like', '%'.$searchByTDNo.'%');
                $nameProfile->where('rpt_machineries.TDNo', 'like', '%'.$searchByTDNo.'%');
            }
    
            if (!empty($searchByOwner)) { 
                $citizenProfile->where(DB::raw('CONCAT(citizen_profiles.fName," ",citizen_profiles.mName," ",citizen_profiles.lName)'), 'like', '%'.$searchByOwner.'%');
                $nameProfile->where(DB::raw('CONCAT(name_profiles.first_name," ",name_profiles.middle_name," ",name_profiles.last_name)'), 'like', '%'.$searchByOwner.'%');
            }
    
            $citizenProfiles = $citizenProfile->where('rpt_machineries.isActive', '=', '1')->orderBy('rpt_machineries.refID','ASC')->get();
            $nameProfiles = $nameProfile->where('rpt_machineries.isActive', '=', '1')->orderBy('rpt_machineries.refID','ASC')->get();
            $results = $citizenProfiles->merge($nameProfiles);
        }

        
        return $results;
    }
}
