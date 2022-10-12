<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CitizenProfileRequest;
use App\Models\CitizenProfile;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Date;
use Yajra\Address\Entities\Barangay;
use Illuminate\Support\Facades\DB;
/**
 * Class CitizenProfileCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CitizenProfileCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CitizenProfile::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/citizen-profile');
        CRUD::setEntityNameStrings('citizen profile', 'citizen profiles');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        
        
        
        CRUD::column('refID');
        // CRUD::addColumn([
        //     'name'=>'fName',
        //     'label'=>'First Name'
        // ]);
        // CRUD::addColumn([
        //     'name'=>'mName',
        //     'label'=>'Middle Name'
        // ]);
        // CRUD::addColumn([
        //     'name'=>'lName',
        //     'label'=>'Last Name'
        // ]);
        
        CRUD::addColumn([
            'label'=>'Full Name',
            'type'  => 'model_function',
            'function_name' => 'getFullName',
        ]);
      
        CRUD::column('bdate');
        CRUD::addColumn([
            'name'=>'brgyID',
            'label' => "Barangay",
            'type'=>'select',
            'entity' => 'barangay',
            'attribute' => 'name',
        ]);
        CRUD::column('address');
        CRUD::column('civilStatus');
        CRUD::column('created_at');
        CRUD::column('isActive');
        CRUD::column('placeOfOrigin');
        CRUD::column('purokID');
       
        CRUD::column('sex');
        CRUD::column('suffix');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
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
        CRUD::setValidation(CitizenProfileRequest::class);

        $brgys = Barangay::select('id','name')->where('city_id','042122')->get();
        $brgy = [];
        foreach($brgys as $br){
            $brgy += [$br->id => $br->name];
        }
        $this->crud->addField([
            'name'=>'fName',
            'label'=>'First Name',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'mName',
            'label'=>'Middle Name',
            'allows_null' => true,
            'hint'=>'optional',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
           ]
        ]);
        $this->crud->addField([
            'name'=>'lName',
            'label'=>'Last Name',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
           ]
           
        ]);
        $this->crud->addField([
            'name'=>'suffix',
            'label'=>'Suffix',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6'
           ]
           
        ]);

        $this->crud->addField([
            'name'=>'address',
            'label'=>'Address',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6'
           ]
           
        ]);
        $this->crud->addField([
            'name'=>'bdate',
            'label'=>'Birthday',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6'
           ]
           
        ]);
        
        $this->crud->addField([ 
            'name'        => 'sex',
            'label'       => "Sex",
            'type'        => 'select_from_array',
            'options'     => ['1' => 'Male', '0' => 'Female'],
            'allows_null' => false,
            'default'     => '1',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-6'
            ]
        ]);

        $this->crud->addField([   // select_from_array
            'name'        => 'brgyID',
            'label'       => "Baranggay",
            'type'        => 'select_from_array',
            'options'     => $brgy,
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
            ]
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);
        $this->crud->addField([   // select_from_array
            'name'        => 'purokID',
            'label'       => "Purok",
            'type'        => 'select_from_array',
            'options'     => ['N/A' => 'N/A'],
            'allows_null' => false,
            'default'     => '1',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
            ]
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);
        $this->crud->addField([   // select_from_array
            'name'        => 'civilStatus',
            'label'       => "Civil Status",
            'type'        => 'select_from_array',
            'options'     => ['Single' => 'Single', 
                              'Married' => 'Married',
                              'Widowed' => 'Widowed'],
            'allows_null' => false,
            'default'     => '1',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-4'
            ]
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);
        
       $this->crud->addField([   // Textarea
            'name'  => 'placeOfOrigin',
            'label' => 'Place of Origin',
            'type'  => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-12'
            ]
        ]);
       
        $this->crud->addField([   
            'name'        => 'isActive',
            'label'       => "isActive",
            'type'        => 'select_from_array',
            'options'     => ['y'=>'TRUE','n'=>'FALSE'],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-12'
            ]
        ]);
       
        
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
        CitizenProfile::creating(function($entry) {
            $count = CitizenProfile::select(DB::raw('count(*) as count'))->where('refID','like',"%".Date('mdY')."%")->first();
            $refId = 'CID'.Date('mdY').'-'.str_pad(($count->count), 4, "0", STR_PAD_LEFT);

            $entry->refID = $refId;
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
       
    }
}