@push('scripts')
    <script type="text/javascript">

    var infowindow = new google.maps.InfoWindow();

window.onload = function() {

    map = new google.maps.Map(document.getElementById("map"), {
   center: new google.maps.LatLng(55.7499172, 37.6199341),
zoom: 8
    });


    var json1 = {
          "universities": [
              {
                  "title": "Aberystwyth University",
                  "web": "www.aber.ac.uk",
                  "phone": "+44 (0)1970 623 111",
                  "lat": 52.415524,
                  "lng": -4.063066},
              {
                  "title": "Bangor University",
                  "web": "www.bangor.ac.uk",
                  "phone": "+44 (0)1248 351 151",
                  "lat": 53.229520,
                  "lng": -4.129987},
              {
                  "title": "Cardiff Metropolitan University",
                  "website": "www.cardiffmet.ac.uk",
                  "phone": "+44 (0)2920 416 138",
                  "lat": 51.482708,
                  "lng": -3.165881}
          ]
      };


      $.each(json1.universities, function (key, data) {

    var latLng = new google.maps.LatLng(data.lat, data.lng);

    var marker = new google.maps.Marker({
        position: latLng,
        map: map,
        // icon: icon,
        title: data.title
    });

    var details = data.website + ", " + data.phone + ".";

    bindInfoWindow(marker, map, infowindow, details);

 //    });
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
