<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Models\FaasMachinery;
use App\Models\BuildingProfile;
use App\Models\FaasLand;

/**
 * Class RPTAPIsController
 * @package App\Http\Controllers\Admin
 * 
 */
class RPTAPIsController
{

    /**
     * Define what happens when the api - /api/rpt/machineries/search - has been called
     *
     * @return void
     */
    public function machineriesSearch(Request $request)
    {
        $totalFilteredRecord = $totalDataRecord = $draw_val = "";
        $columns_list = array(
            0 => 'refID',
            1 => 'primaryOwner',
            2 => 'ownerAddress',
            3 => 'created_at',
            4 => 'id',
        );
         
        $totalDataRecord = FaasMachinery::where('isActive','=','Y')->count();
         
        $totalFilteredRecord = $totalDataRecord;
         
        $limit_val = $request->input('length');
        $start_val = $request->input('start');
        $order_val = $columns_list[$request->input('order.0.column')];
        $dir_val = $request->input('order.0.dir');
         
        if(empty($request->input('search.value'))) {
            $model_data = FaasMachinery::where('isActive','=','Y')
                ->with('citizen_profile', function ($query) {
                    $query->select('id','fName','mName','lName');
                })
                ->offset($start_val)
                ->limit($limit_val)
                ->orderBy($order_val,$dir_val)
                ->get();
        }
        else {
            $search_text = $request->input('search.value');
            
            $model_data =  FaasMachinery::where('isActive','=','Y')
                ->orWhere('refID','LIKE',"%{$search_text}%")
                ->with('citizen_profile', function ($query) use ($search_text) {
                    $query->select('id','fName','mName','lName')
                    ->orWhere('fName','LIKE',"%{$search_text}%")
                    ->orWhere('mName','LIKE',"%{$search_text}%")
                    ->orWhere('lName','LIKE',"%{$search_text}%");
                })
                ->orWhere('ownerAddress', 'LIKE',"%{$search_text}%")
                ->offset($start_val)
                ->limit($limit_val)
                ->orderBy($order_val,$dir_val)
                ->get();
            
            $totalFilteredRecord = FaasMachinery::where('isActive','=','Y')
                ->orWhere('refID','LIKE',"%{$search_text}%")
                ->with('citizen_profile', function ($query) use ($search_text) {
                    $query->select('id','fName','mName','lName')
                    ->orWhere('fName','LIKE',"%{$search_text}%")
                    ->orWhere('mName','LIKE',"%{$search_text}%")
                    ->orWhere('lName','LIKE',"%{$search_text}%");
                })
                ->orWhere('ownerAddress', 'LIKE',"%{$search_text}%")
                ->count();
        }
         
        $data_val = array();
        if(!empty($model_data)) {
            foreach ($model_data as $model_val) {
                $datashow =  route('rpt-view-machinery',$model_val->id);

                $modelnestedData['refID'] = $model_val->refID;
                $modelnestedData['primaryOwner'] = $model_val->citizen_profile->full_name;
                $modelnestedData['ownerAddress'] = $model_val->ownerAddress;
                $modelnestedData['created_at'] = date('j M Y h:i a',strtotime($model_val->created_at));
                $modelnestedData['options'] = "<a href='{$datashow}' class='btn btn-sm btn-link'><i class='la la-eye'></i> Open</a>";
                $data_val[] = $modelnestedData;
            }
        }
        $draw_val = $request->input('draw');
        $get_json_data = array(
            "draw"            => intval($draw_val),
            "recordsTotal"    => intval($totalDataRecord),
            "recordsFiltered" => intval($totalFilteredRecord),
            "data"            => $data_val
        );
         
        echo json_encode($get_json_data);
    }

