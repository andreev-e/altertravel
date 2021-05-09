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

Route::get('/', 'App\Http\Controllers\PoisController@index')->name('/');

Route::get('/catalog', 'App\Http\Controllers\PoisController@catalog')->name('catalog');
Route::get('/new', 'App\Http\Controllers\PoisController@new')->name('new');
Route::get('/popular', 'App\Http\Controllers\PoisController@popular')->name('popular');
Route::get('/location/{url}', 'App\Http\Controllers\PoisController@location')->name('location');
Route::get('/tag/{url}', 'App\Http\Controllers\PoisController@tag')->name('tag');
Route::get('/user/{url}', 'App\Http\Controllers\PoisController@user')->name('user');


Route::get('/place/{url}', 'App\Http\Controllers\PoisController@single')->name('single-poi');
Route::get('/place/', 'App\Http\Controllers\PoisController@single')->name('pois');
Route::get('/edit/{url}', 'App\Http\Controllers\PoisController@single_edit')->name('single-poi-edit');

Route::post('/hide/{url}', 'App\Http\Controllers\PoisController@hide')->name('poi-hide');
Route::post('/show/{url}', 'App\Http\Controllers\PoisController@show')->name('poi-show');
Route::post('/delete/{url}', 'App\Http\Controllers\PoisController@delete')->name('poi-delete');

Route::get('/secure', 'App\Http\Controllers\PoisController@secure_index')->name('secure');

Route::post('/secure/add', 'App\Http\Controllers\PoisController@store')->name('add');

Route::get('/json/poi.json', 'App\Http\Controllers\PoisController@poi_json')->name('poi_json');
