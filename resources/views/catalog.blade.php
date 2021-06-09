@push('scripts')
<script type="text/javascript">

var icon = "/i/map_marker.png";
var json_url = "{{ route('poi_json') }}";
var infowindow = new google.maps.InfoWindow();
var markersArray = [];
var first=true; //

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
  markersArray.length = 0;
}

function loadPointsfomJSON() {

  var i=0;
  clearOverlays();
  $("#tags").children().hide();

  var bounds = map.getBounds();
  var url=json_url+'?mne=' + bounds.getNorthEast().toUrlValue() + '&msw=' + bounds.getSouthWest().toUrlValue();

  $.getJSON(url, function(json) {
  /*$('#shown_on_map').empty();*/
  $.each(json, function (key, data) {
    var latLng = new google.maps.LatLng(data.lat, data.lng);
    var marker = new google.maps.Marker({
        position: latLng,
        map: map,
        icon: '/i/markers/'+data.icon,
        title: data.name
    });
    markersArray.push(marker);

    var details = "<p>"+data.name+"<br><a target='_blank' href='/place/"+data.url+"'>подробнее</a>";
    bindInfoWindow(marker, map, infowindow, details);
    /*
    i=i+1;
        if (i<={{ env('OBJECTS_ON_MAIN_PAGE',6) }}) $('#shown_on_map ').append('<div class="poi p-3"><div class="card"><a href="{{ route('poi') }}/'+data.url+'" target="_blank"><img class="card-img-top" src="'+data.photo+'" alt="'+data.name+'"><div class="card-body"><div class="h5 card-title">'+data.name+'</div></a> </div></div></div>');
        */
        data.tags.forEach(function(item, i, arr) {
          $('[data-tag_id="'+item.id+'"]').show();
        });


  });

  markerClusterer.clearMarkers();
  markerClusterer.addMarkers(markersArray);


});

}



