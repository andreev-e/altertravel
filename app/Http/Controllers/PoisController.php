<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pois;
use App\Models\Tags;
use App\Models\User;
use App\Models\Locations;
use Illuminate\Support\Str;
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
      $pois=Pois::where('status','=',1)->limit(15)->orderby('created_at','desc')->get();
      return view('catalog', compact('pois'));
  }
  public function popular()
  {
      $pois=Pois::where('status','=',1)->limit(15)->orderby('views','desc')->get();
      return view('catalog', compact('pois'));
  }
    public function catalog()
    {
        $pois=Pois::where('status','=',1)->get();
        return view('catalog', compact('pois'));
    }
    public function secure_index()
    {
        $pois=array();
        if (Auth::check()) $pois=Pois::where('user_id','=',auth()->user()->id)->where('status','<>',99)->get();
        return view('catalog_secure', compact('pois'));
    }
    public function single($url)
    {
        $poi=Pois::firstWhere('url', $url);
        $poi->increment('views');
        if (count($poi->locations)==0) { PoisController::make_pois_geocodes($poi);$poi=Pois::firstWhere('url', $url);}
        $poi->photos=explode(",",$poi->photos);
        if (auth()->user()!==null) return view('poi', compact('poi'));
        else return view('poi', compact('poi'));
    }
    public function single_edit($url)
    {
        $poi=Pois::firstWhere('url', $url);
        if (auth()->user()!==null and auth()->user()->id==$poi->owner) return view('poi_secure', compact('poi'));
        else return redirect()->route('single-poi', $poi->url);
    }

    public function location($url)
    {
        $location=Locations::firstWhere('url', $url);
        $pois=$location->pois()->where('status','=',1)->get();
        return view('location', compact('pois'));
    }

    public function tag($url)
    {
      $tag=Tags::firstWhere('url', $url);
        $pois=$tag->pois()->where('status','=',1)->get();
        return view('tag', compact('pois'));
    }
    public function user($url)
    {
        $user=User::firstWhere('login', $url);
        $pois=$user->pois()->where('status','=',1)->get();
        return view('user', compact('pois'));
    }
////////////////actions////////////////////////

function GetBetween($content,$start,$end){
	    $r = explode($start, $content);
	    if (isset($r[1])){
	        $r = explode($end, $r[1]);
	        return $r[0];
	    }
	    return '';
	}

public function make_pois_geocodes($poi){

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
         'lat'=>$latlng[0],
         'lng'=>$latlng[1],
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
        'title'  => 'required|min:5|max:255',
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
        'name' => $request->get('title'),
        'url'=> Str::slug($request->get('title'), '_'),
        'user_id'=>auth()->user()->id,
        'status'=>1,
        'description'=>$request->get('description'),
        'category'=>$request->get('category'),
        'prim'=>$request->get('prim'),
        'route'=>$request->get('route'),
        'video'=>$request->get('video'),
        'lat'=>$request->get('lat'),
        'lng'=>$request->get('lng'),
        'photos'=>$image,

      ]);
    }




    return redirect()->route('secure');

}
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
