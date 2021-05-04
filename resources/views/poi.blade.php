@extends('layouts.app')

@section('title'){{$poi->name}}@endsection

@section('content')
<div class="container">
  <h1>Страница объекта {{$poi->name}}</h1>
  <p>Автор <a href="{{ route('user', $poi->user->login )}}">{{$poi->user->name}}</a>
<nav>
<ul class="fastnav">
  <li>Как добраться
  <li>Что рядом?
  <li>Кто уже побывал?
  <li>Отзывы и комментарии
</ul>
</nav>
Фото @foreach ($poi->photos as $photo) {{ $photo }} @endforeach

Теги @foreach ($poi->tags as $tag) {{ $tag->name }}, @endforeach
    <div class="row justify-content-center">
        <div class="col-md-8">

        </div>
    </div>
</div>

@endsection
