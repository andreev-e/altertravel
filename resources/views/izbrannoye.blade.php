@push('scripts')
<script type="text/javascript">

var icon = "/i/map_marker.png";
var json_url = "{{ route('poi_json') }}";
var infowindow = new google.maps.InfoWindow();
var markersArray = [];
var first=true; //
var total = 0;

var glat={{$beginend[0]}};
var glng={{$beginend[1]}};
var glatf={{$beginend[2]}};
var glngf={{$beginend[3]}};



  function computeTotalDistance(result) {
    var myroute = result.routes[0];
    for (i = 0; i < myroute.legs.length; i++)   total += myroute.legs[i].distance.value;
    total = total / 1000.
    document.getElementById('total').innerHTML = 'Построен маршрут длиной '+ Math.round(total * 10) / 10 + ' км';
  }

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

window.onload = function()
{

var odtalenie='50';
var markerf;
var marker;
var markers = [];
var all_points=[];

  var myLatlng = new google.maps.LatLng({{$beginend[0]}}, {{$beginend[1]}});
      var myOptions = {
          zoom: 6,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          draggingCursor: 'crosshair',
          draggableCursor: 'pointer',
          gestureHandling: 'greedy',
      }
  var map = new google.maps.Map(document.getElementById('izbrannoe_map_guide'), myOptions);

  	var directionDisplay;
    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer();
    directionsDisplay.setOptions( { suppressMarkers: true } );

      directionsDisplay.setMap(map);

      @foreach ($pois as $poi)
      image{{$poi->id}}='/i/star.png'
          var latlng = new google.maps.LatLng({{$poi->lat}}, {{$poi->lng}});
          var marker21898 = new google.maps.Marker({
            position: latlng,
            url: '#id{{$poi->id}}',
            map: map,
            animation: google.maps.Animation.DROP,
            title:'{{$poi->name}}' ,
            icon: image{{$poi->id}}

        });


        google.maps.event.addListener(marker21898, 'click', function() {
      window.location.href = marker21898.url;
    });
      @endforeach



     var imagef = '/i/end.png';
      markerf = new google.maps.Marker({
      position: new google.maps.LatLng({{$beginend[2]}}, {{$beginend[3]}}),
  	draggable:true,
  	icon:imagef,
      map: map,
  	animation: google.maps.Animation.DROP,
    });

       var image = '/i/start.png';
      marker = new google.maps.Marker({
      position: new google.maps.LatLng({{$beginend[0]}}, {{$beginend[1]}}),
  	draggable:true,
  	icon:image,
      map: map,
  	animation: google.maps.Animation.DROP,
    });

       google.maps.event.addListener(marker,"dragend", function(event) {
         start=marker.getPosition();
         finish=markerf.getPosition();
  	     calcRoute(start.lat(),start.lng(),finish.lat(),finish.lng());
  	     document.cookie='fromto='+[start.lat(), start.lng(), finish.lat(), finish.lng()].join(',');
       });

       google.maps.event.addListener(markerf,"dragend", function(event) {
         start=marker.getPosition();
         finish=markerf.getPosition();
        calcRoute(start.lat(),start.lng(),finish.lat(),finish.lng());
        document.cookie='fromto='+[start.lat(), start.lng(), finish.lat(), finish.lng()].join(',');
       });

    $( document ).ready(calcRoute(glat,glng,glatf,glngf));


    function calcRoute(glat,glng,glatf,glngf) {

        var waypts = [
          @foreach ($pois as $poi)
            {location : new google.maps.LatLng({{$poi->lat}}, {{$poi->lng}}), stopover : true},
          @endforeach
        ]
        if (glat &&  glng)  var start = new google.maps.LatLng(glat, glng);
        if (glatf &&  glngf) var end = new google.maps.LatLng(glatf, glngf);

        var request = {
            origin:start,
            destination:end,
            waypoints: waypts, optimizeWaypoints: true,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };

        directionsService.route(request, function(response, status) {

          if (status == google.maps.DirectionsStatus.OK) {
    								directionsDisplay.setDirections(response);
    								computeTotalDistance(directionsDisplay.directions);
    								var myRoute = response.routes[0].legs[0];
    								var route= response.routes[0];
    								var points_search='';
    								var prevlng=0;
    								var prevlat=0;
    								var cont_steps=0;
    								var prevpoint=start;
    								var prevpoint2=start;
    								var nakopl_dist=0;
                    var odtalenie=50;
    								all_points=[];
    								route.legs.forEach(function(leg){
    								    leg.steps.forEach(function(step){
    								        step.path.forEach(function(point){
          								    latlng=point.toString();
          								    latlng=latlng.replace(')','');
          								    latlng=latlng.replace('(','');
          								    latlng_arr=  latlng.split(',');
          								    lat1=parseFloat(latlng_arr[0]);
          								    lng1=parseFloat(latlng_arr[1]);

          								    if (google.maps.geometry.spherical.computeDistanceBetween (prevpoint2, point)>odtalenie*100) {
          								    	 all_points.push(point);
          								    	 prevpoint2=point;
          								    }
          								    if (google.maps.geometry.spherical.computeDistanceBetween (prevpoint, point)>odtalenie*750) {
          									        points_search=points_search+lat1.toFixed(4)+','+lng1.toFixed(4)+'!';
          									        cont_steps=cont_steps+1;
          								          prevpoint=point;
          								    }
                            });
                        });
                      });

        clearOverlays();
      $.getJSON('{{route('route_points')}}'+'?coords='+points_search+'&otdalenie='+odtalenie, function(json) {
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



    });




          } else alert('Не получается построить маршрут. Возможно, что одна из точек находится там, куда нельзя проехать');
        });
      }


}

</script>
@endpush
@extends('layouts.app')

@section('title')Поиск достопримечательностей вдоль маршрута @endsection
@section('content')
<div class="container">
  <h1>Избранное</h1>
  <div class="row">
    <div class="col-8">
        <div id="izbrannoe_map_guide" class="map"></div>
    </div>
    <div class="col-4">
      <p id="total">
    </div>
  </div>
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
