@push('scripts')
    <script type="text/javascript">
    //var icon = "http://path/to/icon.png";
    var json = "{{ route('poi_json') }}";
    var infowindow = new google.maps.InfoWindow();

window.onload = function() {

    map = new google.maps.Map(document.getElementById("map"), {
   center: new google.maps.LatLng(55.7499172, 37.6199341),
zoom: 2
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

@section('content')
<div class="container">
  <h1>Главная</h1>
  <div class="map" id="map"></div>
    <div class="row" id="shown_on_map">
  @foreach ($pois as $poi)
    <div class="col-sm-2">
    <a href="{{ route('single-poi', $poi->url) }}">
    <img src="{{ $poi->photo }}" alt="{{ $poi->name }}" />
    <div class="title">{{ $poi->name }}</div>
    </a>
    </div>
@endforeach

</div>

@endsection
