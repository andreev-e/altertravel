<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Routes;

class RoutesController extends Controller
{
  public function routes()
  {
      $routes=Routes::where('status','=',1)->orderby('created_at','desc')->Paginate(15);
      return view('routes', compact('routes'));
  }
}
