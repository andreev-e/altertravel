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
  center: new google.maps.LatLng({{$location->lat}}, {{$location->lng}}), zoom: 6, gestureHandling: 'greedy'
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
