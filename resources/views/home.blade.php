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
      center: new google.maps.LatLng(﻿54.96667, 73.38333), zoom: 4
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
    i=i+1;
        if (i<=6) $('#shown_on_map ').append('<div class="col-sm-2"><div class="card"><img class="card-img-top" src="'+data.photo+'" alt="'+data.name+'"><div class="card-body"><div class="h5 card-title">'+data.name+'</div>  <a href="{{ route('pois') }}/'+data.url+'" class="btn btn-primary">Смотреть</a></div></div></div>');
     
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

@section('content')
<div class="container">
  <h1>Карта достопримечательностей</h1>
</div>
<div class="container-fluid">
  <div class="row">
  <div class="map" id="map"></div>
  </div>
</div>
<div class="container-fluid mt-3">
         <div  class="row" id="shown_on_map" >
  @foreach ($pois as $key => $poi)
    <div class="col-sm-2">
      <div class="card">
         <img class="card-img-top" src="{{ $poi->photo }}" alt="{{ $poi->name }}">
         <div class="card-body">
      <div class="h5 card-title">{{ $poi->name }}</div>
      <a href="{{ route('single-poi', $poi->url) }}" class="btn btn-primary">Смотреть</a>
      </div>
      </div>
    </div>
@endforeach
</div>
</div>

@endsection
