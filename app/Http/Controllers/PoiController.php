<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poi;


class PoiController extends Controller
{
    public function index()
    {
        $pois=Poi::where('status','=',1)->get();
        return view('catalog', compact('pois'));
    }
    public function secure_index()
    {
        //$pois=Poi::all();
        $pois=Poi::where('owner','=',auth()->user()->id)->get();
        return view('catalog_secure', compact('pois'));
    }
    public function single($url)
    {
        $poi=Poi::firstWhere('url', $url);
        if (auth()->user()!==null) return view('poi', compact('poi'));
        else return view('poi', compact('poi'));
    }
    public function single_edit($url)
    {
        $poi=Poi::firstWhere('url', $url);
        if (auth()->user()!==null and auth()->user()->id==$poi->owner) return view('poi_secure', compact('poi'));
        else return redirect()->route('single-poi', $poi->url); 

    }
}
