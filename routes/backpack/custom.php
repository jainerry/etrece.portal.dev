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

    Route::get('/api/cp/search',function(Request $req){
        $searchTxt = $req->q;
        $query  = CitizenProfile::select(DB::raw('citizen_profiles.*,CONCAT(citizen_profiles.fName," ",citizen_profiles.mName," ",citizen_profiles.lName) as fullname'))
        ->leftJoin('barangays','citizen_profiles.brgyId','=','barangays.id')
        ->orWhere(DB::raw('CONCAT(TRIM(citizen_profiles.fName)," ",TRIM(citizen_profiles.mName),(IF(TRIM(citizen_profiles.mName) IS NOT NULL, " ","")),TRIM(citizen_profiles.lName))'),'LIKE',"%".strtolower($searchTxt)."%")
        ->orWhere('citizen_profiles.refId','like',"%{$searchTxt}%")
        ->orderBy('fullname');
        return $query->get();
    });

    Route::get('/api/citizen-profile/ajaxsearch', 'CitizenProfileCrudController@ajaxsearch');
    Route::get('/api/employee/ajaxsearch', 'EmployeeCrudController@ajaxsearch');

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
    Route::crud('municipality', 'MunicipalityCrudController');
    Route::crud('province', 'ProvinceCrudController');
    Route::crud('structural-type', 'StructuralTypeCrudController');
    Route::crud('kind-of-building', 'KindOfBuildingCrudController');
    Route::crud('faas-other-secondary-owners', 'FaasOtherSecondaryOwnersCrudController');
    Route::crud('department', 'DepartmentCrudController');
    Route::crud('faas-machinery-secondary-owners', 'FaasMachinerySecondaryOwnersCrudController');
    Route::crud('structural-roofs', 'StructuralRoofsCrudController');
    Route::crud('faas-land-idle', 'FaasLandIdleCrudController');
    Route::crud('faas-land', 'FaasLandCrudController');
    Route::crud('faas-land-secondary-owners', 'FaasLandSecondaryOwnersCrudController');
    Route::crud('faas-land-idle-secondary-owners', 'FaasLandIdleSecondaryOwnersCrudController');
    Route::crud('faas-assessment-status', 'FaasAssessmentStatusCrudController');
    Route::crud('faas-land-classification', 'FaasLandClassificationCrudController');

    Route::get('/rpt-new-assessment-request', 'RPTController@newAssessmentRequest')->name('rpt-new-assessment-request');
    Route::get('/rpt-assessment-requests', 'RPTController@assessmentRequests')->name('rpt-assessment-requests');

    Route::post('/api/rpt/machineries/search', 'RPTAPIsController@machineriesSearch')->name('rpt-machineries-search');
    Route::get('/rpt-view-machinery/{id}', 'RPTController@viewMachinery')->name('rpt-view-machinery');

    Route::post('/api/rpt/buildings/search', 'RPTAPIsController@buildingsSearch')->name('rpt-buildings-search');
    Route::get('/rpt-view-building/{id}', 'RPTController@viewBuilding')->name('rpt-view-building');

    Route::post('/api/rpt/lands/search', 'RPTAPIsController@landsSearch')->name('rpt-lands-search');
    Route::get('/rpt-view-land/{id}', 'RPTController@viewLand')->name('rpt-view-land');

    Route::post('/api/rpt/idle-lands/search', 'RPTAPIsController@idleLandsSearch')->name('rpt-idle-lands-search');
    Route::get('/rpt-view-idle-land/{id}', 'RPTController@viewIdleLand')->name('rpt-view-idle-land');

    Route::post('/api/rpt/others/search', 'RPTAPIsController@othersSearch')->name('rpt-others-search');
    Route::get('/rpt-view-other/{id}', 'RPTController@viewOther')->name('rpt-view-other');

    Route::crud('structural-flooring', 'StructuralFlooringCrudController');
    Route::crud('structural-walling', 'StructuralWallingCrudController');
    Route::crud('structural-additional-items', 'StructuralAdditionalItemsCrudController');
}); // this should be the absolute last line of this file