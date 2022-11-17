<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BarangayRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Barangay;
use App\Models\TransactionLogs;

/**
 * Class BarangayCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BarangayCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view-barangays', ['only' => ['index','show']]);
        $this->middleware('can:create-barangays', ['only' => ['create','store']]);
        $this->middleware('can:edit-barangays', ['only' => ['edit','update']]);
        $this->middleware('can:delete-barangays', ['only' => ['destroy']]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Barangay::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/barangay');
        CRUD::setEntityNameStrings('barangay', 'barangays');
        $this->crud->removeButton('delete');
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
                    return route('barangay.edit',$entry->id);
                },
            ]
        ]);
        $this->crud->column('name');
        $this->crud->column('municipality');
        $this->crud->addColumn([
            'label'=>'Status',
            'type'  => 'model_function',
            'function_name' => 'getStatus',
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(BarangayRequest::class);

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Barangay',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4',
            ]
        ]);
        $this->crud->addField([
            'name'=>'municipalityId',
            'label' => "City",
            'type'=>'select',
            'entity' => 'municipality',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4',
            ]
        ]);
        $this->crud->addField([
            'name'  => 'separator2a',
            'type'  => 'custom_html',
            'value' => '<hr>',
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
                'class' => 'form-group col-12 col-md-3'
            ],
        ]);

        Barangay::creating(function($entry) {
            $count = Barangay::count();
            $refID = 'BRGY-'.str_pad(($count), 4, "0", STR_PAD_LEFT);
            $entry->refID = $refID;

            $transCount = TransactionLogs::count();
            $transRefID = 'TRANS-LOG'.'-'.str_pad(($transCount), 4, "0", STR_PAD_LEFT);

            TransactionLogs::create([
                'refID' => $transRefID,
                'transId' =>$refID,
                'category' =>'barangay',
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

        Barangay::updating(function($entry) {

            $transCount = TransactionLogs::count();
            $transRefID = 'TRANS-LOG'.'-'.str_pad(($transCount), 4, "0", STR_PAD_LEFT);
          
            TransactionLogs::create([
                'refID' => $transRefID,
                'transId' =>$entry->refID,
                'category' =>'barangay',
                'type' =>'update',
            ]);
        });
    }
}
