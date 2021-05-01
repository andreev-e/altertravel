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
Auth::routes();

Route::get('/', function () {
    return view('home');
})->name('/');;

Route::get('/catalog', 'App\Http\Controllers\PoiController@index')->name('catalog');

Route::get('/place/{url}', 'App\Http\Controllers\PoiController@single')->name('single-poi');
Route::get('/edit/{url}', 'App\Http\Controllers\PoiController@single_edit')->name('single-poi-edit');

Route::get('/secure', 'App\Http\Controllers\PoiController@secure_index')->name('secure');
