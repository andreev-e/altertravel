<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddController extends Controller
{
    public function index(Request $request)
    {
      // выполнять код, если есть POST-запрос
        if ($request->isMethod('post')) {

        // валидация формы
        $request->validate([
            'title'  => 'required|min:5|max:255',
            'lat'  => 'required',
            'lng'  => 'required',
            'description'  => 'required|min:20',
            'photo'  => 'required',
        ]);

    }
    }
}
