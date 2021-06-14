@push('scripts')
<script type="text/javascript">
$(document).ready(function(){
  $('.owl-carousel').owlCarousel({
    loop:false,
    margin:10,
    nav:false,
    items:4,
    autoWidth:true,
    autoHeight: false,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:4
        }
    }
})
});
</script>
@endpush
@extends('layouts.app')

@section('title')Маршрут {!!$route->name!!} @endsection

@section('content')

<div class="container">
  <ul class="breadcrumbs">

  </ul>
  <h1>{!!$route->name!!}</h1>
  <p class="small">Автор маршрута <a href="{{ route('user', $route->user->login )}}">{{$route->user->name}}</a>
   / {{$route->views}} просмотров</p>

<div class="owl-carousel owl-theme">
  @foreach ($route->gallery() as $photo)
  <a href="{{ Storage::url($photo) }}" data-fancybox="photoes" >
      <img src="{{ Storage::url($photo) }}" alt="{{$route->name}}" style="height:300px; width:auto;"/>
  </a>
  @endforeach
</div>

<h2>Карта</h2>
<div class="map" id="map"></div>
<h2>Описание</h2>
{!!$route->description!!}
@isset($route->prim)
<h2>Примечание</h2>
{!!$route->prim!!}
@endisset
<h2>Ссылки</h2>
{{$route->links}}
<h2>Комментарии</h2>
{{$route->comments}}

<h2>Точки</h2>
  <div class="d-flex flex-wrap align-items-stretch">
@foreach ($route->pois as $poi)
  @include('blocks.poi_card')
@endforeach
</div>
</div>
</div>
@endsection
