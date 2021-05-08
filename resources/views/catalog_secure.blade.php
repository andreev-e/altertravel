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
  <h1>Ваши публикации</h1>
    @guest
    <p>Вы не авторизованы
    @else
    <div class="row">
      <table class="table table-stried">
      @if (count($pois)>0)
      @foreach ($pois as $poi)
        <tr>
        <td>
          <a href="{{ route('single-poi-edit', $poi->url) }}"><b>{{ $poi->name }}</b></a>
          <a  target="_blank" href="{{ route('single-poi', $poi->url) }}"><i class="fa fa-external-link" aria-hidden="true"></i></a>
        </td>
        <td>
          {{ $poi->category }}
        </td>
        <td><i class="fa fa-map-marker" aria-hidden="true"></i>
          @foreach ($poi->locations as $location)
            <a href="{{ route('location', $location->url) }}">{{ $location->name }}</a>{{ ($loop->last ? '' : ' / ') }}
          @endforeach
        </td>
        <td>
          @foreach ($poi->tags as $tag)
            <a href="{{ route('tag', $tag->url) }}">{{ $tag->name }}</a>{{ ($loop->last ? '' : ',') }}
          @endforeach
        </td>
      @if ($poi->status==1)
      <td><span class="text-success">опубликовано</span>
        <td>
          <form id="hide{{$poi->id}}" action="{{ route('poi-hide', $poi->id) }}" method="post">
          @csrf
          <button type="submit" class="btn btn-outline-secondary">Скрыть</button></td>
          </form>
      @endif
      @if ($poi->status==0)
      <td><span class="text-danger">скрыто</span>
        <td>
          <form id="show{{$poi->id}}" action="{{ route('poi-show', $poi->id) }}" method="post">
            @csrf
          <button type="submit" class="btn btn-success">Опубликовать</button></td>
          </form>
      @endif
      <td>
      <td>
        <form id="delete{{$poi->id}}" action="{{ route('poi-delete', $poi->id) }}" method="post">
        @csrf
        <button type="submit" class="btn btn-outline-danger">Удалить</button></td>
        </form>
      </div>
      @endforeach
      </table>
      @else
      <div class="col-12">
        <div class="alert alert-danger">
          <p>У вас нет ни одной публикации</p>
      </div>
    </div>
      @endif
    <div class="col-12">
    <h2>Добавить публикацию</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <form method="POST" action="{{route('add')}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="title">Название объекта</label>
          <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}">
          <small class="form-text text-muted">Местоположение будет определено по координатам, пожалуйста не указывайте его в названии</small>
        </div>
        <p>Укажите точку на карте

        <div class="form-row">
          <div class="col-8"><div style="height:300px;" id="map_guide"></div></div>
          <div class="col-4">
            <label for="lat">Широта</label>
            <input type="text" id="currentmarkerlat" class="form-control @error('lat') is-invalid @enderror"  name="lat" value="{{ old('lat') }}">
            <label for="lng">Долгота</label>
            <input type="text" id="currentmarkerlng" class="form-control @error('lng') is-invalid @enderror"  name="lng" value="{{ old('lng') }}">
          </div>
        </div>
        <div class="form-group">
          <label for="description">Подробное описание</label>
          <textarea  class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
        </div>
        <div class="form-group">
          <label for="photos">Загрузите фотографии</label>
          <input multiple="multiple" class="form-control-file @error('photos') is-invalid @enderror" name="photos[]" type="file">
          <small class="form-text text-muted">Хотя бы одно фото</small>
        </div>
        <div class="form-group">
            <label for="category">Категория</label>
            <select class="form-control @error('category') is-invalid @enderror" name="category">
                <option value="Архитектура">Архитектура</option>
                <option value="Природа">Природа</option>
                <option value="История/Культура">История/Культура</option>
                <option value="Техноген">Техноген</option>
                <option value="Музей">Музей</option>
                <option value="Памятник">Памятник</option>
                <option value="Ночлег">Ночлег</option>
                <option value="Еда">Еда</option>
                <option value="Покупки">Покупки</option>
                <option value="Развлечения">Развлечения</option>
            </select>
        </div>
        <div class="form-group">
          <label for="checkboxes">Метки</label>
        <div class="form-group">
          @foreach (App\Models\Tags::get() as $tag)
          <label class="col-md-2 checkbox-inline" for="tag{{$tag->id}}">
          <input type="checkbox" name="checkboxes" id="tag{{$tag->id}}" value="{{$tag->id}}">
          {{$tag->name}}
          </label>

          @endforeach
        </div>
        </div>
        <div class="form-group">
          <label for="route">Как добраться?</label>
          <textarea  class="form-control @error('route') is-invalid @enderror" name="route" >{{ old('route') }}</textarea>
          <small class="form-text text-muted">На машине и общественным транспортом</small>
        </div>
        <div class="form-group">
          <label for="prim">Примечание</label>
          <textarea  class="form-control @error('prim') is-invalid @enderror" name="prim" >{{ old('prim') }}</textarea>
          <small class="form-text text-muted">Что еще хотите сообщить</small>
        </div>
        <div class="form-group">
          <label for="video">Видео</label>
          <input type="text" class="form-control @error('video') is-invalid @enderror"  name="video" value="{{ old('video') }}">
          <small class="form-text text-muted">https://www.youtube.com/watch?v=<b>xxxxxxx</b></small>
        </div>
        <button type="submit" class="btn btn-primary">Опубликовать</button>
    </form>
</div>




    @endguest
</div>

@endsection
