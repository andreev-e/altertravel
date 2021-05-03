<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pois;
use App\Models\Tags;
use App\Models\Locations;
use Auth;

class PoisController extends Controller
{
    public function index()
    {
        $pois=Pois::where('status','=',1)->get();
        return view('catalog', compact('pois'));
    }
    public function secure_index()
    {
        $pois=array();
        if (Auth::check()) $pois=Pois::where('user_id','=',auth()->user()->id)->where('status','<>',99)->get();
        return view('catalog_secure', compact('pois'));
    }
    public function single($url)
    {
        $poi=Pois::firstWhere('url', $url);
        if (auth()->user()!==null) return view('poi', compact('poi'));
        else return view('poi', compact('poi'));
    }
    public function single_edit($url)
    {
        $poi=Pois::firstWhere('url', $url);
        if (auth()->user()!==null and auth()->user()->id==$poi->owner) return view('poi_secure', compact('poi'));
        else return redirect()->route('single-poi', $poi->url);
    }

    public function location($url)
    {
        $location=Locations::firstWhere('url', $url);
        $pois=$location->pois()->where('status','=',1)->get();
        return view('location', compact('pois'));
    }

    public function tag($url)
    {
      $tag=Tags::firstWhere('url', $url);
        $pois=$tag->pois()->where('status','=',1)->get();
        return view('tag', compact('pois'));
    }

    public function hide($id)
    {
        if (auth()->user()!==null) {
        $poi = Pois::firstWhere('id', $id);
        $poi->status=0;
        $poi->save();
        }
        return redirect()->route('secure');
    }

    public function show($id)
    {
      if (auth()->user()!==null) {
      $poi = Pois::firstWhere('id', $id);
      $poi->status=1;
      $poi->save();
      }
        return redirect()->route('secure');
    }

    public function delete($id)
    {
      if (auth()->user()!==null) {
      $poi = Pois::firstWhere('id', $id);
      $poi->status=99;
      $poi->save();
      }
        return redirect()->route('secure');
    }
}