    /**
     * Define what happens when the api - /api/rpt/buildings/search - has been called
     *
     * @return void
     */
    public function buildingsSearch(Request $request)
    {
        $totalFilteredRecord = $totalDataRecord = $draw_val = "";
        $columns_list = array(
            0 => 'refID',
            1 => 'primaryOwner',
            2 => 'ownerAddress',
            4 => 'created_at',
            5 => 'id',
        );
         
        $totalDataRecord = BuildingProfile::where('isActive','=','Y')->count();
         
        $totalFilteredRecord = $totalDataRecord;
         
        $limit_val = $request->input('length');
        $start_val = $request->input('start');
        $order_val = $columns_list[$request->input('order.0.column')];
        $dir_val = $request->input('order.0.dir');
         
        if(empty($request->input('search.value'))) {
            $model_data = BuildingProfile::where('isActive','=','Y')
                ->with('citizen_profile', function ($query) {
                    $query->select('id','fName','mName','lName');
                })
                ->offset($start_val)
                ->limit($limit_val)
                ->orderBy($order_val,$dir_val)
                ->get();
        }
        else {
            $search_text = $request->input('search.value');
            
            $model_data =  BuildingProfile::where('isActive','=','Y')
                ->orWhere('refID','LIKE',"%{$search_text}%")
                ->with('citizen_profile', function ($query) use ($search_text) {
                    $query->select('id','fName','mName','lName')
                    ->orWhere('fName','LIKE',"%{$search_text}%")
                    ->orWhere('mName','LIKE',"%{$search_text}%")
                    ->orWhere('lName','LIKE',"%{$search_text}%");
                })
                ->orWhere('ownerAddress', 'LIKE',"%{$search_text}%")
                ->offset($start_val)
                ->limit($limit_val)
                ->orderBy($order_val,$dir_val)
                ->get();
            
            $totalFilteredRecord = BuildingProfile::where('isActive','=','Y')
                ->orWhere('refID','LIKE',"%{$search_text}%")
                ->with('citizen_profile', function ($query) use ($search_text) {
                    $query->select('id','fName','mName','lName')
                    ->orWhere('fName','LIKE',"%{$search_text}%")
                    ->orWhere('mName','LIKE',"%{$search_text}%")
                    ->orWhere('lName','LIKE',"%{$search_text}%");
                })
                ->orWhere('ownerAddress', 'LIKE',"%{$search_text}%")
                ->count();
        }
         
        $data_val = array();
        if(!empty($model_data)) {
            foreach ($model_data as $model_val) {
                $datashow =  route('rpt-view-building',$model_val->id);

                $modelnestedData['refID'] = $model_val->refID;
                $modelnestedData['primaryOwner'] = $model_val->citizen_profile->full_name;
                $modelnestedData['ownerAddress'] = $model_val->ownerAddress;
                $modelnestedData['created_at'] = date('j M Y h:i a',strtotime($model_val->created_at));
                $modelnestedData['options'] = "<a href='{$datashow}' class='btn btn-sm btn-link'><i class='la la-eye'></i> Open</a>";
                $data_val[] = $modelnestedData;
            }
        }
        $draw_val = $request->input('draw');
        $get_json_data = array(
            "draw"            => intval($draw_val),
            "recordsTotal"    => intval($totalDataRecord),
            "recordsFiltered" => intval($totalFilteredRecord),
            "data"            => $data_val
        );
         
        echo json_encode($get_json_data);
    }

    /**
     * Define what happens when the api - /api/rpt/lands/search - has been called
     *
     * @return void
     */
    public function landsSearch(Request $request)
    {
        $totalFilteredRecord = $totalDataRecord = $draw_val = "";
        $columns_list = array(
            0 => 'refID',
            1 => 'primaryOwner',
            2 => 'ownerAddress',
            3 => 'created_at',
            4 => 'id',
        );
         
        $totalDataRecord = FaasLand::where('isActive','=','Y')->count();
         
        $totalFilteredRecord = $totalDataRecord;
         
        $limit_val = $request->input('length');
        $start_val = $request->input('start');
        $order_val = $columns_list[$request->input('order.0.column')];
        $dir_val = $request->input('order.0.dir');
         
        if(empty($request->input('search.value'))) {
            $model_data = FaasLand::where('isActive','=','Y')
                ->with('citizen_profile', function ($query) {
                    $query->select('id','fName','mName','lName');
                })
                ->offset($start_val)
                ->limit($limit_val)
                ->orderBy($order_val,$dir_val)
                ->get();
        }
        else {
            $search_text = $request->input('search.value');
            
            $model_data =  FaasLand::where('isActive','=','Y')
                ->orWhere('refID','LIKE',"%{$search_text}%")
                ->with('citizen_profile', function ($query) use ($search_text) {
                    $query->select('id','fName','mName','lName')
                    ->orWhere('fName','LIKE',"%{$search_text}%")
                    ->orWhere('mName','LIKE',"%{$search_text}%")
                    ->orWhere('lName','LIKE',"%{$search_text}%");
                })
                ->orWhere('ownerAddress', 'LIKE',"%{$search_text}%")
                ->offset($start_val)
                ->limit($limit_val)
                ->orderBy($order_val,$dir_val)
                ->get();
            
            $totalFilteredRecord = FaasLand::where('isActive','=','Y')
                ->orWhere('refID','LIKE',"%{$search_text}%")
                ->with('citizen_profile', function ($query) use ($search_text) {
                    $query->select('id','fName','mName','lName')
                    ->orWhere('fName','LIKE',"%{$search_text}%")
                    ->orWhere('mName','LIKE',"%{$search_text}%")
                    ->orWhere('lName','LIKE',"%{$search_text}%");
                })
                ->orWhere('ownerAddress', 'LIKE',"%{$search_text}%")
                ->count();
        }
         
        $data_val = array();
        if(!empty($model_data)) {
            foreach ($model_data as $model_val) {
                $datashow =  route('rpt-view-land',$model_val->id);

                $modelnestedData['refID'] = $model_val->refID;
                $modelnestedData['primaryOwner'] = $model_val->citizen_profile->full_name;
                $modelnestedData['ownerAddress'] = $model_val->ownerAddress;
                $modelnestedData['created_at'] = date('j M Y h:i a',strtotime($model_val->created_at));
                $modelnestedData['options'] = "<a href='{$datashow}' class='btn btn-sm btn-link'><i class='la la-eye'></i> Open</a>";
                $data_val[] = $modelnestedData;
            }
        }
        $draw_val = $request->input('draw');
        $get_json_data = array(
            "draw"            => intval($draw_val),
            "recordsTotal"    => intval($totalDataRecord),
            "recordsFiltered" => intval($totalFilteredRecord),
            "data"            => $data_val
        );
         
        echo json_encode($get_json_data);
    }

}
