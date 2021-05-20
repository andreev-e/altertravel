<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pois;
use App\Models\Tags;
use App\Models\User;
use App\Models\Locations;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Image;
use Storage;
use Auth;

class PoisController extends Controller
{
  public function index()
  {
      $pois=Pois::where('status','=',1)->limit(6)->get();
      return view('home', compact('pois'));
  }

  public function new()
  {
      $pois=Pois::where('status','=',1)->orderby('created_at','desc')->Paginate(15);
      return view('catalog', compact('pois'));
  }

  public function popular()
  {
      $pois=Pois::where('status','=',1)->orderby('views','desc')->Paginate(15);
      return view('catalog', compact('pois'));
  }

  public function izbrannoye()
  {
      return view('izbrannoye');
  }

    public function secure_index()
    {
        $pois=array();
        if (Auth::check()) $pois=Pois::where('user_id','=',auth()->user()->id)->where('status','<>',99)->orderbyDESC('updated_at')->Paginate(10);
        return view('secure', compact('pois'));
    }
    public function single_place($url)
    {
        $poi = Cache::remember('single_poi_'.$url, 20, function () use ($url) {
        $poi=Pois::where('url', $url)->firstOrFail();
        if (count($poi->locations)==0) {
          $this->make_pois_geocodes($poi);
          $poi=Pois::firstWhere('url', $url);
        }
        $poi->photos=explode(",",$poi->photos);
        return $poi;
        });
        $poi->increment('views');
        if (auth()->user()!==null) return view('poi', compact('poi'));
        else return view('poi', compact('poi'));
    }
    public function single_edit($id,Request $request)
    {
        $poi=Pois::find($id);
        if (auth()->user()!==null and auth()->user()->id==$poi->user_id) {

            if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name'  => 'required|min:5|max:255|unique:pois,name,'.$poi->id,
                'lat'  => 'required',
                'lng'  => 'required',
                'description'  => '',
            ]);
            $images = $request->file('photos');
            if ($request->hasFile('photos')) :
            foreach ($images as $file):
            $arr[] =$file->store('public');
            endforeach;
            $image = implode(",", $arr);
            else:
                    $image = '';
            endif;

            if ($validated and Auth::check()) {

                $poi->name=$request->get('name');
                $poi->url=Str::slug($request->get('name'), '_');
                $poi->description=$request->get('description');
                $poi->category=$request->get('category');
                $poi->prim=$request->get('prim');
                $poi->route=$request->get('route');
                $poi->route_o=$request->get('route_o');
                $poi->video=$request->get('video');
                $poi->lat=$request->get('lat');
                $poi->lng=$request->get('lng');
                $poi->photos=$image;
                $poi->save();

                $poi->locations()->detach();
                $poi->tags()->detach();
                if (is_array($request->tags)) foreach ($request->tags as $tag) {
                  $tag=Tags::find($tag);
                  $poi->tags()->save($tag);
                }

                Cache::forget('single_poi_'.$poi->url);
                return redirect()->route('secure');
            }

        }
        else {
          //not post - edit form

          $poi=Pois::find($id);
          $checked_tags=array();
          foreach ($poi->tags as $tag) {
            $checked_tags[]=$tag->id;
          }


          return view('poi_edit', compact('poi','checked_tags'));
        }



      }
        else return redirect()->route('single-poi', $poi->url);
    }

    public function location($url)
    {
        $location=Locations::firstWhere('url', $url);
        $breadcrumbs=$this->get_parent_location($location->parent);
        $pois=$location->pois()->where('status','=',1)->get();
        return view('location', compact('pois','location','breadcrumbs'));
    }


    private function get_parent_location($parent) {
      static $out = [];
      $loc=Locations::firstWhere('id', $parent);
      if ($loc) {$out[]=array('name'=>$loc->name,'url'=>$loc->url);
      if ($loc->type!='country' and count($out)<10) $this->get_parent_location($loc->parent);
      }
      return array_reverse($out);
    }

    public function tag($url)
    {
      $tag=Tags::where('url', $url)->firstOrFail();;
        $pois=$tag->pois()->where('status','=',1)->get();
        return view('tag', compact('pois','tag'));
    }
    public function user($url)
    {
        $user=User::where('login', $url)->firstOrFail();
        $pois=$user->pois()->where('status','=',1)->get();
        return view('user', compact('pois'));
    }
    public function poi_json(Request $request) {
        if ($request->get('mne')!==NULL and $request->get('msw')!==NULL) {
        list($nelat,$nelng) = explode(',',$request->get('mne'));
        list($swlat,$swlng) = explode(',',$request->get('msw'));
        $latreduce=abs($nelat-$swlat)/20;
        $lngreduce=abs($swlng-$nelng)/40;
         $pois=Pois::where([
           ['status','=',1],
           ['lat', '>=', $swlat+$latreduce],
           ['lat', '<=', $nelat-$latreduce],
           ['lng', '<=', $nelng-$lngreduce],
           ['lng', '>=', $swlng+$lngreduce]
         ])->orderby('views','DESC')->limit(100)->get();
      }
        else $pois=Pois::where('status','=',1)->orderby('views','DESC')->limit(100)->get();
        return json_encode($pois);
      }


      public function import() {
        $echo='';

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

        /*
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
          //dd($value);
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
        */

        foreach ($relationship->data as $value) {
          //dd($value);
          $poi=Pois::where('old_id','=',$value->POSTID)->first();
          $tag=Tags::where('old_id','=',$value->TAGID)->first();
          if (isset($tag) and isset($poi)) $tag->pois()->save($poi);


        }
          unset($relationship);
        $echo.='Relationship ok';


        //dd($all);
        return view('import',compact('echo'));
      }
