@push('scripts')
    <script type="text/javascript">
    var icon = "/i/map_marker.png";
    var icon_this = "/i/map_marker_this.png";
    var json_url = "{{ route('poi_json') }}";
    var infowindow = new google.maps.InfoWindow();
    var markersArray = [];
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
            position: latLng, map: map, icon: icon,
            title: data.name
        });

        if (data.lat==﻿'{{$poi->lat}}' && data.lng=='{{$poi->lng}}') {
        marker.icon=icon_this;
        marker.title=data.name;
        bindInfoWindow(marker, map, infowindow, data.name, true);

      } else bindInfoWindow(marker, map, infowindow, details, false);

        markersArray.push(marker);
        });
    });
    }

    function clearOverlays() {
      for (var i = 0; i < markersArray.length; i++ ) {
        markersArray[i].setMap(null);
      }
      markersArray.length = 0;
    }


window.onload = function()
{
    var flag_first_poi_load=true;

    map = new google.maps.Map(document.getElementById("map"),
    {
      center: new google.maps.LatLng(﻿{{$poi->lat}}, {{$poi->lng}}), zoom: 13, gestureHandling: 'greedy'
    });

google.maps.event.addListener(map, 'dragend', function() { loadPointsfomJSON(); });
google.maps.event.addListener(map, 'zoom_changed', function() { loadPointsfomJSON(); });
google.maps.event.addListener(map, 'idle', function() { if (flag_first_poi_load) { flag_first_poi_load=false; loadPointsfomJSON(); } });

}

    </script>
@endpush
@extends('layouts.app')

@section('title'){!!$poi->name!!}@endsection

@section('content')

<div class="container">
  <ul class="breadcrumbs">
    <li><a href="{{ route ('/') }}"><i class="fa fa-home" aria-hidden="true"></i></a>
    @foreach ($poi->locations as $location)<li><a href="{{ route ('location',$location->url) }}">{{ $location->name }}</a></li>@endforeach
  </ul>
  <h1>{!!$poi->name!!}</h1>
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
<img src="https://altertravel.ru/images/{{ $poi->old_id }}.jpg" class="img-fluid" alt="{{$poi->name}}"/>
@foreach ($poi->photos as $photo)
<img src="{{ Storage::url($photo) }}" alt="{{$poi->name}}"/>
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
<h2>Комментари</h2>
{{$poi->comments}}
Теги @foreach ($poi->tags as $tag) <a href="{{ Route('tag',$tag->url) }}" class="btn btn-primary btn-sm">{{ $tag->name }}</a> @endforeach
    <div class="row justify-content-center">
        <div class="col-md-8">

        </div>
    </div>
</div>

@endsection
