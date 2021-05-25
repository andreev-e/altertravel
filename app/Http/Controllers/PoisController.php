<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pois;
use App\Models\Tags;
use App\Models\User;
use App\Models\Locations;
use App\Models\Categories;
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
      $tags=Tags::orderby('name','ASC')->get();
      return view('home', compact('pois','tags'));
  }

  public function new()
  {
      $pois=Pois::where('status','=',1)->orderby('created_at','desc')->Paginate(15);
      return view('catalog', compact('pois'));
  }

  public function izbrannoye()
  {
      return view('izbrannoye');
  }

    public function secure_index()
    {
        $pois=array();
        if (Auth::check()) $pois=Pois::where('user_id','=',auth()->user()->id)->where('status','<>',99)->with('tags')->orderbyDESC('updated_at')->Paginate(10);
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
         $pois=Pois::where([
           ['status','=',1],
           ['lat', '>=', $swlat],
           ['lat', '<=', $nelat],
           ['lng', '<=', $nelng],
           ['lng', '>=', $swlng]
         ])->with('tags')->with('user')->with('category')->orderby('views','DESC')->limit(500)->get();

      }
        else $pois=null;

        $responce=[];
        foreach ($pois as $poi) {
          $marker='marker_1_.png';
          if (is_object($poi->category)) $marker='marker_'.$poi->category->id.'_.png';
          $point['lat']=$poi->lat;
          $point['lng']=$poi->lng;
          $point['name']=$poi->name;
          $point['tags']=$poi->tags;
          $point['url']=$poi->url;
          $point['icon']=$marker;
          $point['photo']=$poi->photo;
          $responce[]=$point;
        }
        return json_encode($responce);
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
    $file = curl_exec($curl);
}
$file=json_decode($file);
$file=array_reverse($file->response->GeoObjectCollection->featureMember);
$prev_loc=0;
$exclude_kinds = array('street','house','area','district');
$prev_loc_name="";

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
