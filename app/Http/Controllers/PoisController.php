<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pois;
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
        if (Auth::check()) $pois=Poi::where('owner','=',auth()->user()->id)->get();
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
}
