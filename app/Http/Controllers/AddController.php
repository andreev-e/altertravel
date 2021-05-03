<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Pois;
use Auth;

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

        $images = $request->file('photos');
        if ($request->hasFile('photos')) :
        foreach ($images as $item):
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $imageName = $time . '-' . $item->getClientOriginalName();
            $item->move(base_path() . '/uploads/file/', $imageName);
            $arr[] = $imageName;
        endforeach;
        $image = implode(",", $arr);
        else:
                $image = '';
        endif;

        if ($validated and Auth::check()) {
          $new_poi=Pois::create([
            'name' => $request->get('title'),
            'url'=> Str::slug($request->get('title'), '_'),
            'user_id'=>auth()->user()->id,
            'status'=>1,
            'description'=>$request->get('description'),
            'category'=>$request->get('category'),
            'prim'=>$request->get('prim'),
            'route'=>$request->get('route'),
            'video'=>$request->get('video'),
            'photos'=>$image,
          ]);
        }




        return redirect()->route('secure');

    }
    }
}
