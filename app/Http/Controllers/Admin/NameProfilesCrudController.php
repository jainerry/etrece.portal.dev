<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\NameProfilesRequest;
use App\Models\NameProfiles;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;
USE Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Backpack\CRUD\app\Library\Widget;
/**
 * Class NameProfilesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class NameProfilesCrudController extends CrudController
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
        $this->crud->setModel(\App\Models\NameProfiles::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/name-profiles');
        $this->crud->setEntityNameStrings('name profiles', 'name profiles');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
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
                    return route('name-profiles.edit',$entry->id);
                },
            ],
          ]);
        $this->crud->column('fullname');
        $this->crud->column('suffix');
        $this->crud->column('municipality_id');
        $this->crud->column('address');
        $this->crud->column('isActive');
        $this->crud->column('created_at');
  

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - $this->crud->column('price')->type('number');
         * - $this->crud->addColumn(['name' => 'price', 'type' => 'number']); 
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
        $this->crud->setValidation(NameProfilesRequest::class);

        Widget::add([
            'type'     => 'script',
            'name'      => 'custom_script',
            'content'  => '/assets/js/name_profile_create.js',
        ]);


        $this->crud->addField([
            'name' =>'first_name',
            'type' =>'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name' =>'middle_name',
            'type' =>'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
        ]);
        $this->crud->addField([
            'name' =>'last_name',
            'type' =>'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
           ]
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
            'hint'=>'(optional)',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
        ]);
        $this->crud->addField([
            'name'  => 'bdate',
            'type'  => 'date_picker',
            'label' => 'Birthdate',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'yyyy-mm-dd',
                'language' => 'fr',
                'endDate' => '0d',
                'startDate' => Carbon::now()->subYears(130)->format('Y-m-d')
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-lg-3'
            ]
         ]);
         $this->crud->addField([
            'name'  => 'contact_no',
            'type'  => 'text',
            'label' => 'Contact No',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ]
         ]);

         $this->crud->addField([ 
            'name'        => 'sex',
            'label'       => "Sex",
            'type'        => 'select_from_array',
            'options'     => ['1' => 'Male', '0' => 'Female'],
            'allows_null' => false,
           
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ]
        ]);
       $this->crud->addField([
            'name'=>'municipality_id',
            'label' => "City",
            'type'=>'select',
            'entity' => 'municipality',
            'attribute' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3',
            ]
        ]);
        $this->crud->addField([
            'name'=>'address',
            'label'=>'Address',
            'type'  => 'textarea',
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-12'
           ]
           
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

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - $this->crud->field('price')->type('number');
         * - $this->crud->addField(['name' => 'price', 'type' => 'number'])); 
         */
    }
    public function checkDuplicate(Request $req){
        $input = $req->all();
     
        $count = NameProfiles::select(DB::raw('count(*) as count'))
        ->where('first_name',strtolower($req->first_name))
        ->where('last_name',strtolower($req->last_name))
        ->where('bdate',"{$req->bdate}");

        if(isset($req->mName)){
            $count->where('middle_name',strtolower($req->middle_name));
        }
        if(isset($req->suffix)){
            $count->where('suffix',strtolower($req->suffix));
        }
        if(isset($req->id)){
            $count->where('id',"<>",strtolower($req->id));
        }
        return response()->json($count->first());
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
