@extends('layouts.app')
@section('title')Редактор: {{$poi->name}}@endsection
@section('content')
<div class="container">
  @guest
  <p>Вы не авторизованы
  @else
  <h1>Страница редактирования объекта {{$poi->id}}</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">

        </div>
    </div>
  @endguest
</div>

@endsection
