<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Poi;


class AddController extends Controller
{
    public function store(Request $request)
    {
      // выполнять код, если есть POST-запрос
        if ($request->isMethod('post')) {

        // валидация формы
        $validated = $request->validate([
            'title'  => 'required|min:5|max:255',
            'lat'  => 'required',
            'lng'  => 'required',
            'description'  => 'required|min:20',
        ]);

        if ($validated) Poi::create([
          'name' => $request->get('title'),
          'url'=> Str::slug($request->get('title'), '_'),
          'owner'=>auth()->user()->id,
          'status'=>1
        ]);


        return redirect()->route('secure'); 

    }
    }
}
