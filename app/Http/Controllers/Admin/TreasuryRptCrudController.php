<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TreasuryRptRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Http\Request;

/**
 * Class TreasuryRptCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TreasuryRptCrudController extends CrudController
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
        CRUD::setModel(\App\Models\TreasuryRpt::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/treasury-rpt');
        CRUD::setEntityNameStrings('treasury rpt', 'treasury rpts');
        $this->crud->removeButton('delete'); 

        Widget::add()->type('style')->content('assets/css/faas/styles.css');
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');
        Widget::add()->type('script')->content('assets/js/jquery.inputmask.bundle.min.js');
        Widget::add()->type('script')->content('assets/js/backpack/crud/inputmask.js');
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
                    return route('treasury-rpt.edit',$entry->id);
                },
            ],
          ]);

        $this->crud->column('or_no');
        $this->crud->column('rptId');
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
        CRUD::setValidation(TreasuryRptRequest::class);

        $this->crud->addField(
            [
                'name'=>'rptType',
                'label'=>'Type',
                'type' => 'select_from_array',
                'options' => [
                    'Building' => 'Building', 
                    'Machinery' => 'Machinery',
                    'Land' => 'Land'
                ],
                'allows_null' => false,
                'default'     => 'Y',
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-3'
                ],
            ]
        );
        $this->crud->addField(
            [
                'name'=>'TDNo',
                'label'=>'TD No.',
                'fake'=>true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-3'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'ARPNo',
                'label'=>'ARP No.',
                'fake'=>true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-3'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'rptId',
                'type'=>'hidden',
            ]
        );
        $this->crud->addField([
            'name'  => 'separator01',
            'type'  => 'custom_html',
            'value' => '<hr>',
        ]);
        $this->crud->addField(
            [
                'name'=>'primaryOwner',
                'label'=>'Owner',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'ownerAddress',
                'label'=>'Address',
                'type'=>'textarea',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-12'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'administrator',
                'label'=>'Owner',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'administratorAddress',
                'label'=>'Address',
                'type'=>'textarea',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-12'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'assessedValue',
                'label'=>'Assessed Value',
                'fake'=>true,
                'attributes' => [
                    'class' => 'form-control text_input_mask_currency',
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'dateAssessed',
                'label'=>'Date Assessed',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'area',
                'label'=>'Area',
                'fake'=>true,
                'attributes' => [
                    'class' => 'form-control text_input_mask_currency',
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'lotNo',
                'label'=>'Lot Number',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name'=>'title',
                'label'=>'Title',
                'fake'=>true,
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-12 col-md-6'
                ]
            ]
        );
        $this->crud->addField([
            'name'  => 'separator02',
            'type'  => 'custom_html',
            'value' => '<hr>',
        ]);
        $this->crud->addField([
            'name'=>'periodCovered',
            'label'=>'Period Covered',
            'type' => 'select_from_array',
            'options' => [
                'Quarterly' => 'Quarterly', 
                'Semi-Annually' => 'Semi-Annually',
                'Annually' => 'Annually',
            ],
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
        ]);
        $this->crud->addField([
            'name'  => 'separator02a',
            'type'  => 'custom_html',
            'value' => '<hr>',
        ]);
        $this->crud->addField([   
            'name'  => 'otherFees',
            'label' => 'Other Fees',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'particulars',
                    'type'    => 'text',
                    'label'   => 'Particulars',
                    'attributes' => [
                        'class' => 'form-control particulars',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-9'],
                ],
                [
                    'name'    => 'amount',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency amount',
                    ],
                    'label'   => 'Amount',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
            ],
            'new_item_label'  => 'New Item', 
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => false,
        ]);
        $this->crud->addField([
            'name'  => 'separator03',
            'type'  => 'custom_html',
            'value' => '<hr>',
        ]);
        $this->crud->addField([   
            'name'  => 'summary',
            'label' => 'Summary',
            'type'  => 'repeatable',
            'subfields' => [
                [
                    'name'    => 'particulars',
                    'type'    => 'text',
                    'label'   => 'Particulars',
                    'attributes' => [
                        'class' => 'form-control particulars',
                    ],
                    'wrapper' => ['class' => 'form-group col-md-9'],
                ],
                [
                    'name'    => 'amount',
                    'type'=>'text',
                    'attributes' => [
                        'class' => 'form-control text_input_mask_currency amount',
                    ],
                    'label'   => 'Amount',
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
            ],
            'new_item_label'  => 'New Item', 
            'init_rows' => 1,
            'min_rows' => 1,
            'max_rows' => 10,
            'reorder' => false,
        ]);
        $this->crud->addField([
            'name'  => 'separator04',
            'type'  => 'custom_html',
            'value' => '<hr>',
        ]);
        $this->crud->addField([
            'name'=>'isActive',
            'label'=>'Status <span style="color:red;">*</span>',
            'type' => 'select_from_array',
            'options' => [
                1 => 'Active', 
                0 => 'Inactive'
            ],
            'allows_null' => false,
            'default'     => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-12 col-md-3'
            ],
        ]);
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
