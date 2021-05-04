@extends('layouts.app')
@section('title')Публикации по тегу @endsection
@section('content')
<div class="container">
  <h1>Публикации по тегу</h1>
  <div class="row">
  @foreach ($pois as $poi)
    <div class="col-sm-4"><a href="{{ route('single-poi', $poi->url) }}">{{ $poi->name }}</a></div>
@endforeach
</div>
</div>

@endsection
