<?php
namespace App\Http\Controllers;


use App\Models\Pois;
use App\Models\Tags;
use App\Models\User;
use App\Models\Locations;
use App\Models\Routes;
use App\Models\Categories;
use App\Models\PoisComments;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Auth;

class PoisController extends Controller
{

    use \App\Traits\Sortable;

    public function index()
    {
        $pois=Pois::where('status','=',1)->orderby('created_at','DESC')->limit(env('OBJECTS_ON_MAIN_PAGE',6))->get();
        $routes=Routes::where('status','=',1)->limit(env('OBJECTS_ON_MAIN_PAGE',6))->get();
        $tags=Tags::orderby('name','ASC')->get();
        $comments=PoisComments::where('status','=',1)->orderby('updated_at','DESC')->limit(env('OBJECTS_ON_MAIN_PAGE',6))->get();
        return view('home', compact('pois','tags','routes','comments'));
    }

    public function redirectFromOldUrl(Request $request)
    {
        $poi=Pois::where('old_id','=',$request->id)->first();
        return redirect()->route('single-poi',$poi->url);
    }

    protected function get_izbr_array()
    {
        $pois_ids=[];
        if (isset($_COOKIE['izbr'])) {
            $pois_ids=explode("|",$_COOKIE['izbr']);
        }
        $pois_ids = array_diff($pois_ids, array(''));
        return $pois_ids;
    }


    public function izbrannoye(Request $request)
    {
        $sort=$this->sorting_array($request);
        $sorts=$sort[0];
        $beginend=[55.75370903771494,37.61981338262558,55.75,37.61];
        if (isset($_COOKIE['fromto'])) {
          if (isset($_COOKIE['fromto'])) {
            $beginend_from_cookie=explode(",",$_COOKIE['fromto']);
            if (count($beginend_from_cookie)==4) {
              $beginend=$beginend_from_cookie;
            }
          }
        }
        $wherein=$this->get_izbr_array();
        $pois=Pois::where('status','=',1)->whereIn('id', $wherein)->orderby($sort[1],$sort[2])->Paginate(env('OBJECTS_ON_PAGE',15));
        return view('izbrannoye',compact('pois','sorts', 'request', 'beginend'));
    }

    public function my_pois_index()
    {
        $pois=array();
        if (Auth::check()) {
            $pois=Pois::where('user_id','=',auth()->user()->id)->where('status','<>',99)->with('tags')->orderbyDESC('updated_at')->Paginate(env('OBJECTS_ON_PAGE',15));
        }
        return view('secure', compact('pois'));
    }

    public function single_place($url)
    {
        $poi = Cache::remember(
            'single_poi_'.$url,
            env('CACHE_TIME',60),
            function () use ($url) {
                $poi=Pois::where('url', $url)->firstOrFail();
                if (count($poi->locations)==0) {
                    $this->make_pois_geocodes($poi);
                    $poi=Pois::firstWhere('url', $url);
                }
                return $poi;
            }
        );

        $poi->increment('views');
        $poi->links=str_replace("\n",'<br>',$this->make_links_clickable($poi->links));


        if (Auth::check()) {
            if (Auth::user()->email=='andreev-e@mail.ru') {
                $comments=PoisComments::where('status','!=',3);
            }
        } else {
            $comments=PoisComments::where('status','=',1);
        }
        $comments=$comments->where('poi_id','=',$poi->id)->orderby('updated_at','ASC')->Paginate(env('OBJECTS_ON_PAGE',15));

        if (auth()->user()!==null) {
            return view('poi', compact('poi','comments'));
        } else {
            return view('poi', compact('poi','comments'));
        }
    }