////////////////actions////////////////////////


private function make_pois_geocodes($poi){

$url="https://geocode-maps.yandex.ru/1.x/?format=json&geocode=$poi->lng,$poi->lat&apikey=7483ad1f-f61c-489b-a4e5-815eb06d5961" ;
if ($curl = curl_init()) {
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, FALSE);
	//curl_setopt($curl, CURLOPT_COOKIE, "z=s:l:35.233:1364481482299; yandexuid=2371188181351687295; fuid01=50911c7f2786d98d.SDYHoFkoH5ZTRw0tjuM36403JpKEEfLpKLXPX3WW8cC7GsNOhJ_Ee7zP1Q1nQRehrlwPp-FTMoIN7QWgMgJY39eeV7QbZIfTbaROTwXI-PtE4WQlrzMrD6x8qBpwPoZ0; yabs-frequency=/4/U04F091RNL6O94nH/_R016fSI9m000R1z1Y-N4Zm0004F_08xbn8d0001u6uB3fGIGO9S03YN4YS0006aVGPFan8y0001Ci886vCI9m000LTV0YwF4Zm0007-xmCpZn8d0001Qr025umI9vlN21UC4ZnjAGiLYn8d0001Fiy6DdyIF0000P7N1Iry4cSydGGjV19d2G0897SIF0000QtM1L5t4Zm00073omeJTn9S0002gza2Hd0IRWIG1Zbl4Zm0004GL0GpRn8y00015OiB1MCIF0000MT31JvV4bm000AWZWiLMn8y00019KaB4biIF0000HE42n1R4Zm0005YEmOyLn9S0002Wo851LSIF0000Sm_1ZLN4bm0009eAGLFLn9S0002Oyi6C5CIF0000LZ922TJ4dm000C01PG0Fn9y0003IT882piIF0000LY002ax4dm000CRemP1En9y0003/; my=YycCAAEoBIDV4ABOkuAAUJXgAYzrNgEBAA==; L=bUApUVdLdFhFYENeUEJnYVcNW2BBRWB+fEoXNRReWBoEPwsFUjVRIE4DADlbOhAcXggsJA0gUQ0cRCMuUR5cdQ==.1365083248.9679.218643.dcccda9a0c27c562ee59a2af1cfcc2a8; yp=1680447863.sp.nd%3A50%3And%3A50%3And%3A50%3And%3A50%3And%3A50%3Alang%3A%21%3Aisnp%3A0%3Aprs%3A0; spravka=dD0xMzY0OTc2NDYxO2k9ODcuMjM4LjEwMC40NDt1PTEzNjQ5NzY0NjEzMDY3Njk1NDQ7aD1iMTU2NjdhOWRjZDI4N2MyODc0Y2RmM2Q5OTY1NTExZA==; ys=; Cookie_check=1; balance_cookie=eJwtyr0KwjAQAOA8jZsJCFYQihRdugluLiUk1^Ygf9xdWvv2Orh9w0ewYMmKmmICqVaCCiKVr8Z4JHCid5s9fDQ1Q7/LAgTeJItZd6dxfr2H4bFj9zxL1TXeXPI9h7Ldbap8aLEsmPsW5chAKzpQBPP094ReXb71lC8W"); // куки
    $file = curl_exec($curl);
}
$file=json_decode($file);
$file=array_reverse($file->response->GeoObjectCollection->featureMember);
$prev_loc=0;
$exclude_kinds = array('street','house');
$prev_loc_name="";
//dd($file);
foreach ($file as $location) {

   if ($location->GeoObject->name==$prev_loc_name) continue;
   $latlng=explode(" ",$location->GeoObject->Point->pos);

   if (Locations::where('name', '=', $location->GeoObject->name)->count() == 0)  {
     //Создаем новую локацию по названию
     if(!in_array($location->GeoObject->metaDataProperty->GeocoderMetaData->kind,$exclude_kinds)) {

     $new_loc=Locations::create([
         'name'=>$location->GeoObject->name,
         'url'=>Str::slug($location->GeoObject->name, '_'),
         'parent'=>$prev_loc,
         'type'=>0,
         'lat'=>$latlng[1],
         'lng'=>$latlng[0],
         'type'=>$location->GeoObject->metaDataProperty->GeocoderMetaData->kind,
         ]);
         $new_loc->pois()->save($poi);
         $prev_loc=$new_loc->id;
         $prev_loc_name=$new_loc->name;
       }

       }
       else  {
         $new_loc=Locations::where('name', '=', $location->GeoObject->name)->first(); //берем существующую локацию по названию
         $new_loc->pois()->save($poi);
         $prev_loc=$new_loc->id;
         $prev_loc_name=$new_loc->name;
       }
}
   }





