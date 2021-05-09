@push('scripts')
  <script type="text/javascript">
  var json = "{{ route('poi_json') }}";
  var infowindow = new google.maps.InfoWindow();

window.onload = function() {

    map = new google.maps.Map(document.getElementById("map"), {
   center: new google.maps.LatLng({{$location->lat}}, {{$location->lng}}),
zoom: 6
    });

    $.getJSON(json, function(json1) {


        $.each(json1, function (key, data) {

      var latLng = new google.maps.LatLng(data.lat, data.lng);

      var marker = new google.maps.Marker({
          position: latLng,
          map: map,
          //icon: icon,
          title: data.name
      });

      var details = data.name+"<br><a target='_blank' href='/place/"+data.url+"'>подробнее</a>";

      bindInfoWindow(marker, map, infowindow, details);

      });
  });

  function bindInfoWindow(marker, map, infowindow, strDescription) {
      google.maps.event.addListener(marker, 'click', function () {
          infowindow.setContent(strDescription);
          infowindow.open(map, marker);
      });
  }

           }

      </script>
@endpush
@extends('layouts.app')
@section('title')Достопримечательности {{$location->name}} @endsection
@section('content')
<div class="container">
  <ul class="breadcrumbs">
  <li><a href="{{ route ('/') }}"><i class="fa fa-home" aria-hidden="true"></i></a>
  @foreach ($breadcrumbs as $breadcrumb)<li><a href="{{ route('location', $breadcrumb['url']) }}">{{$breadcrumb['name']}}</a></li>@endforeach
<li>{{$location->name}}</li>
</ul>
  <h1>Достопримечательности {{$location->name}}</h1>
</div>
<div class="container-fluid">
  <div class="row">
  <div class="map" id="map"></div>
  </div>
</div>
<div class="container">
  <div class="row">

  @foreach ($pois as $poi)
    <div class="col-sm-4"><a href="{{ route('single-poi', $poi->url) }}">{{ $poi->name }}</a></div>
@endforeach
</div>
</div>

@endsection