    public function update($id, Request $request)
    {
        $poi=Pois::find($id);
        if (auth()->user()->id==$poi->user_id or Auth::user()->email=='andreev-e@mail.ru') {
                $validated = $request->validate([
                    'name'  => 'required|min:5|max:255|unique:pois,name,'.$poi->id,
                    'lat'  => 'required',
                    'lng'  => 'required',
                    'description'  => '',
                ]);

                $images = $request->file('photos');
                if ($request->hasFile('photos')) {
                  foreach ($images as $file) $arr[] =$file->store('public');
                  $image = implode(",", $arr);
                }
                else $image = '';

                if ($validated and Auth::check()) {
                    $poi->name=$request->get('name');
                    $poi->url=Str::slug($request->get('name'), '_');
                    $poi->description=$request->get('description');
                    $poi->category_id=$request->get('category');
                    $poi->prim=$request->get('prim');
                    $poi->route=$request->get('route');
                    $poi->route_o=$request->get('route_o');
                    $poi->video=$request->get('video');
                    $poi->lat=$request->get('lat');
                    $poi->lng=$request->get('lng');
                    $poi->links=$request->get('links');
                    $poi->save();
                    $poi->locations()->detach();
                    $poi->tags()->detach();
                    if (is_array($request->tags)) {
                        foreach ($request->tags as $tag) {
                            $tag=Tags::find($tag);
                            $poi->tags()->save($tag);
                        }
                    }

                    Cache::forget('single_poi_'.$poi->url);
                    return redirect()->route('single-poi',$poi->url);
                }
        } else {
            //auth failed
            return redirect()->route('single-poi', $poi->url);
        }
    }

    public function edit($id, Request $request)
    {
        $poi=Pois::find($id);
        if (auth()->user()->id==$poi->user_id or Auth::user()->email=='andreev-e@mail.ru') {
                  //get - showing form
                  $poi=Pois::find($id);
                  if ($poi->category_id==null) {
                      $poi->category_id=1;
                  }
                  $checked_tags=array();
                  foreach ($poi->tags as $tag)
                  {
                      $checked_tags[]=$tag->id;
                  }
                  return view('poi_edit', compact('poi','checked_tags'));

        } else {
            //auth failed
            return redirect()->route('single-poi', $poi->url);
        }
    }

    public function location(Request $request, $location_url='')
    {
        return $this->location_category_tag($request, $location_url, '', '');
    }

    public function category(Request $request, $category_url='', $location_url='')
    {
        return $this->location_category_tag($request, $location_url, $category_url, '');
    }

    public function tag(Request $request, $tag_url='', $location_url='')
    {
        return $this->location_category_tag($request, $location_url, '', $tag_url);
    }

    protected function location_category_tag(Request $request, $location_url='', $category_url='', $tag_url='')
    {
          $sort=$this->sorting_array($request);
          $sorts=$sort[0];

          $breadcrumbs=null;
          $locations=null;
          $current_location=null;
          $current_category=null;
          $current_tag=null;
          $subregions=null;
          $categories=[];
          $tags=[];
          $h1='??????????????????????????????????????????';

          if ($location_url!='') {
              $current_location=Locations::Where('url', $location_url)->firstOrFail();
          }
          if ($category_url!='') {
              $current_category=Categories::Where('url', $category_url)->firstOrFail();
          }
          if ($tag_url!='') {
              $current_tag=Tags::Where('url', $tag_url)->firstOrFail();
          }

          $wherein=[];
          $wherein_tag=[];
          $wherein_loc=[];
          $wherein_category=[];

          if (is_object($current_category))  {
              $h1=$current_category->name;
              $poi_ids=\DB::table('pois')->where('category_id',"=",$current_category->id)->get('id');
              foreach ($poi_ids as $poi_id) {
                  $wherein_category[]=$poi_id->id;
              }
          }

          if (is_object($current_tag))  {
              $h1=$current_tag->name_rod;
              $locations=Locations::where('type','=','country')->orderby('name','ASC')->get();
              $poi_ids=\DB::table('pois_tags')->where('tags_id',"=",$current_tag->id)->get('pois_id');
              foreach ($poi_ids as $poi_id) {
                  $wherein_tag[]=$poi_id->pois_id;
              }
          }

          if (is_object($current_location)) {
              if ($current_location->name_rod!='') {
                  $h1.=' '.$current_location->name_rod;
              } else {
                  $h1.=': '.$current_location->name;
              }
              $breadcrumbs=$this->get_parent_location($current_location->parent);
              $subregions=Locations::where('parent', $current_location->id)->orderby('count','DESC')->get();
              $poi_ids=\DB::table('pois_locations')->where('locations_id',"=",$current_location->id)->get('pois_id');
              foreach ($poi_ids as $poi_id) {
                  $wherein_loc[]=$poi_id->pois_id;
              }
          } else {
              $h1.=' ????????';
          }

          if (!empty($wherein_loc) and !empty($wherein_tag)) {
              $wherein=array_intersect($wherein_loc,$wherein_tag);
          } elseif (!empty($wherein_loc) and !empty($wherein_category)) {
              $wherein=array_intersect($wherein_loc,$wherein_category);
          } elseif (!empty($wherein_category) and !empty($wherein_tag)) {
              $wherein=array_intersect($wherein_category,$wherein_tag);
          } else {
              $wherein=array_merge($wherein_loc,$wherein_tag,$wherein_category);
          }

          if (!empty($wherein)) {
              $pois=Pois::where('status','=',1)->whereIn('id', $wherein);
          } else {
              $locations=Locations::where('type','=','country')->orderby('name','ASC')->get();
              if (!is_object($current_tag)) {
                  $tags=Tags::orderby('name','ASC')->get();
              }
              $pois=Pois::where('status','=',1);
          }
          $pois=$pois->orderby($sort[1],$sort[2])->Paginate(env('OBJECTS_ON_PAGE',15));

          if (!is_object($current_tag) and !empty($wherein)) {
              $tags=\DB::table('tags')
              ->join('pois_tags', 'pois_tags.tags_id', '=', 'tags.id')
              ->whereIn('pois_tags.pois_id', $wherein)->groupBy('tags.id')->get('tags.*');
          }

          if (!is_object($current_category) & !is_object($current_tag)) {
              $categories=\DB::table('pois')->leftjoin('categories', 'categories.id', '=', 'pois.category_id')
              ->whereIn('pois.id', $wherein)->where('categories.id',"<>", null)->groupBy('categories.id')->get('categories.*');
          }

          $meta['h1']=$h1;
          return view('catalog', compact('pois','subregions','current_location','locations','current_category','categories','current_tag','tags','breadcrumbs','sorts', 'request', 'meta'));
    }