public function store(Request $request)
{
    // выполнять код, если есть POST-запрос
    if ($request->isMethod('post')) {

    // валидация формы
    $validated = $request->validate([
        'name'  => 'required|min:5|max:255|unique:pois',
        'lat'  => 'required',
        'lng'  => 'required',
        'description'  => '',
    ]);

    $images = $request->file('photos');
    if ($request->hasFile('photos')) :
    foreach ($images as $file):
    $arr[] =$file->store('public');
    endforeach;
    $image = implode(",", $arr);
    else:
            $image = '';
    endif;
    if ($validated and Auth::check()) {


      $new_poi=Pois::create([
        'name' => $request->get('name'),
        'url'=> Str::slug($request->get('name'), '_'),
        'user_id'=>auth()->user()->id,
        'status'=>1,
        'description'=>$request->get('description'),
        'category'=>$request->get('category'),
        'prim'=>$request->get('prim'),
        'route'=>$request->get('route'),
        'route_o'=>$request->get('route_o'),
        'video'=>$request->get('video'),
        'lat'=>$request->get('lat'),
        'lng'=>$request->get('lng'),
        'photos'=>$image,
      ]);

      if (is_array($request->tags)) foreach ($request->tags as $tag) {
        $tag=Tags::find($tag);
        $new_poi->tags()->save($tag);
      }

    }




    return redirect()->route('secure');

} else return view('poi_add');
}


    public function hide($id)
    {
        if (auth()->user()!==null) {
        $poi = Pois::find($id);
        $poi->status=0;
        if ($poi->user_id==auth()->user()->id) $poi->save();
        }
        return redirect()->route('secure');
    }

    public function show($id)
    {
        if (auth()->user()!==null) {
        $poi = Pois::find($id);
        $poi->status=1;
        if ($poi->user_id==auth()->user()->id) $poi->save();
        }
        return redirect()->route('secure');
    }

    public function delete($id)
    {
        if (auth()->user()!==null) {
        $poi = Pois::find($id);
        $poi->status=99;
        if ($poi->user_id==auth()->user()->id) $poi->save();
        }
        return redirect()->route('secure');
    }
}
