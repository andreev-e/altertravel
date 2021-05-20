<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Routes;

class RoutesController extends Controller
{
  public function routes()
  {
      $pois=Routes::where('status','=',1)->orderby('created_at','desc')->Paginate(15);
      return view('catalog', compact('pois'));
  }
}
