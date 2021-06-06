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

Route::get('/izbrannoye', 'App\Http\Controllers\PoisController@izbrannoye')->name('izbrannoye');


Route::get('/locations', 'App\Http\Controllers\PoisController@location')->name('catalog');
Route::get('/locations/{location_url}/', 'App\Http\Controllers\PoisController@location')->name('location');

Route::get('/category/{category_url}', 'App\Http\Controllers\PoisController@category')->name('category');
Route::get('/category/{category_url}/{location_url}', 'App\Http\Controllers\PoisController@category')->name('category');

Route::get('/tag/{tag_url}', 'App\Http\Controllers\PoisController@tag')->name('tag');
Route::get('/tag/{tag_url}/{location_url}', 'App\Http\Controllers\PoisController@tag')->name('tag');


Route::get('/user/{url}', 'App\Http\Controllers\PoisController@user')->name('user');
Route::get('/place/', 'App\Http\Controllers\PoisController@single_place')->name('poi');
Route::get('/place/{url}', 'App\Http\Controllers\PoisController@single_place')->name('single-poi');

Route::get('/route/{url}', 'App\Http\Controllers\RoutesController@single_route')->name('single-route');
Route::get('/routes', 'App\Http\Controllers\RoutesController@routes')->name('routes');


Route::get('/my_pois', 'App\Http\Controllers\PoisController@my_pois_index')->name('my_pois');
Route::get('/my_pois/add', 'App\Http\Controllers\PoisController@my_pois_add')->name('my_pois_add');
Route::post('/my_pois/add', 'App\Http\Controllers\PoisController@my_pois_add');
Route::post('/place_hide/{url}', 'App\Http\Controllers\PoisController@hide')->name('poi-hide');
Route::post('/place_show/{url}', 'App\Http\Controllers\PoisController@show')->name('poi-show');
Route::post('/place_delete/{url}', 'App\Http\Controllers\PoisController@delete')->name('poi-delete');
Route::get('/place_edit/{id}', 'App\Http\Controllers\PoisController@single_edit')->name('single-poi-edit');
Route::post('/place_edit/{id}', 'App\Http\Controllers\PoisController@single_edit')->name('single-poi-edit-post');

Route::get('/my_routes', 'App\Http\Controllers\RoutesController@my_routes_index')->name('my_routes');
Route::get('/my_routes/add', 'App\Http\Controllers\RoutesController@my_routes_add')->name('my_routes_add');
Route::post('/my_routes/add', 'App\Http\Controllers\RoutessController@my_routes_add');
Route::post('/route_hide/{url}', 'App\Http\Controllers\RoutesController@hide')->name('route-hide');
Route::post('/route_show/{url}', 'App\Http\Controllers\RoutesController@show')->name('route-show');
Route::post('/route_delete/{url}', 'App\Http\Controllers\RoutesController@delete')->name('route-delete');
Route::get('/route_edit/{id}', 'App\Http\Controllers\RoutesController@single_edit')->name('single-route-edit');
Route::post('/route_edit/{id}', 'App\Http\Controllers\RoutesController@single_edit')->name('single-route-edit-post');

Route::get('/me/edit', 'App\Http\Controllers\UsersController@user_edit')->name('user_edit');
Route::post('/me/edit', 'App\Http\Controllers\UsersController@user_edit');


Route::get('/json/poi.json', 'App\Http\Controllers\PoisController@poi_json')->name('poi_json');

Route::get('/users', 'App\Http\Controllers\UsersController@list')->name('users');

///socualite
Route::get('/auth/{provider}', 'App\Http\Controllers\Auth\LoginController@redirectToProvider')->name('oauth');
Route::get('/callback/{provider}', 'App\Http\Controllers\Auth\LoginController@handleProviderCallback');

//service tools
Route::get('/service/import/{what}', 'App\Http\Controllers\ServiceController@import')->name('import');
Route::get('/service/clusterize', 'App\Http\Controllers\ServiceController@clusterize')->name('clusterize');


//redirects from old pages
Route::get('view.php', 'App\Http\Controllers\PoisController@old_redirect');
Route::get('view_route.php', 'App\Http\Controllers\RoutesController@old_redirect');
Route::get('/catalog/{url}', 'App\Http\Controllers\LocationsController@old_redirect');
Route::get('/catalog/{url}/{category}', 'App\Http\Controllers\LocationsController@old_redirect');
