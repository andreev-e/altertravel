@push('scripts')

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
<nav>
<ul class="fastnav">
  <li>Отзывы и комментарии
</ul>
</nav>
<img src="https://altertravel.ru/images/{{ $route->old_id }}.jpg" class="img-fluid" alt="{{$route->name}}"/>
@foreach ($route->photos as $photo)
<img src="{{ Storage::url($photo) }}" alt="{{$route->name}}"/>
@endforeach
<h2>Карта</h2>
<div class="map" id="map"></div>
<h2>Описание</h2>
{!!$route->description!!}
@isset($route->route)
<h2>Как добраться</h2>
{!!$route->route!!}
@endisset
@isset($route->prim)
<h2>Примечание</h2>
{!!$route->prim!!}
@endisset
<h2>Ссылки</h2>
{{$route->links}}
<h2>Комментари</h2>
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
