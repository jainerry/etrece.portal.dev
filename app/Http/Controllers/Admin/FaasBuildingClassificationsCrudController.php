<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FaasBuildingClassificationsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use App\Models\FaasBuildingClassifications;
use App\Models\TransactionLogs;

/**
 * Class FaasBuildingClassificationsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FaasBuildingClassificationsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view-building-classifications', ['only' => ['index','show']]);
        $this->middleware('can:create-building-classifications', ['only' => ['create','store']]);
        $this->middleware('can:edit-building-classifications', ['only' => ['edit','update']]);
        $this->middleware('can:delete-building-classifications', ['only' => ['destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
       $this->crud->setModel(\App\Models\FaasBuildingClassifications::class);
       $this->crud->setRoute(config('backpack.base.route_prefix') . '/faas-building-classifications');
       $this->crud->setEntityNameStrings('faas building classifications', 'faas building classifications');
        $this->crud->removeButton('delete');

        Widget::add()->type('style')->content('assets/css/faas/styles.css');
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');
        Widget::add()->type('script')->content('assets/js/jquery.inputmask.bundle.min.js');
        Widget::add()->type('script')->content('assets/js/backpack/crud/inputmask.js');
        Widget::add()->type('script')->content('assets/js/faas/create-building-classification-function.js');
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
                    return route('faas-building-classifications.edit',$entry->id);
                },
            ]
        ]);
       $this->crud->column('name');
       $this->crud->column('code');
        $this->crud->column('isActive')->label('Active');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
       $this->crud->setValidation(FaasBuildingClassificationsRequest::class);

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

        FaasBuildingClassifications::creating(function($entry) {
            $count = FaasBuildingClassifications::count();
            $refID = 'BLDG-CLASS'.'-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $transCount = TransactionLogs::count();
            $transRefID = 'TRANS-LOG'.'-'.str_pad(($transCount), 4, "0", STR_PAD_LEFT);

            TransactionLogs::create([
                'refID' => $transRefID,
                'transId' =>$refID,
                'category' =>'faas_building_classifications',
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

        FaasBuildingClassifications::updating(function($entry) {

            $transCount = TransactionLogs::count();
            $transRefID = 'TRANS-LOG'.'-'.str_pad(($transCount), 4, "0", STR_PAD_LEFT);
          
            TransactionLogs::create([
                'refID' => $transRefID,
                'transId' =>$entry->refID,
                'category' =>'faas_building_classifications',
                'type' =>'update',
            ]);
        });
    }

    public function getDetails(Request $request){
        $id = $request->input('id');
        $results = [];
        if (!empty($id))
        {
            $results = FaasBuildingClassifications::select('id', 'name', 'refID', 'code', 'assessmentLevels')
            ->where('isActive', '=', 'Y')
            ->where('id', '=', $id)
            ->get();
        }

        return $results;
    }
}
