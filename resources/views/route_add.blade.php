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

   placeMarker(new google.maps.LatLng(55.7499172, 37.6199341));

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
@section('title')Добавление публикации@endsection
@section('content')
<div class="container">
<p>
    @guest
    @include('blocks.register_or_login')
    @else
    <div class="row">
    <div class="col-12">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{route('my_pois_add')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="name">Название объекта</label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
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
                  <label for="formFile" class="form-label">Загрузите фотографии</label>
                  <input multiple="multiple" class="form-control @error('photos') is-invalid @enderror" name="photos[]" type="file" id="formFile">
                  <small class="form-text text-muted">Хотя бы одно фото</small>
                </div>
                <div class="form-group">
                    <label for="category">Категория</label>
                    <select class="form-select" @error('category') is-invalid @enderror" name="category">
                        @foreach (App\Models\Categories::get() as $category)
                        <option value="{{$category->id}}" @if (old('category')==$category->id) selected @endif>{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                  <label for="checkboxes">Метки</label>
                <div class="form-group">
                  @foreach (App\Models\Tags::get() as $tag)
                  <label class="col-md-2 form-check-label for="tag{{$tag->id}}">
                  <input class="form-check-input" type="checkbox" name="tags[]" id="tag{{$tag->id}}" value="{{$tag->id}}">
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


    @endguest
</div>

@endsection
