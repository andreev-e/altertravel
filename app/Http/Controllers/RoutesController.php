<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Routes;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

use Auth;


class RoutesController extends Controller
{

  protected $sorts= [
    ['id.desc', 'Самые новые'],
    ['id.asc', 'Самые старые'],
    ['views.desc', 'Самые популярные'],
  ];

  public function my_routes_index()    {
        $routes=array();
        if (Auth::check()) $routes=Routes::where('user_id','=',auth()->user()->id)->where('status','<>',99)->orderbyDESC('updated_at')->Paginate(env('OBJECTS_ON_PAGE',15));
        return view('my_routes', compact('routes'));
    }


  public function routes(Request $request)
  {
    $sorts=$this->sorts;
    if (isset($request->sort)) [$table,$direction]=explode('.',$request->sort);
    else [$table,$direction]=explode('.',$this->sorts[0][0]);
    if (!in_array($direction,['asc','desc']) or !in_array($table,['id','views'])) abort(404);

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
