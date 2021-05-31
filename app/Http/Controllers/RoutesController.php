<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Routes;

use Illuminate\Support\Facades\Cache;

class RoutesController extends Controller
{

  protected $sorts= array(
    array('sort'=>'id.desc', 'name'=> 'Самые новые'),
    array('sort'=>'id.asc', 'name'=> 'Самые старые'),
    array('sort'=>'views.desc', 'name'=>'Самые популярные')
  );
  //default sort
  protected $default_table='id';
  protected $default_direction='desc';

  public function routes(Request $request)
  {
    $sorts=$this->sorts;
    $table=$this->default_table;
    $direction=$this->default_direction;
    if (isset($request->sort))  {
      $sort=explode('.',$request->sort);
      $table=$sort[0];
      $direction=$sort[1];
    }
      $routes=Routes::where('status','=',1)->orderby($table,$direction)->Paginate(env('OBJECTS_ON_PAGE',15));
      return view('routes', compact('routes','sorts','request'));
  }

  public function old_redirect(Request $request)
  {
      $route=Routes::where('old_id','=',$request->id)->first();
      return redirect()->route('single-route',$route->url);
  }

  public function single_route($url)
  {
      $route = Cache::remember('single_route_'.$url, env('CACHE_TIME',60), function () use ($url) {
      $route=Routes::where('url', $url)->firstOrFail();
      return $route;
      });
      $route->increment('views');
      return view('route', compact('route'));
  }
}
