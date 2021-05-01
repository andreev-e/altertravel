<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poi;


class PoiController extends Controller
{
    public function index()
    {
        $pois = new Poi;
        $pois->all();

        $pois=Poi::all();
        return view('catalog', compact('pois'));
    }
}
