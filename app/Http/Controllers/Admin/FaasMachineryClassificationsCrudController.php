<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FaasMachineryClassificationsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Http\Request;
use App\Models\FaasMachineryClassifications;

/**
 * Class FaasMachineryClassificationsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FaasMachineryClassificationsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        /*$this->middleware('can:view-machinery-classifications', ['only' => ['index','show']]);
        $this->middleware('can:create-machinery-classifications', ['only' => ['create','store']]);
        $this->middleware('can:edit-machinery-classifications', ['only' => ['edit','update']]);
        $this->middleware('can:delete-machinery-classifications', ['only' => ['destroy']]);*/
        $this->middleware('can:FAAS Configurations > Machinery Classifications', ['only' => ['index','show','create','store','edit','update','destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\FaasMachineryClassifications::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/faas-machinery-classifications');
        $this->crud->setEntityNameStrings('faas machinery classifications', 'faas machinery classifications');
        $this->crud->removeButton('delete');

        Widget::add()->type('style')->content('assets/css/faas/styles.css');
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');
        Widget::add()->type('script')->content('assets/js/jquery.inputmask.bundle.min.js');
        Widget::add()->type('script')->content('assets/js/backpack/crud/inputmask.js');
        Widget::add()->type('script')->content('assets/js/faas/create-machinery-classification-function.js');
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

        $this->crud->addFilter([
            'type'  => 'date',
            'name'  => 'created_at',
            'label' => 'Created At'
            ],
            false,
            function ($value) {
            $this->crud->addClause('whereDate', 'created_at', $value);
        });
        $this->crud->addColumn([
            'label'     => 'Reference ID',
            'type'      => 'text',
            'name'      => 'refID',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('faas-machinery-classifications.edit',$entry->id);
                },
            ],
          ]);
        $this->crud->column('name');
        $this->crud->column('code');
        $this->crud->column('isActive')->label('Status');
        $this->crud->column('created_at');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(FaasMachineryClassificationsRequest::class);

        $this->crud->addField(
            [
                'name'=>'name',
                'label'=>'Name',
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'code',
                'label'=>'Code',
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-4'
                ]
            ]
        );
        /*Assessment Levels*/
        $this->crud->addField([   
            'name'  => 'assessmentLevels',
            'label' => 'Assessment Levels',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'rangeFrom',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency rangeFrom',
                    ],
                    'label'   => 'Range From',
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name'    => 'rangeTo',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency rangeTo',
                    ],
                    'label'   => 'Range To',
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name'    => 'percentage',
                    'type'    => 'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_percent percentage',
                    ],
                    'label'   => 'Percentage (%)',
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
            ],
            'new_item_label'  => 'New Item',
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => true,
        ]);
        $this->crud->addField(
            [
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
                    'class' => 'form-group col-12 col-md-4'
                ],
            ]
        );
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

    public function getDetails(Request $request){
        $id = $request->input('id');
        $results = [];
        if (!empty($id))
        {
            $results = FaasMachineryClassifications::select('id', 'name', 'refID', 'code', 'assessmentLevels')
            ->where('isActive', '=', 'Y')
            ->where('id', '=', $id)
            ->get();
        }

        return $results;
    }
}
