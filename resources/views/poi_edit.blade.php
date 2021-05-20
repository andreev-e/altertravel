@push('scripts')
<script type="text/javascript">


window.onload = function() {

   map = new google.maps.Map(document.getElementById('map_guide'), {
   zoom: 6,
   center: new google.maps.LatLng({{$poi->lat}}, {{$poi->lng}}),
   mapTypeId: google.maps.MapTypeId.ROADMAP,
   draggingCursor: 'crosshair',
   draggableCursor: 'pointer',
   });
   var marker_count=0;

   placeMarker(new google.maps.LatLng({{$poi->lat}}, {{$poi->lng}}));

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
             position: new google.maps.LatLng({{$poi->lat}}, {{$poi->lng}}),
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
@section('title')Редактор: {{$poi->name}}@endsection
@section('content')
<div class="container">
  @guest
  <p>Вы не авторизованы
  @else
  <h1>Страница редактирования объекта</h1>
  @if ($errors->any())
  <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
  @endif
    <div class="row justify-content-center">
        <div class="col-12">
          <form method="POST" action="{{route('single-poi-edit-post', $poi->id)}}" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label for="name">Название объекта</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="@if (strlen(old('name'))){{old('name')}}@else{{ $poi->name }}@endif">
                <small class="form-text text-muted">Местоположение будет определено по координатам, пожалуйста не указывайте его в названии</small>
              </div>
              <p>Укажите точку на карте

              <div class="form-row">
                <div class="col-8"><div style="height:300px;" id="map_guide"></div></div>
                <div class="col-4">
                  <label for="lat">Широта</label>
                  <input type="text" id="currentmarkerlat" class="form-control @error('lat') is-invalid @enderror"  name="lat" value="@if (strlen(old('lat'))){{old('lat')}}@else{{ $poi->lat }}@endif">
                  <label for="lng">Долгота</label>
                  <input type="text" id="currentmarkerlng" class="form-control @error('lng') is-invalid @enderror"  name="lng" value="@if (strlen(old('lng'))){{old('lng')}}@else{{ $poi->lng }}@endif">
                </div>
              </div>
              <div class="form-group">
                <label for="description">Подробное описание</label>
                <textarea  class="form-control @error('description') is-invalid @enderror" name="description">@if (strlen(old('description'))){{old('description')}}@else{{ $poi->description }}@endif</textarea>
              </div>
              <div class="form-group">
                <label for="photos">Загрузите фотографии</label>
                <input multiple="multiple" class="form-control-file @error('photos') is-invalid @enderror" name="photos[]" type="file">
                <small class="form-text text-muted">Хотя бы одно фото</small>
              </div>
              <div class="form-group">
                  <label for="category">Категория {{old('category')}}</label>
                  <select class="form-control @error('category') is-invalid @enderror" name="category">
                      <option value="Архитектура" @if ($poi->category=='Архитектура' or old('category')=='Архитектура') selected @endif>Архитектура</option>
                      <option value="Природа" @if ($poi->category=='Природа' or old('category')=='Природа') selected @endif>Природа</option>
                      <option value="История/Культура"  @if ($poi->category=='История/Культура' or old('category')=='История/Культура') selected @endif>История/Культура</option>
                      <option value="Техноген"  @if ($poi->category=='Техноген' or old('category')=='Техноген') selected @endif>Техноген</option>
                      <option value="Музей"  @if ($poi->category=='Музей' or old('category')=='Музей') selected @endif>Музей</option>
                      <option value="Памятник"  @if ($poi->category=='Памятник' or old('category')=='Памятник') selected @endif>Памятник</option>
                      <option value="Ночлег"  @if ($poi->category=='Ночлег' or old('category')=='Ночлег') selected @endif>Ночлег</option>
                      <option value="Еда"  @if ($poi->category=='Еда' or old('category')=='Еда') selected @endif>Еда</option>
                      <option value="Покупки"  @if ($poi->category=='Покупки' or old('category')=='Покупки') selected @endif>Покупки</option>
                      <option value="Развлечения"  @if ($poi->category=='Развлечения' or old('category')=='Развлечения') selected @endif>Развлечения</option>
                  </select>
              </div>
              <div class="form-group">
                <label for="checkboxes">Метки</label>
              <div class="form-group">
                @foreach (App\Models\Tags::get() as $tag)
                <label class="col-md-2 checkbox-inline" for="tag{{$tag->id}}">
                <input type="checkbox" name="tags[]" id="tag{{$tag->id}}" value="{{$tag->id}}" @if (in_array($tag->id,$checked_tags)) checked @endif>
                {{$tag->name}}
                </label>

                @endforeach
              </div>
              </div>
              <div class="form-group">
                <label for="route">Как добраться?</label>
                <textarea  class="form-control @error('route') is-invalid @enderror" name="route" >@if (strlen(old('route'))){{old('route')}}@else{{ $poi->route }}@endif</textarea>
                <small class="form-text text-muted">На машине и общественным транспортом</small>
              </div>
              <div class="form-group">
                <label for="prim">Примечание</label>
                <textarea  class="form-control @error('prim') is-invalid @enderror" name="prim" >@if (strlen(old('prim'))){{old('prim')}}@else{{ $poi->prim }}@endif</textarea>
                <small class="form-text text-muted">Что еще хотите сообщить</small>
              </div>
              <div class="form-group">
                <label for="video">Видео</label>
                <input type="text" class="form-control @error('video') is-invalid @enderror"  name="video" value="@if (strlen(old('video'))){{old('video')}}@else{{ $poi->video }}@endif">
                <small class="form-text text-muted">https://www.youtube.com/watch?v=<b>xxxxxxx</b></small>
              </div>
              <button type="submit" class="btn btn-primary">Сохранить</button>
          </form>
        </div>
    </div>
  @endguest
</div>

@endsection
