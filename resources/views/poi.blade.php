@extends('layouts.app')

@section('title'){{$poi->name}}@endsection

@section('content')
<div class="container">
  <h1>Страница объекта {{$poi->name}}</h1>
Вывод юзера через связи {{$poi->user->name}}<br>

    <div class="row justify-content-center">
        <div class="col-md-8">
            {{dd($poi)}}
        </div>
    </div>
</div>

@endsection
