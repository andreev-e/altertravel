<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pois;
use App\Models\User;
use App\Models\Locations;
use App\Models\Tags;
use App\Models\Categories;
use App\Models\Routes;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class ServiceController extends Controller
{


  public function import($what) {
    $echo='Импорт: ';

    $all=json_decode(file_get_contents (__DIR__.'/import/all.json'));
    $chekins=$all[2];
    $comments=$all[3];
    $edits=$all[4];
    $poi=$all[5];
    $relationship=$all[6];
    $routes=$all[7];
    $route_comments=$all[8];
    $tags=$all[9];
    $users=$all[10];
    unset($all);
    Schema::disableForeignKeyConstraints();

    if ($what=='users') {
      User::query()->truncate();
      Schema::enableForeignKeyConstraints();
      foreach ($users->data as $value) {

      if ($value->publications>0 and strlen($value->email)>0)
        $tmp=User::firstOrCreate([
        'name' => $value->firstname." ".$value->lastname,
        'email' => $value->email,
        'avatar'=>  'https://altertravel.ru/authors/'.$value->username.'.jpg',
        'avatar_original'=>  'https://altertravel.ru/authors/'.$value->username.'_full.jpg',
        'login' => $value->username,
        'site' => $value->homepage,
        'about' => $value->about,
        'old_password' => $value->password,
        'password' => Hash::make($value->password),
        'publications' => $value->publications,
      ]);
    }

    unset($users);
    $echo.='Users ok';
    }

    if ($what=='tags') {
    Tags::query()->truncate();
    Schema::enableForeignKeyConstraints();
    foreach ($tags->data as $value) {

      if ( $value->TYPE==0) $tmp=Tags::firstOrCreate([
        'name' => $value->NAME,
        'url' => Str::slug($value->NAME, '_'),
        'name_rod' =>$value->NAME_ROD,
        'old_id'=>$value->ID,
        'count'=>$value->COUNT,
      ]);
    }
      unset($tags);
    $echo.='Tags ok';
    }

    if ($what=='poi') {
      Pois::query()->truncate();
      Schema::enableForeignKeyConstraints();
      foreach ($poi->data as $value) {

      if ($value->lat!=0 and $value->lng!=0) {
        $category=Categories::firstWhere('name','=',$value->type);
        $user=User::firstWhere('login','=',$value->author);
        $name=str_replace(array("'",'"',"&quot;","&laquo;","&raquo;"),'',$value->name);
        $tmp=Pois::create([
          'old_id'=>$value->id,
          'name' => $name,
          'user_id' => (is_object($user)?$user->id:8),
          'category_id'=>(is_object($category)?$category->id:null),
          'url' => Str::slug($name, '_'),
          'lat' =>$value->lat,
          'lng' =>$value->lng,
          'description'=>$value->description,
          'route'=>$value->route,
          'views'=>$value->views,
          'status'=>$value->show,
          'prim'=>$value->addon,
          'video'=>$value->ytb,
          'links'=>$value->links,
          'copyright'=>$value->copyright,
          'dominatecolor'=>$value->dominatecolor,
        ]);
      }
    }
      unset($poi);
    $echo.='Poi ok';


    }

    if ($what=='rel') {
    foreach ($relationship->data as $value) {
      $poi=Pois::where('old_id','=',$value->POSTID)->first();
      $tag=Tags::where('old_id','=',$value->TAGID)->first();
      if (isset($tag) and isset($poi)) $tag->pois()->save($poi);


    }
      unset($relationship);
    $echo.='Relationship ok';
    }


    if ($what=='routes') {
      Routes::query()->truncate();
      Schema::enableForeignKeyConstraints();
      foreach ($routes->data as $value) {
        $relationship=explode("|",$value->POINTS);
        $relationship=array_unique($relationship);
        $user=User::firstWhere('login','=',$value->author);
        $name=trim(str_replace(array("'",'"',"&quot;","&laquo;","&raquo;"),'',$value->name));
      if (strlen($name)>0) {
      $tmp=Routes::create([
          'old_id'=>$value->id,
          'name' => $name,
          'user_id' => (is_object($user)?$user->id:8),
          'url' => Str::slug($name, '_'),
          'description'=>$value->description,
          'views'=>$value->views,
          'status'=>$value->show,
          'prim'=>$value->route,
          'links'=>$value->links,
          'duration'=>$value->days,
          'route'=>$value->encoded_route,
        ]);

        foreach ($relationship as $value) {
          if (is_numeric($value)) {
          $poi=Pois::where('old_id','=',$value)->first();
          if (is_object($poi)) $tmp->pois()->save($poi);
          }
        }
      }
    }
      unset($poi);
    $echo.='Routes ok';





    }

    return view('service',compact('echo'));
  }
}
