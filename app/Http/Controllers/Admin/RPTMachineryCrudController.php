<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

class RPTMachineryCrudController extends FaasMachineryCrudController
{
    public function setup()
    {
        $this->crud->setModel(\App\Models\FaasMachinery::class);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/rpt-machinery');
        $this->crud->setEntityNameStrings('RPT Assessment (Machinery)', 'RPT Assessment (Machineries)');

        $this->crud->set('show.setFromDb', false);

        Widget::add()->type('style')->content('assets/css/faas/styles.css');
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');
        Widget::add()->type('script')->content('assets/js/jquery.inputmask.bundle.min.js');
        Widget::add()->type('script')->content('assets/js/backpack/crud/inputmask.js');
        Widget::add()->type('script')->content('assets/js/faas/machinery/functions.js');
    }

    protected function setupListOperation()
    {
        $this->crud->enableExportButtons();
        $this->crud->removeButton('delete');  
        $this->crud->removeButton('show');
        $this->crud->removeButton('update'); 
        
        $this->crud->addColumn([
            'label'     => 'Reference ID',
            'type'      => 'text',
            'name'      => 'refID', // the db column for the foreign key
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('rpt-machinery.edit',$entry->id);
                },
            ],
        ]);
        // $this->crud->addColumn([
        //     'name'  => 'primaryOwner',
        //     'label' => 'Primary Owner',
        //     'type'  => 'select',
        //     'entity'    => 'citizen_profile',
        //     'attribute' => 'full_name'
        // ],);

        $this->crud->addColumn([
            'label'=>'Primary Owner',
            'type'  => 'model_function',
            'function_name' => 'getPrimaryOwner',
        ]);

        $this->crud->column('ownerAddress')->limit(255)->label('Owner Address');
        $this->crud->addColumn([
            'name'  => 'isApproved',
            'label' => 'Approved',
            'type'  => 'boolean',
            'options' => [0 => 'No', 1 => 'Yes'],
            'wrapper' => [
                'element' => 'span',
                'class'   => function ($crud, $column, $entry, $related_key) {
                    if ($column['text'] == 'Yes') {
                        return 'badge badge-success';
                    }
                    return 'badge badge-default';
                },
            ],
        ]);
        $this->crud->addClause('where', 'isActive', '=', '1');
        $this->crud->orderBy('refID','ASC');
    }

    protected function setupCreateOperation()
    {
        parent::setupCreateOperation();
    }
}
