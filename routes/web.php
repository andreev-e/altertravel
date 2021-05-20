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


Route::get('/new', 'App\Http\Controllers\PoisController@new')->name('new');
Route::get('/popular', 'App\Http\Controllers\PoisController@popular')->name('popular');
Route::get('/izbrannoye', 'App\Http\Controllers\PoisController@izbrannoye')->name('izbrannoye');


Route::get('/location/{url}', 'App\Http\Controllers\PoisController@location')->name('location');
Route::get('/tag/{url}', 'App\Http\Controllers\PoisController@tag')->name('tag');
Route::get('/user/{url}', 'App\Http\Controllers\PoisController@user')->name('user');
Route::get('/place/', 'App\Http\Controllers\PoisController@single_place')->name('poi');
Route::get('/place/{url}', 'App\Http\Controllers\PoisController@single_place')->name('single-poi');

Route::get('/route/{url}', 'App\Http\Controllers\PoisController@single_route')->name('single-route');
Route::get('/routes', 'App\Http\Controllers\RoutesController@routes')->name('routes');


Route::get('/place_edit/{id}', 'App\Http\Controllers\PoisController@single_edit')->name('single-poi-edit');
Route::post('/place_edit/{id}', 'App\Http\Controllers\PoisController@single_edit')->name('single-poi-edit-post');

Route::post('/place_hide/{url}', 'App\Http\Controllers\PoisController@hide')->name('poi-hide');
Route::post('/place_show/{url}', 'App\Http\Controllers\PoisController@show')->name('poi-show');
Route::post('/place_delete/{url}', 'App\Http\Controllers\PoisController@delete')->name('poi-delete');

Route::get('/secure', 'App\Http\Controllers\PoisController@secure_index')->name('secure');

Route::get('/secure/add', 'App\Http\Controllers\PoisController@store')->name('add');
Route::post('/secure/add', 'App\Http\Controllers\PoisController@store');

Route::get('/json/poi.json', 'App\Http\Controllers\PoisController@poi_json')->name('poi_json');

Route::get('/users', 'App\Http\Controllers\UsersController@list')->name('users');

Route::get('/import', 'App\Http\Controllers\PoisController@import')->name('import');
