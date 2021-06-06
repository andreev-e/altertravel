@push('scripts')
<script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"></script>
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
    oldzoom=9;
    map = new google.maps.Map(document.getElementById("map"),
    {
      center: new google.maps.LatLng(55.7499172, 37.6199341), zoom: oldzoom, gestureHandling: 'greedy'
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
@section('title')Альтернативный путеводитель @endsection

@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
  <div class="col-sm-9 text-center">
    <h1>Карта достопримечательностей</h1>
    <div class="map" id="map"></div>
  </div>
  <div class="col-sm-3">
    <h2>Метки</h2>
    <div class="col-12 " id="tags">
      @foreach ($tags as $tag)
      <a href="{{route('tag',[$tag->url,''])}}" data-tag_id="{{$tag->id}}">{{$tag->name}}</a>
      @endforeach
    </div>
  </div>
  </div>
</div>
<div class="container text-center">
  <p class="h1" >Новые места</p>
  <div class="gallery d-flex flex-wrap justify-content-between align-items-stretch">
    @foreach ($pois as $poi)
  @include('blocks.poi_card')
  @endforeach
  </div>
</div>
<div class="container text-center">
  <p class="h1" >Популярные маршруты</p>
  <div class="gallery d-flex flex-wrap justify-content-between align-items-stretch">
    @foreach ($routes as $route)
  @include('blocks.route_card')
  @endforeach
  </div>
</div>
<div class="container text-center">
  <p class="h1" >Последние комментарии</p>
  <div class="gallery d-flex flex-wrap justify-content-between align-items-stretch">
    @foreach ($comments as $comment)
  @include('blocks.comment_card')
  @endforeach
  </div>
</div>

@endsection
