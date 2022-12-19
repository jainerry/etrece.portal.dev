<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BusMayorsPermitsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;
/**
 * Class BusMayorsPermitsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BusMayorsPermitsCrudController extends CrudController
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
        $this->crud->setModel(\App\Models\BusMayorsPermits::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/bus-mayors-permits');
        $this->crud->setEntityNameStrings('bus mayors permits', 'bus mayors permits');
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
                    return route('business-profiles.edit',$entry->id);
                },
            ],
          ]);
        $this->crud->addColumn([
            "name"=>"category_id",
            "label" =>"Category",
            
        ]);
        $this->crud->column('from');
        $this->crud->column('to');
        $this->crud->column('isActive');
        $this->crud->column('created_at');
        $this->crud->column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crudcolumn('price')->type('number');
         * - $this->crudaddColumn(['name' => 'price', 'type' => 'number']); 
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
        $this->crud->setValidation(BusMayorsPermitsRequest::class);

        // $this->crud->field('refID');
        $this->crud->addField([  // Select
            'label'     => "Category",
            'type'      => 'select',
            'name'      => 'category_id', // the db column for the foreign key
            'entity'    => 'category',
            'attribute' => 'name', // foreign key attribute that is shown to user
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 '
            ]
         ],);
        $this->crud->addField([
            'name'  => 'effective_date ',
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
                'class' => 'form-group col-12 col-md-6 '
            ]
         ]);
        $this->crud->addField([
            'name'=>'from',
            'label'=>'From',
            'type'=>'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 '
            ],
        ]);
      
        $this->crud->addField([
            'name'=>'to',
            'label'=>'To',
            'type'=>'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 '
            ],
        ]);
        $this->crud->addField([
            'name'=>'amount',
            'label'=>'Amount',
            'type'=>'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-6 '
            ],
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
                'class' => 'form-group col-12 '
            ],
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - $this->crudfield('price')->type('number');
         * - $this->crudaddField(['name' => 'price', 'type' => 'number'])); 
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
        $this->crud->setupCreateOperation();
    }
}