    private function get_parent_location($parent)
    {
        static $out = [];
        $loc=Locations::firstWhere('id', $parent);
        if ($loc) {
            $out[]=array('name'=>$loc->name,'url'=>$loc->url);
            if ($loc->type!='country' and count($out)<10) {
                $this->get_parent_location($loc->parent);
            }
        }
        return array_reverse($out);
    }

    public function user($url, Request $request)
    {
        $sort=$this->sorting_array($request);
        $sorts=$sort[0];
        $user=User::where('login', $url)->firstOrFail();
        $pois=$user->pois()->where('status','=',1)->orderby($sort[1],$sort[2])->paginate(env('OBJECTS_ON_PAGE',15));
        return view('user', compact('pois','user','sorts','request'));
    }

    public function routePointsJson(Request $request)
    {
        if ($request->get('coords')!==NULL and $request->get('otdalenie')!==NULL) {
            $steps=explode("!",$request->get('coords'));
            $radius=(float)$request->get('otdalenie')/200;
            $pois=Pois::orderby('views','DESC');
            foreach ($steps as $step) {
                $step=explode(",",$step);
                if (count($step)==2) {
                    $pois->orwhere([
                      ['lat',"<",(float)$step[0]+$radius],
                      ['lat',">",(float)$step[0]-$radius],
                      ['lng',"<",(float)$step[1]+$radius],
                      ['lng',">",(float)$step[1]-$radius]
                    ]);
                }
            }

            $pois->where(function($query) {
                $query->Where('status', '=', 1);
            });

            $pois=$pois->limit(1000)->get();

           $responce=[];
           foreach ($pois as $poi) {
               $marker='marker_1_.png';
               $marker='marker_'.$poi->category_id.'_.png';
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
    }

    public function poiJson(Request $request)
    {
        if ($request->get('mne')!==NULL and $request->get('msw')!==NULL) {
            [$nelat,$nelng] = explode(',',$request->get('mne'));
            [$swlat,$swlng] = explode(',',$request->get('msw'));

            $pois=Pois::where([
               ['status','=',1],
               ['lat', '>=', $swlat],
               ['lat', '<=', $nelat],
               ['lng', '<=', $nelng],
               ['lng', '>=', $swlng]
            ]);
            if (($request->get('tag'))!="") {
                $wherein_tag=[];
                $poi_ids=\DB::table('pois_tags')->where('tags_id',"=",$request->get('tag'))->get('pois_id');
                foreach ($poi_ids as $poi_id) {
                   $wherein_tag[]=$poi_id->pois_id;
                }
                $pois=$pois->whereIn('pois.id', $wherein_tag);
            }

            if (($request->get('cat'))!="") {
                $pois=$pois->where('category_id', '=', $request->get('cat'));
            }

            $pois=$pois->with('tags')
            ->orderby(\DB::raw("ABS(`lat`-'".(($nelat+$swlat)/2)."')+ABS(`lng`-'".(($nelng+$swlng)/2)."')"),'ASC')
            ->limit(1000)->get();

        } else {
            $pois=null;
        }

        $responce=[];
        foreach ($pois as $poi) {
            $marker='marker_1_.png';
            $marker='marker_'.$poi->category_id.'_.png';
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

    public static function make_pois_geocodes($poi)
    {

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

        if (is_object($file)) {
            if (isset($file->response->GeoObjectCollection->featureMember)) {
                $file=array_reverse($file->response->GeoObjectCollection->featureMember);
            }
        } else {
            $file=null;
        }
        $prev_loc=0;
        $exclude_kinds = array('street','house','area','district','vegetation');
        $prev_loc_name="";


        if (!isset($file->statusCode)) {
            foreach ($file as $location) {
                if ($location->GeoObject->name==$prev_loc_name) {
                   continue;
                }
                $latlng=explode(" ",$location->GeoObject->Point->pos);

                if (Locations::where('name', '=', $location->GeoObject->name)->count() == 0)  {
                    if (!in_array($location->GeoObject->metaDataProperty->GeocoderMetaData->kind,$exclude_kinds)) {
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
                } else {
                    $new_loc=Locations::where('name', '=', $location->GeoObject->name)->first();
                    $new_loc->pois()->save($poi);
                    $prev_loc=$new_loc->id;
                    $prev_loc_name=$new_loc->name;
                }
            }
        }
    }

    public function create(Request $request)
    {
        $files=[];
        if ($request->session()->get('files')!='') $files=$request->session()->get('files');
        return view('poi_add',compact('files'));
    }

    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name'  => 'required|min:5|max:255|unique:pois',
                'lat'  => 'required',
                'lng'  => 'required',
                'description'  => '',
                'category'  => 'required',
            ]);

            $images = $request->file('photos');
            if ($request->hasFile('photos')) {
                foreach ($images as $file) {
                    $arr[] =$file->store('public');
                }
                $image = implode(",", $arr);
            } else {
                $image = '';
            }

            if ($validated and Auth::check()) {
                $new_poi=Pois::create([
                    'name' => $request->get('name'),
                    'url'=> Str::slug($request->get('name'), '_'),
                    'user_id'=>auth()->user()->id,
                    'status'=>1,
                    'description'=>$request->get('description'),
                    'category'=>$request->get('category'),
                    'prim'=>$request->get('prim'),
                    'links'=>$request->get('links'),
                    'route'=>$request->get('route'),
                    'route_o'=>$request->get('route_o'),
                    'video'=>$request->get('video'),
                    'lat'=>$request->get('lat'),
                    'lng'=>$request->get('lng'),
                ]);

                if (is_array($request->tags)) foreach ($request->tags as $tag) {
                    $tag=Tags::find($tag);
                    $new_poi->tags()->save($tag);
                }
            }
            return redirect()->route('my_pois');
        }
    }


    public function hide($id)
    {
        if (auth()->user()!==null) {
            $poi = Pois::find($id);
            $poi->status=0;
            if ($poi->user_id==auth()->user()->id) {
                $poi->save();
            }
        }
        return redirect()->back();
    }

    public function publish($id)
    {
        if (auth()->user()!==null) {
            $poi = Pois::find($id);
            $poi->status=1;
            if ($poi->user_id==auth()->user()->id) {
                $poi->save();
            }
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        if (auth()->user()!==null) {
            $poi = Pois::find($id);
            $poi->status=99;
            if ($poi->user_id==auth()->user()->id) {
                $poi->save();
            }
        }
        return redirect()->back();
    }

}
