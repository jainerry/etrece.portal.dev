<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BusinessProfilesRequest;
use App\Models\BusinessProfiles;
use App\Models\CitizenProfile;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use \Backpack\CRUD\app\Library\Widget;
use GuzzleHttp\Psr7\Request;

/**
 * Class BusinessProfilesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BusinessProfilesCrudController extends CrudController
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
       $this->crud->setModel(\App\Models\BusinessProfiles::class);
       $this->crud->setRoute(config('backpack.base.route_prefix') . '/business-profiles');
       $this->crud->setEntityNameStrings('business profiles', 'business profiles');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
       $this->crud->column('buss_id')->label('Business ID');
       $this->crud->column('business_name')->label('Business Name');

       $this->crud->column('sec_reg_date');
       $this->crud->column('isActive')->label('Status');

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
       $this->crud->setValidation(BusinessProfilesRequest::class);
       Widget::add([
        'type'     => 'script',
        'name'      => 'custom_script',
        'content'  => '/assets/js/business.js',
         ]);


       $this->crud->addField([
        'name' => 'business_name',
        'type' => 'text',
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-6 mt-3'
        ],
        'tab' => 'Details',
       ]);
       $this->crud->addField([  // Select2
        'label'     => "Category",
        'type'      => 'select2',
        'name'      => 'category_id', // the db column for the foreign key
     
        // optional
        'entity'    => 'category', // the method that defines the relationship in your Model
        'model'     => "App\Models\BusinessCategory", // foreign key model
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
     $id = $this->crud->getCurrentEntryId();
     if($id != false){
    $data = BusinessProfiles::where('id',$id)->first();
    $owner_id = $data->owner_id;
    $ownerExist  = CitizenProfile::where("id",$owner_id)->count();
    if($ownerExist == 0 ){
        $this->crud->addField([
            'label' => 'Owner/Pres/OIC',
            'type' => 'business_owner',
            'name' => 'owner_id',
            'entity' => 'names',
            'attribute' => 'full_name',
            'data_source' => url('/admin/api/citizen-profile/search-business-owner'),
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12 ',
            ],
            'tab' => 'Details',
            ]);
    }else{
        $this->crud->addField([
            'label' => 'Owner/Pres/OIC',
            'type' => 'business_owner',
            'name' => 'owner_id',
            'entity' => 'owner',
            'attribute' => 'full_name',
            'data_source' => url('/admin/api/citizen-profile/search-business-owner'),
            'minimum_input_length' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12 ',
            ],
            'tab' => 'Details',
            ]);
    }

   }else{
    $this->crud->addField([
        'label' => 'Owner/Pres/OIC',
        'type' => 'business_owner',
        'name' => 'owner_id',
        'entity' => 'owner',
        'attribute' => 'full_name',
        'data_source' => url('/admin/api/citizen-profile/search-business-owner'),
        'minimum_input_length' => 1,
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-12 ',
        ],
        'tab' => 'Details',
        ]);
   }
      
     
       
       $this->crud->addField([
        
        'label' => 'Main Office Business Address',
        'type' => 'business_main_office',
        'name' => 'main_office_address',
        'entity' => 'owner',
       
        'attribute' => 'full_name',
        'data_source' => url('/admin/api/faas-land/search'),
        'minimum_input_length' => 2,
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-12 ',
        ],
        'tab' => 'Details',
        
       ]);

       $this->crud->addField([ 
        'name'        => 'property_owner',
        'label'       => "Property Owner (Y/N)",
        'type'        => 'select_from_array',
        'options'     => ['Y' => 'Yes', 'N' => 'No'],
        'allows_null' => false,
        'tab' => 'Details',
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-4'
        ]
    ]);
    $this->crud->addField([
        'label' => 'Lessor Name',
        'type' => 'primary_owner_input',
        'name' => 'lessor_name',
        'entity' => 'owner',
        'attribute' => 'full_name',
        'data_source' => url('/admin/api/citizen-profile/ajaxsearch'),
        'minimum_input_length' => 1,
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-12 ',
        ],
        'tab' => 'Details',
        ]);
    //    $this->crud->field('tel_no');
    //    $this->crud->field('mobile');
    //    $this->crud->field('email');
    //    $this->crud->field('buss_type');
    //    $this->crud->field('corp_type');
    //    $this->crud->field('trade_name_franchise');
    //    $this->crud->field('business_activity_id');
    //    $this->crud->field('other_buss_type');
    //    $this->crud->field('faas_land_id');
    //    $this->crud->field('sec_no');
    //    $this->crud->field('sec_reg_date');
    //    $this->crud->field('dti_no');
    //    $this->crud->field('dti_reg_date');
    //    $this->crud->field('tax_incentives');
    //    $this->crud->field('certificate');
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
