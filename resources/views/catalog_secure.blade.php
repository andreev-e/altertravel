@extends('layouts.app')
@section('title')Кабинет автора@endsection
@section('content')
<div class="container">
  <h1>Ваши публикации</h1>
    @guest
    <p>Вы не авторизованы
    @else
    <div class="row">
      @if (count($pois)>0)
      @foreach ($pois as $poi)
        <div class="col-sm-12"><a href="{{ route('single-poi-edit', $poi->url) }}">{{ $poi->name }}</a></div>
      @endforeach
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
        <div style="height:300px;" id="map_guide"></div>
        <div class="form-row">
          <div class="col">
            <label for="lat">Широта</label>
            <input type="text" id="currentmarkerlat" class="form-control @error('lat') is-invalid @enderror"  name="lat" value="{{ old('lat') }}">
          </div>
          <div class="col">
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
          <input multiple="multiple"  class="form-control-file @error('photos') is-invalid @enderror" name="photos[]" type="file">
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
                <option value="Памятник">Ночлег</option>
                <option value="Памятник">Еда</option>
                <option value="Памятник">Покупки</option>
                <option value="Памятник">Развлечения</option>
            </select>
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
<script src="//maps.google.com/maps/api/js?key=AIzaSyDVxoYcrB_2arTNlYpFClzzKy9KgFW3_Y8"></script>
<script type="text/javascript">
load();
 function load() {
   var map = new google.maps.Map(document.getElementById("map_guide"), {
   zoom: 5,
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



    @endguest
</div>

@endsection
