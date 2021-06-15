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


Route::get('/my_pois', 'App\Http\Controllers\PoisController@my_pois_index')->name('my_pois');
Route::post('/pois/{poi}/hide', 'App\Http\Controllers\PoisController@hide')->name('pois.hide');
Route::post('/pois/{poi}/publish', 'App\Http\Controllers\PoisController@publish')->name('pois.publish');
Route::resource('pois', 'App\Http\Controllers\PoisController');



Route::post('/photoes/store', 'App\Http\Controllers\PhotoesController@store')->name('photoes.store');;
//Route::resource('photoes', 'App\Http\Controllers\PhotoesController');



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

Route::get('/route/{url}', 'App\Http\Controllers\RoutesController@index')->name('single-route');
Route::get('/routes', 'App\Http\Controllers\RoutesController@routes')->name('routes');



Route::get('/my_routes', 'App\Http\Controllers\RoutesController@indexMy')->name('my_routes');
Route::get('/my_routes/add', 'App\Http\Controllers\RoutesController@add')->name('my_routes_add');
Route::post('/my_routes/add', 'App\Http\Controllers\RoutesController@add');
Route::post('/route_hide/{url}', 'App\Http\Controllers\RoutesController@hide')->name('route-hide');
Route::post('/route_show/{url}', 'App\Http\Controllers\RoutesController@show')->name('route-show');
Route::post('/route_delete/{url}', 'App\Http\Controllers\RoutesController@delete')->name('route-delete');
Route::get('/route_edit/{id}', 'App\Http\Controllers\RoutesController@edit')->name('single-route-edit');
Route::post('/route_edit/{id}', 'App\Http\Controllers\RoutesController@edit')->name('single-route-edit-post');

Route::resource('routes', 'App\Http\Controllers\RoutesController');


Route::get('/me/edit', 'App\Http\Controllers\UsersController@edit')->name('user_edit');
Route::post('/me/edit', 'App\Http\Controllers\UsersController@edit');

Route::get('/poi_comment/add', 'App\Http\Controllers\PoisCommentsController@add')->name('pois_comments_add');
Route::post('/poi_comment/add', 'App\Http\Controllers\PoisCommentsController@add');
Route::post('/poi_comment/delete/{id}', 'App\Http\Controllers\PoisCommentsController@delete')->name('pois_comments_delete');
Route::post('/poi_comment/approve/{id}', 'App\Http\Controllers\PoisCommentsController@approve')->name('pois_comments_approve');
Route::post('/poi_comment/delete_all/{id}', 'App\Http\Controllers\PoisCommentsController@deleteAll')->name('pois_comments_delete_all');

Route::get('/json/poi.json', 'App\Http\Controllers\PoisController@poiJson')->name('poi_json');
Route::get('/json/route_points.json', 'App\Http\Controllers\PoisController@routePointsJson')->name('route_points');

Route::get('/users', 'App\Http\Controllers\UsersController@list')->name('users');

///socualite
Route::get('/auth/{provider}', 'App\Http\Controllers\Auth\LoginController@redirectToProvider')->name('oauth');
Route::get('/callback/{provider}', 'App\Http\Controllers\Auth\LoginController@handleProviderCallback');

//service tools
Route::get('/service/import/{what}', 'App\Http\Controllers\ServiceController@import')->name('import');

//redirects from old pages
Route::get('view.php', 'App\Http\Controllers\PoisController@redirectFromOldUrl');
Route::get('view_route.php', 'App\Http\Controllers\RoutesController@redirectFromOldUrl');
Route::get('/catalog/{url}', 'App\Http\Controllers\LocationsController@redirectFromOldUrl');
Route::get('/catalog/{url}/{category}', 'App\Http\Controllers\LocationsController@redirectFromOldUrl');
