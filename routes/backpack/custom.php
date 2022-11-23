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
    Route::get('/citizen-profile/cluster','CitizenProfileCrudController@getCluster');
    Route::post('/citizen-profile/check-duplicate','CitizenProfileCrudController@checkDuplicate');
    Route::post('/employee/check-duplicate','EmployeeCrudController@checkDuplicate');
    Route::post('/name-profiles/check-duplicate','NameProfilesCrudController@checkDuplicate');

    Route::get('/api/citizen-profile/ajaxsearch', 'CitizenProfileCrudController@ajaxsearch');
    Route::get('/api/faas-land/ajaxsearch', 'FaasLandCrudController@ajaxsearch');
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
    Route::crud('building-profile', 'BuildingProfileCrudController');
    Route::crud('barangay', 'BarangayCrudController');
    Route::crud('cities', 'MunicipalityCrudController');
    Route::crud('province', 'ProvinceCrudController');
    Route::crud('structural-type', 'StructuralTypeCrudController');
    Route::crud('kind-of-building', 'KindOfBuildingCrudController');
    Route::crud('faas-machinery-secondary-owners', 'FaasMachinerySecondaryOwnersCrudController');
    Route::crud('structural-roofs', 'StructuralRoofsCrudController');
    Route::crud('faas-land', 'FaasLandCrudController');
    Route::crud('faas-land-secondary-owners', 'FaasLandSecondaryOwnersCrudController');
    Route::crud('faas-land-classification', 'FaasLandClassificationCrudController');

    Route::get('/rpt-new-assessment-request', 'RPTController@newAssessmentRequest')->name('rpt-new-assessment-request');
    Route::get('/rpt-assessment-requests', 'RPTController@assessmentRequests')->name('rpt-assessment-requests');

    Route::post('/api/rpt/machineries/search', 'RPTAPIsController@machineriesSearch')->name('rpt-machineries-search');
    Route::get('/rpt-view-machinery/{id}', 'RPTController@viewMachinery')->name('rpt-view-machinery');

    Route::post('/api/rpt/buildings/search', 'RPTAPIsController@buildingsSearch')->name('rpt-buildings-search');
    Route::get('/rpt-view-building/{id}', 'RPTController@viewBuilding')->name('rpt-view-building');

    Route::post('/api/rpt/lands/search', 'RPTAPIsController@landsSearch')->name('rpt-lands-search');
    Route::get('/rpt-view-land/{id}', 'RPTController@viewLand')->name('rpt-view-land');

    Route::crud('structural-flooring', 'StructuralFlooringCrudController');
    Route::crud('structural-walling', 'StructuralWallingCrudController');
    Route::crud('structural-additional-items', 'StructuralAdditionalItemsCrudController');
    Route::crud('transaction-logs', 'TransactionLogsCrudController');
    Route::crud('faas-building-classifications', 'FaasBuildingClassificationsCrudController');
    Route::crud('faas-machinery-classifications', 'FaasMachineryClassificationsCrudController');
   
    Route::crud('regions', 'RegionsCrudController');
    Route::crud('business-profiles', 'BusinessProfilesCrudController');
    Route::crud('name-profiles', 'NameProfilesCrudController');
    Route::crud('business-type', 'BusinessTypeCrudController');
}); // this should be the absolute last line of this file