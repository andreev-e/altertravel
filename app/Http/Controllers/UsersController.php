<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function list() {
      $users=User::where('publications','>',0)->orderby('publications','desc')->get();
      return view('users', compact('users'));
    }
}
