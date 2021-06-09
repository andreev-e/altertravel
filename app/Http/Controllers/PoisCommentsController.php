<?php

namespace App\Http\Controllers;

use App\Models\Pois;
use App\Models\PoisComments;

use Illuminate\Http\Request;

use Auth;

class PoisCommentsController extends Controller
{
    public function add(Request $request) {
      if ($request->isMethod('post')) {
        $validated = $request->validate([
            'comment'  => 'required|min:5|max:255',
        ]);
        if ($validated and Auth::check()) {
          $poi=Pois::findOrFail($request->get('pois_id'));
          $comment=PoisComments::create([
            'user_id' => Auth::user()->id,
            'poi_id' => $poi->id,
            'comment' => $request->get('comment'),
            'email' => Auth::user()->email,
            'parent' => 0,
            'status' => 0,
          ]);
        }
      }
      return redirect()->route('single-poi', $poi->url);
    }


    public function approve($id, Request $request) {
          if ($request->isMethod('post')) {
            if (Auth::check()) {
              $comment=PoisComments::findOrFail($id);
              if (Auth::user()->email=='andreev-e@mail.ru') {
                $comment->status=1;
                $comment->save();
              }
            }
          }
          return redirect()->back();
        }

    public function delete($id, Request $request) {
      if ($request->isMethod('post')) {
        if (Auth::check()) {
          $comment=PoisComments::findOrFail($id);
          if ($comment->user_id==Auth::user()->id or Auth::user()->email=='andreev-e@mail.ru') {
            $comment->delete();
          }
        }
      }
      return redirect()->back();
    }


    public function delete_all($id, Request $request) {
      if ($request->isMethod('post')) {
        if (Auth::check()) {
          $comment=PoisComments::findOrFail($id);
          if ($comment->user_id==Auth::user()->id or Auth::user()->email=='andreev-e@mail.ru') {
            if (!is_null($comment->email)) $comments=PoisComments::where('email','=',$comment->email)->delete();
            else $comment->delete();
          }
        }
      }
      return redirect()->back();
    }



}
