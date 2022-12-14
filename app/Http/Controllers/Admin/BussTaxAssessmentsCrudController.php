<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BussTaxAssessmentsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;

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
        $this->crud->column('refID');
        $this->crud->column('application_type');
        $this->crud->column('assessment_date');
        $this->crud->column('assessment_year');
        $this->crud->column('payment_type');
        $this->crud->column('net_profit');
        $this->crud->column('num_of_employees');
        $this->crud->column('other_fees');
        $this->crud->column('deliquent');
        $this->crud->column('tax_withheld_and_discount');
        $this->crud->column('remarks');
        $this->crud->column('assessmentLevels');
        $this->crud->column('isActive');
        $this->crud->column('created_at');
        $this->crud->column('updated_at');

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


        $this->crud->addField([  // Select2
         'label'     => "Application Type",
         'type'      => 'select2',
         'name'      => 'application_type', // the db column for the foreign key

        // optional
         'entity'    => 'bussType', // the method that defines the relationship in your Model
         'attribute' => 'name', // foreign key attribute that is shown to user
         'tab' => 'Details',
         'wrapperAttributes' => [
             'class' => 'form-group col-12 col-md-6 mt-3'
         ],
         // also optional
         'options'   => (function ($query) {
             return $query->orderBy('name', 'ASC')->get();
         }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
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
      'label'     => "Application Type",
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
         'tab' => 'Details',
         'wrapperAttributes' => [
             'class' => 'form-group col-12 col-md-4 '
         ],
       ]);
       $this->crud->addField([
         "name"=>'payment_type',
         "label"=>"Payment Type",
         'tab' => 'Details',
         'wrapperAttributes' => [
             'class' => 'form-group col-12 col-md-4 '
         ],
        
       ]);
       $this->crud->addField([
         "name"=>'net_profit',
         "label"=>"Net Profit",
         'tab' => 'Details',
         'wrapperAttributes' => [
             'class' => 'form-group col-12 col-md-4 '
         ],
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
    }
}
