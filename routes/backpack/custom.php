<?php

use Illuminate\Support\Facades\Route;
use App\Models\CitizenProfile;
use App\Http\Resources\CitizenProfileDropdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Raw;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    Route::get('/api/cp',function(Request $req){
        $query  = CitizenProfile::select(DB::raw('CONCAT(citizen_profiles.fName," ",citizen_profiles.mName," ",citizen_profiles.lName," - ",citizen_profiles.refId," - ",`barangays`.name,"-",citizen_profiles.bdate) as data, citizen_profiles.id'))
                ->join('barangays','citizen_profiles.brgyId','=','barangays.id')
        ->where('fName', 'like',"%{$req->q}%");

        // $columns = ['fName', 'mName', 'lName'];
        // $d = $req->q;
       
        //     foreach($columns as $column){
        //         $query->orWhere($column, 'LIKE', '%' . $d . '%');
        //         }
      
        return $query->get();
    });
    Route::crud('user', 'UserCrudController');
    Route::crud('citizen-profile', 'CitizenProfileCrudController');
    Route::crud('office', 'OfficeCrudController');
    Route::crud('employee', 'EmployeeCrudController');
    Route::crud('position', 'PositionCrudController');
    Route::crud('section', 'SectionCrudController');
    Route::crud('office-location', 'OfficeLocationCrudController');
    Route::crud('appointment', 'AppointmentCrudController');
    Route::crud('street', 'StreetCrudController');
    Route::crud('faas-machinery', 'FaasMachineryCrudController');
    Route::crud('faas-other', 'FaasOtherCrudController');
    Route::crud('building-profile', 'BuildingProfileCrudController');
    Route::crud('barangay', 'BarangayCrudController');
}); // this should be the absolute last line of this file