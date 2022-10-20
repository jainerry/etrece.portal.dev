<?php

use Illuminate\Support\Facades\Route;
use App\Models\CitizenProfile;
use App\Models\Employee;
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
        $searchTxt = $req->q;
        $columns = ['fName','mName','lName'];
        $query  = CitizenProfile::select(DB::raw('citizen_profiles.*,CONCAT(citizen_profiles.fName," ",citizen_profiles.mName," ",citizen_profiles.lName) as fullname'))
        ->leftJoin('barangays','citizen_profiles.brgyId','=','barangays.id')
        ->where(function($q) use($searchTxt,$columns){
            $extxt = explode($searchTxt,' ');
         
                foreach($columns as $col){
                    $q->orWhere('citizen_profiles.'.$col,'like',"%{$searchTxt}%");
                } 
        })
        ->orderBy('fullname');
        return $query->get();
    });

    Route::get('/api/citizen-profile/find',function(Request $req){
        $searchTerm = $req->q;
        $query = CitizenProfile::select(DB::raw('CONCAT(fName," ",mName," ",lName) as primaryOwnerData, id'))
        ->orWhereHas('barangay', function ($q) use ($searchTerm) {
            $q->where('name', 'like', '%'.$searchTerm.'%');
        })
        ->orWhere('refID', 'like', '%'.$searchTerm.'%')
        ->orWhere('fName', 'like', '%'.$searchTerm.'%')
        ->orWhere('mName', 'like', '%'.$searchTerm.'%')
        ->orWhere('lName', 'like', '%'.$searchTerm.'%')
        ->orWhere('suffix', 'like', '%'.$searchTerm.'%')
        ->orWhere('address', 'like', '%'.$searchTerm.'%')
        ->orWhereDate('bdate', '=', date($searchTerm));

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