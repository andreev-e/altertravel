<?php

namespace App\Http\Controllers;

use App\Models\Photoes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoesController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        request()->validate([
          'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($files = $request->file('image')) {
            $url=Storage::put("/public/test", $request->image);
            $request->session()->push('files', $url);
            return Response()->json([
              "image" => $url
            ], 200);
        }

    }

    public function show(Photoes $photoes)
    {
        //
    }

    public function edit(Photoes $photoes)
    {
        //
    }

    public function update(Request $request, Photoes $photoes)
    {
        //
    }

    public function destroy(Photoes $photoes)
    {
        //
    }
}