window.onload = function()
{
  @if ($current_location==null) oldzoom=2
  @else oldzoom={{$current_location->scale}}
  @endif
    map = new google.maps.Map(document.getElementById("map"),
    {
      @if (isset($current_location))
      center: new google.maps.LatLng({{$current_location->lat}}, {{$current_location->lng}}), zoom: {{$current_location->scale}},
      @else
      center: new google.maps.LatLng(28.425261, 74.771668), zoom: oldzoom,
      @endif
      gestureHandling: 'greedy',
    });

    markerClusterer= new MarkerClusterer(map, markersArray, {
        imagePath: "/i/markers/pie",
    });

google.maps.event.addListener(map, 'dragend', function() {
     loadPointsfomJSON();


  });
google.maps.event.addListener(map, 'zoom_changed', function() {
  if (oldzoom>map.getZoom())  loadPointsfomJSON();
     oldzoom=map.getZoom();

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
@section('title'){{$meta['h1']}} на карте | Альтернативный путеводитель @endsection
@section('description')Все {{$meta['h1']}} в путеводителе с фото, описаниями, отзывами, картами проезда. Строим маршруты, делимся впечатлениями и фотографиями @endsection

@section('content')
<div class="container">
  <ul class="breadcrumbs">
  <li><a href="{{ route ('/') }}"><i class="fa fa-home" aria-hidden="true"></i></a>
  @if (!isset($current_location) & !isset($current_category) & !isset($current_tag) )
  <li>Каталог
  @else
  <li><a href="{{ route('location', ['','','']) }}">Каталог</a>
  @endif
  @if (isset($breadcrumbs) )
    @foreach ($breadcrumbs as $breadcrumb)
      <li><a href="{{ route('location', [$breadcrumb['url'],'','']) }}">{{$breadcrumb['name']}}</a></li>
    @endforeach
  @endif

  @if (isset($current_category) or isset($current_tag))
    @if (isset($current_location))
      <li><a href="{{ route('location', [$current_location->url,'','']) }}">{{$current_location->name}}</a></li>
    @endif
  @else
    @if (isset($current_location))
    <li>{{$current_location->name}}</li>
    @endif
  @endif

  @if (isset($current_category))
  <li>{{$current_category->name}}</li>
  @endif

  @if (isset($current_tag))
    <li>{{$current_tag->name}}</li>
  @endif
</ul>
  <h1>
{{$meta['h1']}}
  </h1>
<div class="row">
  <div class="col-sm-6">
    <div class="map" id="map"></div>
  </div>
  <div class="col-sm-6">
  @if (isset($subregions))
    @if ($subregions->count()>0)
      <h2>
      Подрегионы
      @if (isset($current_location->name_rod))
        {{$current_location->name_rod}}
      @endif
      </h2>
      @foreach ($subregions as $subregion)
        @if (isset($current_tag))
          <a href="{{route('tag',[$current_tag->url,$subregion->url])}}">{{$subregion->name}}</a></li>
          @else
          <a href="{{route('location',[$subregion->url,''])}}">{{$subregion->name}}</a></li>
          @endif
      @endforeach
    @elseif (isset($locations) & !isset($current_location))
      <h2>Страны</h2>
      @foreach ($locations as $loc)
        <a href="{{route('location',[$loc->url,''])}}"><img src="/i/flags/{{$loc->flag}}" alt="flag"> {{$loc->name}}</a></li>
        @endforeach
    @endif
  @elseif (isset($locations) & !isset($current_location))
    <h2>Страны</h2>
    @foreach ($locations as $loc)
      @if (isset($current_tag))
        <a href="{{route('tag',[$current_tag->url,$loc->url])}}"><img src="/i/flags/{{$loc->flag}}" alt="flag"> {{$loc->name}}</a></li>
      @else
        <a href="{{route('location',[$loc->url,''])}}"><img src="/i/flags/{{$loc->flag}}" alt="flag"> {{$loc->name}}</a></li>
      @endif
    @endforeach
  @endif

@if (count($categories)!=0)
  <h2>Категории достопримечательностей
    @if (isset($current_location->name_rod))
      {{$current_location->name_rod}}
    @endif
  </h2>
  @foreach ($categories as $loc_category)
    @if (isset($current_category))
      @if ($current_category->id!=$loc_category->id)
        @if (isset($current_location))
        <a href="{{ route('category',[$loc_category->url,$current_location->url]) }}">{{$loc_category->name}}</a>
        @else
        <a href="{{ route('category',[$loc_category->url,'']) }}">{{$loc_category->name}}</a>
        @endif
      @else
        <span>{{$loc_category->name}}</span>
      @endif
    @else
      @if (isset($current_location))
      <a href="{{ route('category',[$loc_category->url,$current_location->url]) }}">{{$loc_category->name}}</a>
      @else
      <a href="{{ route('category',[$loc_category->url,'']) }}">{{$loc_category->name}}</a>
      @endif
    @endif
  @endforeach
@endif

@if (count($tags)!=0)
  <p class="h2">Метки</p>
    @foreach ($tags as $tag)
      @if (isset($current_location))
        <a href="{{route('tag',[$tag->url,$current_location->url])}}" title="{{$tag->name_rod}} {{$current_location->name_rod}}">{{$tag->name_rod}}</a>
      @else
        <a href="{{route('tag',[$tag->url,''])}}">{{$tag->name_rod}}</a>
      @endif
    @endforeach
  @endif
  <p class="h2">Вы можете</p>
<div class="btn-group">
    <a class="btn btn-primary" href="http://altertravel-ru.ru/my_pois/add">Добавить точку</a>
    <a class="btn btn-secondary" href="http://altertravel-ru.ru/my_routes/add">Добавить маршурт</a>
</div>
</div>


</div>
</div>
<div class="container mt-3">
  <div class="col-12">
      <h2>Список достопримечательностей
        @if (isset($current_location->name_rod))
          {{$current_location->name_rod}}
        @endif
       с фото</h2>
  @if (isset($sorts))
    @include('blocks.sort')
  @endif

@if (isset($pois))
<div class="row">
  @foreach ($pois as $poi)
    @include('blocks.poi_card')
  @endforeach
</div>
{{ $pois->appends(Request::query())->onEachSide(1)->links()}}
@endif
</div>
</div>
@endsection
