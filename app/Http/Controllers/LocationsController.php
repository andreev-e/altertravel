<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locations;


class LocationsController extends Controller
{

  public function old_redirect(Request $request)
  {
      $loc=explode('/',urldecode($request->path()));
      $loc=$loc[1];
      $location=Locations::where('name','=',$loc)->first();
      if (is_object($location)) return redirect()->route('location',[$location->url,'']); else  return redirect()->route('/');
  }

}
