@push('scripts')
  <script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"></script>
    <script type="text/javascript">
    var icon = "/i/map_marker.png";
    var shadow = "/i/new/marker_shadow.png";
    var icon_this = "/i/map_marker_this.png";
    var json_url = "{{ route('poi_json') }}";
    var infowindow = new google.maps.InfoWindow();
    var markersArray = [];
    var markerClusterer = null;
    var first=true; //

    function bindInfoWindow(marker, map, infowindow, strDescription, open) {

      google.maps.event.addListener(marker, 'click', function () {
          infowindow.open(map, marker);
          infowindow.setContent(strDescription);
      });
        if (open) {
          infowindow.setContent(strDescription);
          infowindow.open(map, marker);
        }
    }

    function loadPointsfomJSON() {
      var i=0;
      clearOverlays();
      var bounds = map.getBounds();
      var url=json_url+'?mne=' + bounds.getNorthEast().toUrlValue() + '&msw=' + bounds.getSouthWest().toUrlValue();

      $.getJSON(url, function(json) {
        $.each(json, function (key, data) {
          var latLng = new google.maps.LatLng(data.lat, data.lng);
          var details = "<p>"+data.name+"<br><a target='_blank' href='{{route('poi')}}/"+data.url+"'>подробнее</a>";
          var marker = new google.maps.Marker({
              position: latLng,
              map: map,
              icon: '/i/markers/'+data.icon,
              shadow: shadow,
              title: data.name
          });

          if (data.lat==﻿'{{$poi->lat}}' && data.lng=='{{$poi->lng}}') {
          marker.icon=icon_this;
          marker.title=data.name;
          bindInfoWindow(marker, map, infowindow, data.name, true);

        } else bindInfoWindow(marker, map, infowindow, details, false);

          markersArray.push(marker);
          });

          markerClusterer.clearMarkers();
          markerClusterer.addMarkers(markersArray);

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


window.onload = function()
{
    var flag_first_poi_load=true;

    map = new google.maps.Map(document.getElementById("map"),
    {
      center: new google.maps.LatLng(﻿{{$poi->lat}}, {{$poi->lng}}), zoom: 16, gestureHandling: 'greedy'
    });
    markerClusterer= new MarkerClusterer(map, markersArray, {
        imagePath: "/i/markers/pie",
        zoomOnClick: false,
    });

google.maps.event.addListener(map, 'dragend', function() { loadPointsfomJSON(); });
google.maps.event.addListener(map, 'zoom_changed', function() { loadPointsfomJSON(); });
google.maps.event.addListener(map, 'idle', function() { if (flag_first_poi_load) { flag_first_poi_load=false; loadPointsfomJSON(); } });

}

    </script>
@endpush
@extends('layouts.app')

@section('title'){!!$poi->name!!}, @if (count($poi->locations)>0) {{$poi->locations[count($poi->locations)-1]->name}} @endif @endsection

@section('content')

<div class="container">
   @if (count($poi->locations)>0)
  <ul class="breadcrumbs">
    <li><a href="{{ route ('/') }}"><i class="fa fa-home" aria-hidden="true"></i></a>
    @foreach ($poi->locations as $location)<li><a href="{{ route ('location',[$location->url,'','']) }}">{{ $location->name }}</a></li>@endforeach
    <li>@if (isset($poi->category)) <a href="{{ route ('location',[$location->url,$poi->category->url,'']) }}">{{$poi->category->name}}</a> @endif</li>
  </ul>
  @endif
  <h1>{!!$poi->name!!}
    @if ($poi->user_id==Auth::user()->id)
    <a href="{{ route('single-poi-edit', $poi->id) }}" title="Отредактировать">
    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
    @endif
  </a></h1>
  <p class="small">Автор публикации <a href="{{ route('user', $poi->user->login )}}">{{$poi->user->name}}</a>
     @isset($poi->copyright) / Автор фото {{$poi->copyright}}@endisset
   / {{$poi->views}} просмотров</p>
<nav>
<ul class="fastnav">
  <li>Как добраться
  <li>Кто уже побывал?
  <li>Отзывы и комментарии
</ul>
</nav>
<img src="{{$poi->main_image()}}" alt="{{$poi->name}}"/>
@foreach ($poi->gallery() as $photo)
<img src="{{ $photo }}" alt="{{$poi->name}}"/>
@endforeach
<h2>Карта</h2>
<div class="map" id="map"></div>
<h2>Описание</h2>
{!!$poi->description!!}
@isset($poi->route)
<h2>Как добраться</h2>
{!!$poi->route!!}
@endisset
@isset($poi->route_o)
<h2>Как добраться на общественном транспорте</h2>
{!!$poi->route_o!!}
@endisset
@isset($poi->prim)
<h2>Примечание</h2>
{!!$poi->prim!!}
@endisset
<h2>Ссылки</h2>
{{$poi->links}}
<h2>Маршруты</h2>
  <div class="d-flex flex-wrap align-items-stretch">
@foreach ($poi->routes as $route)
@include('blocks.route_card')
@endforeach
  </div>
<h2>Комментарии</h2>
<div class="d-flex flex-wrap align-items-stretch">
  @foreach ($comments as $comment)
@include('blocks.comment_card')
@endforeach
</div>
<h2>Метки</h2>
@foreach ($poi->tags as $tag) <a href="{{ Route('tag',$tag->url) }}" class="btn btn-primary btn-sm">{{ $tag->name }}</a> @endforeach
    <div class="row justify-content-center">
        <div class="col-md-8">

        </div>
    </div>
</div>

@endsection
