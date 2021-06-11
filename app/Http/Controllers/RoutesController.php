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

    Use \App\Traits\Sortable;

    public function indexMy()
    {
        $routes=array();
        if (Auth::check()) {
            $routes=Routes::where('user_id','=',auth()->user()->id)->where('status','<>',99)
            ->orderbyDESC('updated_at')->Paginate(env('OBJECTS_ON_PAGE',15));
        }
        return view('my_routes', compact('routes'));
    }


    public function routes(Request $request)
    {
        $sort=$this->sorting_array($request);
        $sorts=$sort[0];

        $routes=Routes::where('status','=',1)->orderby($sort[1],$sort[2])->Paginate(env('OBJECTS_ON_PAGE',15));
        return view('routes', compact('routes','sorts','request'));
    }

    public function redirectFromOldUrl(Request $request)
    {
        $route=Routes::where('old_id','=',$request->id)->firstOrFail();
        return redirect()->route('single-route',$route->url);
    }

    public function index($url)
    {
          $route = Cache::remember(
              'single_route_'.$url,
              env('CACHE_TIME',60),
              function () use ($url) {
                  $route=Routes::where('url', $url)->firstOrFail();
                  return $route;
              });
          $route->increment('views');
          return view('route', compact('route'));
    }

    public function add(Request $request)
    {
        //
    }

}
