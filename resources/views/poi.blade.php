@extends('layouts.app')

@section('title'){{$poi->name}}@endsection

@section('content')

<div class="container">
  <ul class="breadcrumbs">
    <li><a href="{{ route ('/') }}"><i class="fa fa-home" aria-hidden="true"></i></a>
    @foreach ($poi->locations as $location)<li><a href="{{ route ('location',$location->url) }}">{{ $location->name }}</a></li>@endforeach
  </ul>
  <h1>{{$poi->name}}</h1>
  <p class="small">Автор публикации <a href="{{ route('user', $poi->user->login )}}">{{$poi->user->name}}</a> / @isset($poi->copyright) Автор фото {{$poi->copyright}}@endisset</p>
<nav>
<ul class="fastnav">
  <li>Как добраться
  <li>Что рядом?
  <li>Кто уже побывал?
  <li>Отзывы и комментарии
</ul>
</nav>
<img src="https://altertravel.ru/images/{{ $poi->old_id }}.jpg" class="img-fluid" alt="{{$poi->name}}"/>
@foreach ($poi->photos as $photo)

<img src="{{ Storage::url($photo) }}" alt="{{$poi->name}}"/> @endforeach
<h2>Описание</h2>
{!!$poi->description!!}
@isset($poi->route)
<h2>Как добраться</h2>
{!!$poi->route!!}
@endisset
@isset($poi->route_o)
<h2>Как добраться на общественном транспорте</h2>
{!!$poi->route_o!!}
@endisset
@isset($poi->prim)
<h2>Примечание</h2>
{!!$poi->prim!!}
@endisset
<h2>Что есть рядом</h2>
{{$poi->near}}
<h2>Ссылки</h2>
{{$poi->links}}
<h2>Комментари</h2>
{{$poi->comments}}
Теги @foreach ($poi->tags as $tag) <a href="{{ Route('tag',$tag->url) }}" class="btn btn-primary btn-sm">{{ $tag->name }}</a> @endforeach
    <div class="row justify-content-center">
        <div class="col-md-8">

        </div>
    </div>
</div>

@endsection
