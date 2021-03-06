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


     $(document).ready(function (e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
        });

        $('#formFile').change(function(){

        for(var i=0; i< this.files.length; i++){
            var file = this.files[i];
            let reader = new FileReader();

            reader.onload = (e) => {
              $('#image_preview_container').append('<img src="'+e.target.result+'" width="200px">');
            }

            reader.readAsDataURL(this.files[i]);

            console.log(this.files[i]);

            var formData = new FormData();
            formData.append('image', this.files[i]);

            $.ajax({
               type:'POST',
               url: "{{ route('photoes.store') }}",
               data: formData,
               cache:false,
               contentType: false,
               processData: false,
               success: (data) => {
                   $('#response').html(data);
               },
               error: function(data){
                  $('#response').html(data);
                }
              });

        }




        });

     });

</script>
@endpush

@extends('layouts.app')
@section('title')???????????????????? ????????????????????@endsection
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

            <form method="POST" action="{{route('pois.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="name">???????????????? ??????????????</label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                  <small class="form-text text-muted">???????????????????????????? ?????????? ???????????????????? ???? ??????????????????????, ???????????????????? ???? ???????????????????? ?????? ?? ????????????????</small>
                </div>
                <p>?????????????? ?????????? ???? ??????????

                <div class="form-row">
                  <div class="col-8"><div style="height:300px;" id="map_guide"></div></div>
                  <div class="col-4">
                    <label for="lat">????????????</label>
                    <input type="text" id="currentmarkerlat" class="form-control @error('lat') is-invalid @enderror"  name="lat" value="{{ old('lat') }}">
                    <label for="lng">??????????????</label>
                    <input type="text" id="currentmarkerlng" class="form-control @error('lng') is-invalid @enderror"  name="lng" value="{{ old('lng') }}">
                  </div>
                </div>
                <div class="form-group">
                  <label for="description">?????????????????? ????????????????</label>
                  <textarea  class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="category">??????????????????</label>
                    <select class="form-select" @error('category') is-invalid @enderror" name="category">
                        @foreach (App\Models\Categories::get() as $category)
                        <option value="{{$category->id}}" @if (old('category')==$category->id) selected @endif>{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                  <label for="checkboxes">??????????</label>
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
                  <label for="route">?????? ???????????????????</label>
                  <textarea  class="form-control @error('route') is-invalid @enderror" name="route" >{{ old('route') }}</textarea>
                  <small class="form-text text-muted">???? ???????????? ?? ???????????????????????? ??????????????????????</small>
                </div>
                <div class="form-group">
                  <label for="prim">????????????????????</label>
                  <textarea  class="form-control @error('prim') is-invalid @enderror" name="prim" >{{ old('prim') }}</textarea>
                  <small class="form-text text-muted">?????? ?????? ???????????? ????????????????</small>
                </div>
                <div class="form-group">
                  <label for="links">????????????</label>
                  <textarea  class="form-control @error('links') is-invalid @enderror" name="links" >{{ old('links') }}</textarea>
                </div>
                <div class="form-group">
                  <label for="video">??????????</label>
                  <input type="text" class="form-control @error('video') is-invalid @enderror"  name="video" value="{{ old('video') }}">
                  <small class="form-text text-muted">https://www.youtube.com/watch?v=<b>xxxxxxx</b></small>
                </div>
                <div class="form-group">
                  <label for="formFile" class="form-label">?????????????????? ????????????????????</label>
                  <input multiple="multiple" class="form-control @error('photos') is-invalid @enderror" name="photos[]" type="file" id="formFile">
                  <small class="form-text text-muted">???????? ???? ???????? ????????</small>
                  <div id="image_preview_container"></div>
                  @foreach ($files as $file)
                    <img src="{{asset($file)}}" alt="foto #{{ $loop->iteration }} {{$file}}">
                  @endforeach
                </div>
                <button type="submit" class="btn btn-primary">????????????????????????</button>
            </form>


    @endguest
</div>

@endsection
