<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BusinessTaxFeesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;
/**
 * Class BusinessTaxFeesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BusinessTaxFeesCrudController extends CrudController
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
       $this->crud->setModel(\App\Models\BusinessTaxFees::class);
       $this->crud->setRoute(config('backpack.base.route_prefix') . '/business-tax-fees');
       $this->crud->setEntityNameStrings('business tax fees', 'business tax fees');
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
       $this->crud->column('business_fees_id');
       $this->crud->column('effective_date');
       $this->crud->column('chart_of_accounts_lvl4_id');
       $this->crud->column('business_categories');
       $this->crud->column('Basis');
       $this->crud->column('range_box');
       $this->crud->column('computation');
       $this->crud->column('amount_value');
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
       $this->crud->setValidation(BusinessTaxFeesRequest::class);

       
       $this->crud->addField([
        "name"=>"business_fees_id",
        "label"=>"Fees",
        'type'      => 'select',
        'entity'    => 'fees',
        'attribute' => 'name', 
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-6'
        ],
        'options'   => (function ($query) {
            return $query->orderBy('name', 'ASC')->get();
        }), 
       ]);
       $this->crud->addField([
        'name'  => 'effective_date',
        'type'  => 'date_picker',
        'label' => 'Effective Date',
        'date_picker_options' => [
            'todayBtn' => 'linked',
            'format'   => 'yyyy-mm-dd',
            'language' => 'fr',
            'endDate' => '0d',
            'startDate' => Carbon::now()->subYears(130)->format('Y-m-d'),
        ],
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-6'
        ]
     ]);
       $this->crud->field('chart_of_accounts_lvl4_id');
       $this->crud->field('business_categories');
       $this->crud->field('Basis');
       $this->crud->field('range_box');
       $this->crud->field('computation');
       $this->crud->field('amount_value');
       $this->crud->field('isActive');

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
