<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BussTaxAssessmentsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\BussTaxAssessments;
use Illuminate\Support\Facades\DB;
use Backpack\CRUD\app\Library\Widget;
/**
 * Class BussTaxAssessmentsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BussTaxAssessmentsCrudController extends CrudController
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
        $this->crud->setModel(\App\Models\BussTaxAssessments::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/buss-tax-assessments');
        $this->crud->setEntityNameStrings('buss tax assessments', 'buss tax assessments');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        Widget::add([
            'type'     => 'script',
            'name'      => 'custom_script',
            'content'  => '/assets/js/business/taxAssesment.js',
        ]);

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
                    return route('buss-tax-assessments.edit',$entry->id);
                },
            ],
            'searchLogic' => function ($query, $column, $searchTerm) {
                return $query->orWhere('refID', 'like', '%'.$searchTerm.'%');
 
             }
          ]);
        $this->crud->column('application_type');
        $this->crud->column('assessment_date');
        $this->crud->column('assessment_year');
        $this->crud->column('payment_type');
        $this->crud->column('net_profit');
      
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
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(BussTaxAssessmentsRequest::class);


          $this->crud->addField([
            'name'=>'application_type',
            'label'=>'Application Type',
            'type' => 'select_from_array',
            'tab' => 'Details',
            'options' => [
                'New' => 'New',
                'Renewal' => 'Renewal',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 mt-3'
            ],
        ]);


        $this->crud->addField([
         'name'  => 'assessment_date',
         'type'  => 'date_picker',
         'label' => 'Assessment Date',
         'date_picker_options' => [
             'todayBtn' => 'linked',
             'format'   => 'yyyy-mm-dd',
             'language' => 'fr',
             'endDate' => '0d',
             'startDate' => Carbon::now()->subYears(130)->format('Y-m-d'),
         ],
         'tab' => 'Details',
         'wrapperAttributes' => [
             'class' => 'form-group col-12 col-md-6 mt-3'
         ],
     ]);

        $this->crud->addField([  // Select2
         'label'     => "Business Name",
         'type'      => 'select2',
         'name'      => 'business_profiles_id', // the db column for the foreign key

     // optional
         'entity'    => 'bussProf', // the method that defines the relationship in your Model
         'attribute' => 'business_name', // foreign key attribute that is shown to user
         'tab' => 'Details',
         'wrapperAttributes' => [
             'class' => 'form-group col-12 col-md-12 '
         ],
         // also optional
         'options'   => (function ($query) {
             return $query->orderBy('business_name', 'ASC')->get();
         }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
          ]);
        $this->crud->addField([
          "name"=>'assessment_year',
          "label"=>"Assessment Year",
            'options' => [
                'New' => 'New',
                'Renewal' => 'Renewal',
            ],
            "type"=>"select_from_array",
          'tab' => 'Details',
          'wrapperAttributes' => [
              'class' => 'form-group col-12 col-md-4 '
          ],
        ]);
        $this->crud->addField([
            "name"=>'payment_type',
            "label"=>"Payment Type",
              'options' => [
                  'Monthly' => 'Monthly',
                  'Quarterly' => 'Quarterly',
                  'Semi-Annual' => 'Semi-Annual',
                  'Annual' => 'Annual',
              ],
              "type"=>"select_from_array",
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 '
            ],
          ]);

        // $this->crud->addField([
        //   "name"=>'net_profit',
        //   "label"=>"Net Profit",
        //   'tab' => 'Details',
        //   'wrapperAttributes' => [
        //       'class' => 'form-group col-12 col-md-4 '
        //   ],
        // ]);
        $this->crud->addField([   // repeatable
         'name'  => 'fees_and_delinquency',
         'label' => 'Fees and Delinquency',
         'type'  => 'repeatable_total',
         'subfields' => [ // also works as: "fields"
            [
                'name'        => 'business_tax_fees',
                'label'       => "Business Tax Fees",
                'type'        => 'select',
                'entity'     =>"busTaxFees",
                'attribute' => "fees_dropdown",
                'tab' => 'Details',
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-8'
                ]
                ],
             [
                 'name'    => 'amount',
                 'type'    => 'text',
                 'label'   => 'Amount',
                 "disable"  =>true,
                 "attributes"=>[
                    'readonly' => 'readonly',
                 ],
                 'wrapper' => ['class' => 'form-group col-md-4'],

             ],

         ],

         // optional
         'tab' => 'Details',
         'new_item_label'  => 'Add Group', // customize the text of the button
         'init_rows' => 1, // number of empty rows to be initialized, by default 1
        // allow reordering?
         'reorder' => false, // hide up&down arrows next to each row (no reordering)

    ], );
    $this->crud->addField([   // repeatable
        'name'  => 'tax_withheld_discount',
        'label' => 'Tax withheld discount',
        'type'  => 'repeatable_total',
        'subfields' => [ // also works as: "fields"
            [
                'name'        => 'tax_withheld_discount',
                'label'       => "Tax withheld Discount",
                'type'        => 'select_from_array',
                'options'     => ['Tax Withheld' => 'Tax Withheld', 'Discount' => 'Discount'],
                'allows_null' => true,
                'tab' => 'Details',
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4'
                ]
                ],
            [
                'name'    => 'remarks',
                'type'    => 'text',
                'label'   => 'Remarks',
                'wrapper' => ['class' => 'form-group col-md-4'],

            ],
          
            [
                'name'    => 'amount',
                'type'    => 'text',
                'label'   => 'Amount',
                "attributes"=>[
                    'readonly' => 'readonly',
                 ],
                'wrapper' => ['class' => 'form-group col-md-4'],

            ],

        ],

        // optional
        'tab' => 'Details',
        'new_item_label'  => 'Add Group', // customize the text of the button
        'init_rows' => 1, // number of empty rows to be initialized, by default 1
       // allow reordering?
        'reorder' => false, // hide up&down arrows next to each row (no reordering)

   ], );
        $this->crud->addField([
            // CustomHTML
            'name' => 'separator',
            'type' => 'custom_html',
            'value' => '
                    <label> Summary </label>
                    <table class="table table-bordered summaryTable"> 
                    <thead>
                       <tr>
                       <th>
                       Particulars
                       </th>
                       <th>
                       Amount
                       </th>
                       </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                            Accounts here
                            </td>
                            <td>
                            0.0000
                            </td >
                          
                        </tr>
                        <tr style="border-top: 1px solid rgba(0,40,100,.12);font-size:24px">
                            <td class="border-right-0"">
                            Total
                            </td>
                            <td class="border-left-0">
                            0.0000
                            </td>
                        </tr>
                    </tbody>
             
                    </table>',
            'tab' => 'Details',
        ]);
   $this->crud->addField([
    "name"=>"remarks",
    "type"=>"textarea",
    "label"=>"Remarks",
    'tab' => 'Details',
   ]);
    //    $this->crud->field('payment_type');
    //    $this->crud->field('net_profit');
    //    $this->crud->field('num_of_employees');
    //    $this->crud->field('other_fees');
    //    $this->crud->field('deliquent');
    //    $this->crud->field('tax_withheld_and_discount');
    //    $this->crud->field('remarks');
    //    $this->crud->field('assessmentLevels');
    //    $this->crud->field('isActive');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * -$this->crud->field('price')->type('number');
         * -$this->crud->addField(['name' => 'price', 'type' => 'number']));
         */
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
    }

    public function getDetails(Request $request){
        $id = $request->input('id');
        
        $results = [];
        if (!empty($id))
        {
            $citizenProfiles = BussTaxAssessments::select('buss_tax_assessments.*',
                'citizen_profiles.fName', 'citizen_profiles.mName', 'citizen_profiles.lName', 'citizen_profiles.suffix', 'citizen_profiles.address', DB::raw('"CitizenProfile" as ownerType'),
                'business_profiles.refID as businessRefID', 'business_profiles.business_name')
                ->join('business_profiles', 'buss_tax_assessments.business_profiles_id', '=', 'business_profiles.id')
                ->join('citizen_profiles', 'business_profiles.owner_id', '=', 'citizen_profiles.id')
                ->with('bussProf')
                ->with('bussProf.main_office')
                ->with('bussProf.main_office.barangay')
                ->with('bussType')
                ->where('buss_tax_assessments.isActive', '=', 'Y')
                ->where('buss_tax_assessments.id', '=', $id)
                ->get();

            $nameProfiles = BussTaxAssessments::select('buss_tax_assessments.*',
                'name_profiles.first_name', 'name_profiles.middle_name', 'name_profiles.last_name', 'name_profiles.suffix', 'name_profiles.address', DB::raw('"NameProfile" as ownerType'),
                'business_profiles.refID as businessRefID', 'business_profiles.business_name')
                ->join('business_profiles', 'buss_tax_assessments.business_profiles_id', '=', 'business_profiles.id')
                ->join('name_profiles', 'business_profiles.owner_id', '=', 'name_profiles.id')
                ->with('bussProf')
                ->with('bussProf.main_office')
                ->with('bussProf.main_office.barangay')
                ->with('bussType')
                ->where('buss_tax_assessments.isActive', '=', 'Y')
                ->where('buss_tax_assessments.id', '=', $id)
                ->get();
            
            $results = $citizenProfiles->merge($nameProfiles);
        }

        return $results;
    }
}
