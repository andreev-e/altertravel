@push('scripts')
<script type="text/javascript">


window.onload = function() {

   map = new google.maps.Map(document.getElementById('map_guide'), {
   zoom: 6,
   center: new google.maps.LatLng(55.7499172, 37.6199341),
   mapTypeId: google.maps.MapTypeId.ROADMAP,
   draggingCursor: 'crosshair',
   draggableCursor: 'pointer',
   });
   var marker_count=0;

       function populateInputs(pos) {
           document.getElementById("currentmarkerlat").value=pos.lat()
           document.getElementById("currentmarkerlng").value=pos.lng();
       }

myListener = google.maps.event.addListener(map, 'click', function(event) {

           if (marker_count<1) { placeMarker(event.latLng);
             marker_count++;
             google.maps.event.removeListener(myListener);
           }
       });


       function placeMarker(location) {
         var marker = new google.maps.Marker({
             position: new google.maps.LatLng(55.7499172, 37.6199341),
             map: map,
             draggable: true
         });
           map.setCenter(location);
           var markerPosition = marker.getPosition();
           populateInputs(markerPosition);
           google.maps.event.addListener(marker, "drag", function (mEvent) {
               populateInputs(mEvent.latLng);
           });
       }



     }
</script>
@endpush

@extends('layouts.app')
@section('title')Кабинет автора@endsection
@section('content')
<div class="container">
  <div class="row">
  <div class="col-sm-9"><h1>Ваши публикации</h1></div>
  @guest
  @else
  <div class="col-sm-3 text-end">
    <a class="btn btn-success" href="{{route('add')}}">Добавить публикацию</a>
  </div>
  @endguest
</div>
    @guest
    <p>Вы не авторизованы
    @else
    <div class="row">
      <div class="col-12">
      <table class="table table-stried">
      @if (count($pois)>0)
      @foreach ($pois as $poi)
        <tr>
        <td>
          <a href="{{ route('single-poi', $poi->url) }}"><b>{{ $poi->name }}</b></a>
          <a  target="_blank" href="{{ route('single-poi', $poi->url) }}"><i class="fa fa-external-link" aria-hidden="true"></i></a>
        </td>
        <td>
          {{ $poi->category }}
        </td>
        <td>
          @foreach ($poi->tags as $tag)
            <a href="{{ route('tag', $tag->url) }}">{{ $tag->name }}</a>{{ ($loop->last ? '' : ',') }}
          @endforeach
        </td>
      @if ($poi->status==1)
        <td>
          <form id="hide{{$poi->id}}" action="{{ route('poi-hide', $poi->id) }}" method="post">
          @csrf
          <button type="submit" class="btn btn-outline-secondary" title="Скрыть публикацию">
            <i class="fa fa-eye-slash" aria-hidden="true"></i>
          </button>
          </form>
        </td>
      @endif
      @if ($poi->status==0)
        <td>
          <form id="show{{$poi->id}}" action="{{ route('poi-show', $poi->id) }}" method="post">
            @csrf
          <button type="submit" class="btn btn-outline-success" title="Опубликовать на сайте">
            <i class="fa fa-eye" aria-hidden="true"></i>
          </button>
          </form>
        </td>
      @endif
      <td>
        <a href="{{ route('single-poi-edit', $poi->id) }}" class="btn btn-warning" title="Отредактировать">
          <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        </a>
      <td>
        <form id="delete{{$poi->id}}" action="{{ route('poi-delete', $poi->id) }}" method="post">
        @csrf
        <button type="submit" class="btn btn-outline-danger" title="Удалить"><i class="fa fa-times" aria-hidden="true"></i></button></td>
        </form>
      </div>
      @endforeach
      </table>
      {{$pois->links()}}

    </div>
      @else
      <div class="col-12">
        <div class="alert alert-danger">
          <p>У вас нет ни одной публикации</p>
          <p><a class="btn btn-success" href="{{route('add')}}">Добавить публикацию</a>
      </div>
    </div>
      @endif

    @endguest
</div>

@endsection
