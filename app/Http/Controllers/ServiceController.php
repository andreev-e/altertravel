<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pois;
use App\Models\User;
use App\Models\Locations;
use App\Models\Tags;
use App\Models\Categories;
use App\Models\Routes;
use App\Models\PoisComments;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Auth;

class ServiceController extends Controller
{


  public function import($what) {
    if (Auth::check()) {
      $echo='Импорт:<br>';

      if ($what=='photo_objects') {

        ///ok
        foreach (Pois::where('photo','=','')->limit(1000)->get() as $poi) {
            $image=false;
            $i=0;
            $directory="/pois/".$poi->id."/";
            $filename=$poi->old_id.".jpg";
            $alter_old_url="https://altertravel.ru/images/".$filename;
            $image=@file_get_contents ($alter_old_url);
            if ($image)  Storage::put("/public/".$directory.'__'.$filename, $image);
            do {
              $i++;
              $filename=$i.".jpg";
              $alter_old_url="https://altertravel.ru/images/".$poi->old_id."/".$filename;
              $image=@file_get_contents ($alter_old_url);
              if ($image) Storage::put("/public/".$directory.$filename, $image);
            } while ($image);

        $filelist=Storage::files("/public/".$directory);
        if (count($filelist)) {
          $photos=array();
          foreach ($filelist as $file) {
            $file=explode("/",$file);
            $name=array_pop($file);
            $lastdir=array_pop($file);
            $photos[]=$lastdir."/".$name;
          }

        $poi->photos=implode(",",$photos);
        $poi->photo=array_pop($photos);
        $poi->save();
        }

        }
        $echo.="photo_objects import ok";
      }


      if ($what=='photo_routes') {

        foreach (Routes::where('photo','=','')->get() as $route) {
            $image=false;
            $i=0;
            $directory="/routes/".$route->id."/";
            do {
              $i++;

            $filename=$i.".jpg";
            $alter_old_url="https://altertravel.ru/routes/".$route->old_id."/".$filename;
            $image=@file_get_contents ($alter_old_url);

            if ($image)  {
                $echo.="есть картинка";
                Storage::put("/public/".$directory.$filename, $image);

            }
            else  $echo.="нет";
            $echo.=$alter_old_url."<br>";

          } while ($image);

        $filelist=Storage::files($directory);
        if (count($filelist)) {
        $route->photo=$filelist[0];
        $filelist=implode(",",$filelist);
        $route->photos=$filelist;
        $route->save();
        }



        }
        $echo.="photo_routes import ok";
      }


            if ($what=='photo_avatars') {

              foreach (User::where('avatar_original','=','')->limit(40)->get() as $user) {
                  $image=false;
                  $i=0;
                  $directory="/avatars/";


                  $filename=$user->login.".jpg";
                  $alter_old_url="https://altertravel.ru/authors/".$filename;
                  $image=@file_get_contents ($alter_old_url);

                  if ($image)  {
                      $echo.="есть картинка";
                      Storage::put("/public/".$directory.$filename, $image);
                      $user->avatar_original=$directory.$filename;
                      $user->avatar=$directory.$filename;

                  }
                  else  {
                    $user->avatar_original="-";
                    $user->avatar="-";
                  }
                  $user->save();

              }
              $echo.="photo_avatars import ok";
            }


      if ($what=='locating') {
        $i=0;
        foreach (Pois::lazy() as $poi) {
          $i++;
          if ($poi->locations()->count()==0) (new PoisController)::make_pois_geocodes($poi);
          if ($i>3) dd('Хватит, Забанят ключ!');
        }


      }

        if ($what=='comments_fix') {


          foreach (PoisComments::where('user_id','=',null)->lazy() as $comment) {
            $user=User::firstWhere('email','=',$comment->email);
            if (is_object($user)) {
                        $comment->user_id=$user->id;
                        $comment->save();
            }

          }


          return view('service',compact('echo'));
        }



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



      if ($what=='edits') {
          foreach ($edits->data as $value) {

            if ($value->APPROVED==1)
            {
              $poi=Pois::firstWhere('old_id','=',$value->POSTID);
              if (is_object($poi)) {
                if ($value->SECTION=='links_text') {$poi->links=$value->NEWTEXT; $poi->save(); $echo.=$poi->id."<br>";}
                elseif ($value->SECTION=='route_o_text') {$poi->route_o=$value->NEWTEXT; $poi->save(); $echo.=$poi->id."<br>";}
                elseif ($value->SECTION=='route_text') {$poi->route=$value->NEWTEXT; $poi->save(); $echo.=$poi->id."<br>";}
                elseif ($value->SECTION=='addon_text') {$poi->prim=$value->NEWTEXT; $poi->save(); $echo.=$poi->id."<br>";}
                elseif ($value->SECTION=='interesting_text') {$poi->description=$value->NEWTEXT; $poi->save(); $echo.=$poi->id."<br>";}
              }
            }
            else if($value->NEWTEXT!='') dd($value);

        }
      }

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

      if ($what=='slovar') {
        foreach ($tags->data as $value) {
          $location=Locations::where('name','=',$value->NAME)->first();
          if(is_object($location)) {
          if ($value->flag and empty($location->flag)) $location->flag=$value->flag;
          if ($value->scale and empty($location->scale)) $location->scale=$value->scale;
          if ($value->lng and empty($location->lng)) $location->lng=$value->lng;
          if ($value->lat and empty($location->lat)) $location->lat=$value->lat;
          if ($value->COUNT and empty($location->count)) $location->count=$value->COUNT;
          if ($value->NAME_DAT_ED and empty($location->flag)) $location->name_dat=$value->NAME_DAT_ED;
          if ($value->NAME_ROD_ED and empty($location->name_rod)) $location->name_rod=$value->NAME_ROD_ED;
          if ($value->NAME_PREDLOZH_ED and empty($location->name_pred)) $location->name_pred=$value->NAME_PREDLOZH_ED;
          $location->save();
          }
        }
        $echo.='Slovar ok';
      }

      if ($what=='comments') {
        PoisComments::query()->truncate();
        Schema::enableForeignKeyConstraints();
        foreach ($comments->data as $value) {

          $poi=Pois::where('old_id','=',$value->backlink)->first();
          $user=User::where('login','=',$value->name)->first();

          if ($value->time>0) $time=date('Y-m-d H:i:s',$value->time); else $time=time();

          if (is_object($user)) $user=$user->id; else $user=null;

          //if (is_object($poi) and $value->approved==1)
          if (is_object($poi))

          $tmp=PoisComments::create([
              'poi_id'=>$poi->id,
              'email'=>$value->email,
              'user_id'=>$user,
              'comment'=>$value->comment,
              'created_at'=>$time,
              'updated_at'=>$time,
              'status'=>$value->approved,
            ]);




        }
      }




      return view('service',compact('echo'));
    }
  }
}
