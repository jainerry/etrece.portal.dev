<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('backpack.dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::group([
    'middleware' => ['auth', 'verified'],
    'namespace'  => 'App\Http\Controllers',
], function () { // custom web routes

});

require __DIR__.'/auth.php';

// Route::get('/linkstorage', function () {
//     Artisan::call('storage:link');
// });