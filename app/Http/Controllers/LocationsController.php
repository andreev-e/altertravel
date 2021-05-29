<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locations;
use App\Models\Categories;

class LocationsController extends Controller
{

  public function old_redirect(Request $request)
  {
      $loc=$cat='';
      $url=explode('/',urldecode($request->path()));
      if (array_key_exists(1,$url)) $loc=$url[1];
      if (array_key_exists(2,$url)) $cat=$url[2];
      $location=Locations::where('name','=',$loc)->first();
      $category=Categories::where('name','=',$cat)->first();
      if (!(is_object($location) and is_object($category))) $category=Categories::where('name','=',$loc)->first();

      if (is_object($location) and is_object($category)) return redirect()->route('location',[$location->url,$category->url]);
      elseif (is_object($location)) return redirect()->route('location',[$location->url,'']);
      elseif (is_object($category)) return redirect()->route('category',[$category->url]);
      else  return redirect()->route('catalog');
  }

}
