@push('scripts')

<script type="text/javascript">
var icon = "/i/map_marker.png";
var json_url = "{{ route('poi_json') }}";
var infowindow = new google.maps.InfoWindow();
var markersArray = [];
var first=true; //
window.onload = function()
{
map = new google.maps.Map(document.getElementById("map"),
{
@if (isset($location))
center: new google.maps.LatLng({{$location->lat}}, {{$location->lng}}), zoom: {{$location->scale}},
@else
center: new google.maps.LatLng(28.425261, 74.771668), zoom: 2,
@endif
gestureHandling: 'greedy',
});

function bindInfoWindow(marker, map, infowindow, strDescription) {
google.maps.event.addListener(marker, 'click', function () {
    infowindow.setContent(strDescription);
    infowindow.open(map, marker);
});
}
function clearOverlays() {
if (markersArray) {
for (i in markersArray) {
  markersArray[i].setMap(null);
}
}
}

function clearOverlays() {
for (var i = 0; i < markersArray.length; i++ ) {
markersArray[i].setMap(null);
}
markersArray.length = 0;
}

function loadPointsfomJSON() {
var i=0;
clearOverlays();
var bounds = map.getBounds();
var url=json_url+'?mne=' + bounds.getNorthEast().toUrlValue() + '&msw=' + bounds.getSouthWest().toUrlValue();

$.getJSON(url, function(json) {
$('#shown_on_map').empty();
$.each(json, function (key, data) {
var latLng = new google.maps.LatLng(data.lat, data.lng);
var marker = new google.maps.Marker({
    position: latLng,
    map: map,
    icon: icon,
    title: data.name
});
markersArray.push(marker);
var details = "<p>"+data.name+"<br><a target='_blank' href='/place/"+data.url+"'>подробнее</a>";
bindInfoWindow(marker, map, infowindow, details);


});
});
}


google.maps.event.addListener(map, 'dragend', function() {
     loadPointsfomJSON();
  });
google.maps.event.addListener(map, 'zoom_changed', function() {
     loadPointsfomJSON();
  });
var first=true;
google.maps.event.addListener(map, 'idle', function() {
              var bounds =  map.getBounds();
              var ne = bounds.getNorthEast();
              var sw = bounds.getSouthWest();
              //do whatever you want with those bounds
              if (first) { first=false; loadPointsfomJSON(); }
     });

}

</script>


@endpush
@extends('layouts.app')
@section('title')@if (isset($location)) {{$location->name}}@else Весь мир@endif: @if (isset($category)) {{$category->name}} @else достопримечательности @endif @endsection
@section('content')
<div class="container">
  <ul class="breadcrumbs">
  <li><a href="{{ route ('/') }}"><i class="fa fa-home" aria-hidden="true"></i></a>
  @if (!isset($location) )
  <li>Каталог
  @else
  <li><a href="{{ route('location', ['','','']) }}">Каталог</a>
  @endif
  @if (isset($breadcrumbs) )
    @foreach ($breadcrumbs as $breadcrumb)<li><a href="{{ route('location', [$breadcrumb['url'],'','']) }}">{{$breadcrumb['name']}}</a></li>@endforeach
  @endif
  @if (isset($category))
  @if (isset($location))<li><a href="{{ route('location', [$location->url,'','']) }}">{{$location->name}}</a></li>@endif
  <li>{{$category->name}}</li>
  @else @if (isset($location))<li>{{$location->name}}</li> @endif
  @endif

</ul>
  <h1>
    @if (isset($category))
    {{$category->name}}
    @else Достопримечательности
    @endif
    @if (isset($location->name_rod))
    {{$location->name_rod}}
    @else
    @if (isset($location)) {{$location->name}}: достопримечательности @endif
    @endif
  </h1>


  @if (isset($subregions))
    @if ($subregions->count()>0)
    <h2>
      Регионы
      @if (isset($location->name_rod))
      {{$location->name_rod}}
      @endif
    </h2>
    @endif
    @foreach ($subregions as $locaton)
      <a href="{{route('location',[$locaton->url,''])}}"> {{$locaton->name}}</a></li>
    @endforeach
  @else
    <h2>Страны</h2>
    @foreach (App\Models\Locations::where('type','=','country')->get() as $locaton)
      <a href="{{route('location',[$locaton->url,''])}}"><img src="/i/flags/{{$locaton->flag}}" alt="flag"> {{$locaton->name}}</a></li>
    @endforeach
  @endif


  <h2>Категории</h2>
  @foreach (App\Models\Categories::get() as $loc_category)
    @if (isset($category))
      @if ($category->id!=$loc_category->id)
        @if (isset($location))
        <a href="{{ route('category',[$loc_category->url,$location->url]) }}">{{$loc_category->name}}</a>
        @else
        <a href="{{ route('category',[$loc_category->url,'']) }}">{{$loc_category->name}}</a>
        @endif
      @else
        <span>{{$loc_category->name}}</span>
      @endif
    @else
      @if (isset($location))
      <a href="{{ route('category',[$loc_category->url,$location->url]) }}">{{$loc_category->name}}</a>
      @else
      <a href="{{ route('category',[$loc_category->url,'']) }}">{{$loc_category->name}}</a>
      @endif
    @endif
  @endforeach

  <h2>Метки</h2>
  @foreach (App\Models\Tags::orderby('name','ASC')->get() as $tag)
  @if (isset($location))
  <a href="{{route('tag',[$tag->url,$location->url])}}">{{$tag->name}}</a>
  @else
  <a href="{{route('tag',[$tag->url,''])}}">{{$tag->name}}</a>
  @endif



  @endforeach

</div>
<div class="container">
  <div class="map" id="map"></div>
</div>
<div class="container">
  Показать сначала:
  @if (isset($sorts))
    @foreach ($sorts as $sort)
      @if ($request->sort==$sort['sort'] or ($request->sort=='' and $sort['sort']=='id.desc'))
      <b>{{$sort['name']}}</b>
      @else
      <a href="?sort={{$sort['sort']}}">{{$sort['name']}}</a>
      @endif
    @endforeach
  @endif

@if (isset($pois))
<div class="row">
  @foreach ($pois as $poi)
    @include('blocks.poi_card')
  @endforeach
</div>
{{$pois->appends(Request::query())->links()}}
@endif
</div>

@endsection
