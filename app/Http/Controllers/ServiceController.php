<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pois;
use App\Models\Clusters;


class ServiceController extends Controller
{

  public function clusterize() {
    $echo='<h2>Построение кластеров</h2>';

    return view('service',compact('echo'));
  }
  public function import() {
    $echo='<h2>Импорт</h2>';

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


    foreach ($users->data as $value) {
      if ($value->publications>0 and strlen($value->email)>0)
      $tmp=User::firstOrCreate([
        'name' => $value->firstname." ".$value->lastname,
        'email' => $value->email,
        'login' => $value->username,
        'site' => $value->homepage,
        'about' => $value->about,
        'old_password' => $value->password,
        'password' => Hash::make($value->password),
        'publications' => $value->publications,
      ]);
    }

    unset($users);
    $echo.='User ok';

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

    foreach ($poi->data as $value) {
      $user=User::where('login','=',$value->author)->first();
      if (isset($user)) $user_id=$user->id; else $user_id=8;
      if ($value->lat!=0 and $value->lng!=0) $tmp=Pois::create([
        'old_id'=>$value->id,
        'name' => $value->name,
        'user_id' => $user_id,
        'url' => Str::slug($value->name, '_'),
        'lat' =>$value->lat,
        'lng' =>$value->lng,
        'category'=>$value->type,
        'description'=>$value->description,
        'route'=>$value->route,
        'category'=>$value->type,
        'views'=>$value->views,
        'status'=>$value->show,
        'prim'=>$value->addon,
        'video'=>$value->ytb,
        'links'=>$value->links,
        'copyright'=>$value->copyright,
        'dominatecolor'=>$value->dominatecolor,
      ]);
    }
      unset($poi);
    $echo.='Poi ok';


    foreach ($relationship->data as $value) {
      $poi=Pois::where('old_id','=',$value->POSTID)->first();
      $tag=Tags::where('old_id','=',$value->TAGID)->first();
      if (isset($tag) and isset($poi)) $tag->pois()->save($poi);


    }
      unset($relationship);
    $echo.='Relationship ok';

    return view('service',compact('echo'));
  }
}
