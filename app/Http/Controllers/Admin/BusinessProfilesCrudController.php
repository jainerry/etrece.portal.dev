<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BusinessProfilesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use \Backpack\CRUD\app\Library\Widget;
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
       $this->crud->addField([
        'name' => 'non_trece_resident',
        'type' => 'checkbox',
        'wrapper' => [
            'element'=>'span',
            'class' => 'form-group position-absolute end-0' ,
            'style' =>'right:35px;z-index:9;margin-top:0px;'
        ],
        'tab' => 'Details',
       ]);
       $this->crud->addField([
        'label' => 'Owner',
        'type' => 'primary_owner_input',
        'name' => 'owner_cid',
        'entity' => 'owner',
        'attribute' => 'full_name',
        'data_source' => url('/admin/api/citizen-profile/ajaxsearch'),
        'minimum_input_length' => 1,
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-6 mt-3',
        ],
        'tab' => 'Details',
        ]);
     
        $this->crud->addField([
            'name'=> 'first_name',
            'type' => 'text',
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 notintrece',
            ],
        ]);
        $this->crud->addField([
            'name'=> 'middle_name',
            'type' => 'text',
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 notintrece',
            ],
        ]);
        $this->crud->addField([
            'name'=> 'last_name',
            'type' => 'text',
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 notintrece',
            ],
        ]);
        $this->crud->addField([
            'name'=>'suffix',
            'label'=>'Suffix',
            'type' => 'select_from_array',
            'options' => [
                'JRA' => 'JRA',
                'SR' => 'SR',
                'JR' => 'JR',
                'I' => 'I',
                'II' => 'II',
                'III' => 'III',
                'IV' => 'IV',
                'V' => 'V',
                'VI' => 'VI',
                'VII' => 'VII'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3 notintrece'
            ],
            'tab' => 'Details',
        ]);
        $this->crud->addField([
            'name'=> 'contact_no',
            'type' => 'text',
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 notintrece',
            ],
        ]);
        $this->crud->addField([ 
            'name'        => 'sex',
            'label'       => "Sex",
            'type'        => 'select_from_array',
            'options'     => ['1' => 'Male', '0' => 'Female'],
            'allows_null' => false,
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 notintrece'
            ]
        ]);
        $this->crud->addField([
            'name'=>'city',
            'label' => "City",
            'type'=>'select',
            'entity' => 'municipality',
            'attribute' => 'name',
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-4 notintrece',
            ]
        ]);

        $this->crud->addField([
            'name'=>'address',
            'label' => "Address",
            'type'=>'textarea',
            'tab' => 'Details',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12 notintrece',
            ]
        ]);



     
     
        $this->crud->addField([
        'label' => 'Main Office Business Address (Land Profile)',
        'type' => 'business_main_office',
        'name' => 'main_land_id',
        'entity' => 'main_land',
        'attribute' => 'full_name',
        'data_source' => url('/admin/api/faas-land/ajaxsearch'),
        'minimum_input_length' => 1,
        'wrapperAttributes' => [
            'class' => 'form-group col-12 col-md-12',
        ],
        'tab' => 'Details',
        ]);
     
        $this->crud->addField([
            'name'=>'property_owner',
            'label'=>'Property Owner',
            'type' => 'select_from_array',
            'options' => [
                'Y' => 'Yes',
                'N' => 'No',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
            'tab' => 'Details',
        ]);
    //    $this->crud->field('lessor_name_cid');
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
