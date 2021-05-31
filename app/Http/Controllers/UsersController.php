<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Intervention\Image\ImageManagerStatic as Image;

class UsersController extends Controller
{
    public function list() {
      $users=User::where('login','<>','')->orderby('publications','desc')->Paginate(env('OBJECTS_ON_PAGE',15));
      return view('users', compact('users'));
    }

    public function user_edit(Request $request) {

      if (\Auth::user()->id!==null) {
        $user=User::find(\Auth::user()->id);

      if ($request->isMethod('post')) {
        $validated = $request->validate([
            'name'  => 'required|string|min:3|max:255',
            'login'  => 'string|min:3|max:20',
        ]);

        if ($validated) {
          $user->name=$request->name;
          $user->site=$request->site;
          $user->about=$request->about;

          $user->login=$request->login;

          if($request->hasFile('avatar_full')) {
          $path = $request->file('avatar_full')->store('/public/avatars');
          $user->avatar_original=$path;
          $filename=explode('/',$path);
          $filename=array_pop($filename);
          //
          $image_resize = Image::make(storage_path().'/app/'.$path);
          $image_resize->resize(75, 75)->save(storage_path().'/app/public/avatars/thumbs/'.$filename);

          $user->avatar='avatars/'.$filename;
          }

          $user->save();
        }

       }

        return view('user_edit', compact('user'));
      }
      else return redirect()->route('/');
}
}
