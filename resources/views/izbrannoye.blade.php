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


function calcRoute(glat,glng,glatf,glngf) {

    var waypts = [
		 {location : new google.maps.LatLng(45.114059, 36.863419), stopover : true}, {location : new google.maps.LatLng(44.803333, 33.989723), stopover : true}
    ]
    if (glat &&  glng)      var start = new google.maps.LatLng(glat, glng);
    else var start = new google.maps.LatLng(47.190135835580335, 41.307006249999986);
      if (glatf &&  glngf)     var end = new google.maps.LatLng(glatf, glngf);
    else var end = new google.maps.LatLng(45.38050600157554, 39.92075625);

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
								        if (google.maps.geometry.spherical.computeDistanceBetween (prevpoint, point)>odtalenie*750)
								        {
									        points_search=points_search+lat1.toFixed(4)+'%'+lng1.toFixed(4)+'!';
									        cont_steps=cont_steps+1;
								            prevpoint=point;
								        }


        }
          );
    });
}  );

     $('#loading').show();
   $.ajax({
  url: 'additional_points.php',
  data: 'coords='+points_search+'&otdalenie='+odtalenie+'&',
  success: function(msg){
  $('#formessages').html(msg);
  $('#loading').hide();
  } ,
  complete: function() {
        $('#loading').hide();
    }
});




      }               else alert('Не получается построить маршрут. Возможно, что одна из точек находится там, куда нельзя проехать');
    });
  }


    var total = 0;
  function computeTotalDistance(result) {

    var myroute = result.routes[0];
    for (i = 0; i < myroute.legs.length; i++) {
      total += myroute.legs[i].distance.value;
    }

    total = total / 1000.
    if (total>1000)   { document.cookie='otd=50' ;   odtalenie=50; }
    document.getElementById('total').innerHTML = 'Маршрут длиной '+ Math.round(total * 10) / 10 + ' км';
  }



window.onload = function()
{

  var odtalenie='50';
var markerf;
var marker;
var markers = [];
var all_points=[];

function set_odtalenie(num) {
$('.odtalenie').removeClass('selected');

$('#odtalenie'+num).addClass('selected');
odtalenie=num;
document.cookie='otd='+num ;
calcRoute(marker.getPosition().lat(),marker.getPosition().lng(),markerf.getPosition().lat(),markerf.getPosition().lng());
}

  var myLatlng = new google.maps.LatLng(46.285320918578, 40.61388125);
      var myOptions = {
          zoom: 7,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          draggingCursor: 'crosshair',
          draggableCursor: 'pointer',
      }
  var map = new google.maps.Map(document.getElementById('izbrannoe_map_guide'), myOptions);

  	var directionDisplay;
    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer();
    directionsDisplay.setOptions( { suppressMarkers: true } );

      directionsDisplay.setMap(map);

  			image5984='/i/star.png'
  					var latlng = new google.maps.LatLng(45.114059, 36.863419);
  					var marker5984 = new google.maps.Marker({
  				    position: latlng,
  				    url: '#id5984',
  				    map: map,
  				    animation: google.maps.Animation.DROP,
  				    title:'Озеро Соленое' ,
  				    icon: image5984

  				});


  google.maps.event.addListener(marker5984, 'click', function() {
        window.location.href = marker5984.url;
      });


  			image21898='/i/star.png'
  					var latlng = new google.maps.LatLng(44.803333, 33.989723);
  					var marker21898 = new google.maps.Marker({
  				    position: latlng,
  				    url: '#id21898',
  				    map: map,
  				    animation: google.maps.Animation.DROP,
  				    title:'Пиленые скалы' ,
  				    icon: image21898

  				});


  google.maps.event.addListener(marker21898, 'click', function() {
        window.location.href = marker21898.url;
      });






     var imagef = '/i/end.png';
      markerf = new google.maps.Marker({
      position: new google.maps.LatLng(45.38050600157554, 39.92075625),
  	draggable:true,
  	icon:imagef,
      map: map,
  	animation: google.maps.Animation.DROP,
    });

       var image = '/i/start.png';
      marker = new google.maps.Marker({
      position: new google.maps.LatLng(47.190135835580335, 41.307006249999986),
  	draggable:true,
  	icon:image,
      map: map,
  	animation: google.maps.Animation.DROP,
    });

       google.maps.event.addListener(marker,"dragend", function(event) {
  	calcRoute(marker.getPosition().lat(),marker.getPosition().lng(),markerf.getPosition().lat(),markerf.getPosition().lng());
  	document.cookie='fromto='+marker.getPosition().lat()+'-'+marker.getPosition().lng()+'-'+markerf.getPosition().lat()+'-'+markerf.getPosition().lng();
  });
         google.maps.event.addListener(markerf,"dragend", function(event) {
  	calcRoute(marker.getPosition().lat(),marker.getPosition().lng(),markerf.getPosition().lat(),markerf.getPosition().lng());
  	document.cookie='fromto='+marker.getPosition().lat()+'-'+marker.getPosition().lng()+'-'+markerf.getPosition().lat()+'-'+markerf.getPosition().lng();
  });




    $( document ).ready(function() {

      calcRoute();
  });

}

</script>
@endpush
@extends('layouts.app')

@section('title')Поиск достопримечательностей вдоль маршрута @endsection
@section('content')
<div class="container">
    <div class="col-12">
        <h1>Избранное</h1>

        <div id="izbrannoe_map_guide" class="map"></div>

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
