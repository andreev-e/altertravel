<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Routes;

use Illuminate\Support\Facades\Cache;

class RoutesController extends Controller
{
  public function routes()
  {
      $routes=Routes::where('status','=',1)->orderby('created_at','desc')->Paginate(env('OBJECTS_ON_PAGE',15));
      return view('routes', compact('routes'));
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
      $route->photos=explode(",",$route->photos);
      return $route;
      });
      $route->increment('views');
      return view('route', compact('route'));
  }
}
