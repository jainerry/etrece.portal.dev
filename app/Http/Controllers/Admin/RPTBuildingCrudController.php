<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Http\Request;
use App\Models\BuildingProfile;

class RPTBuildingCrudController extends BuildingProfileCrudController
{
    public function setup()
    {
        $this->crud->setModel(\App\Models\BuildingProfile::class);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/rpt-building');
        $this->crud->setEntityNameStrings('RPT Assessment (Building)', 'RPT Assessment (Buildings)');

        $this->crud->set('show.setFromDb', false);

        Widget::add()->type('style')->content('assets/css/faas/styles.css');
        Widget::add()->type('style')->content('assets/css/backpack/crud/crud_fields_styles.css');
        Widget::add()->type('style')->content('assets/css/faas/building/styles.css');
        Widget::add()->type('script')->content('assets/js/jquery.inputmask.bundle.min.js');
        Widget::add()->type('script')->content('assets/js/backpack/crud/inputmask.js');
        Widget::add()->type('script')->content('assets/js/faas/building/functions.js');
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
            'name'      => 'refID',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, ) {
                    return route('rpt-building.edit',$entry->id);
                },
            ]
        ]);
        $this->crud->column('ARPNo')->label('ARP No.');
        $this->crud->addColumn([
            'name'  => 'primary_owner',
            'label' => 'Primary Owner',
            'type'  => 'select',
            'entity'    => 'citizen_profile',
            'attribute' => 'full_name'
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

    public function create()
    {
        Widget::add()->type('script')->content('assets/js/faas/building/rpt-create-functions.js');
        $this->crud->hasAccessOrFail('create');
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;
        return view('rpt.building.create', $this->data);
    }

    public function checkIfPrimaryOwnerExist(Request $request){
        $primaryOwnerId = $request->input('primaryOwnerId');
        $primaryOwners = [];
        if ($primaryOwnerId)
        {
            $primaryOwners = BuildingProfile::select('id', 'refID', 'ARPNo', 'transactionCode', 'TDNo', 'primary_owner', 'ownerAddress', 'isActive', 'isApproved')
                ->where('primary_owner', '=', $primaryOwnerId) 
                ->orderBy('refID','ASC')
                ->get();
        }
        return $primaryOwners;
    }

    public function getDetails(Request $request){
        $id = $request->input('id');
        $details = [];
        if ($id)
        {
            $details = BuildingProfile::with('citizen_profile','citizen_profile.barangay','citizen_profile.street')
                ->find($id);
            ;
        }
        return $details;
    }
}
