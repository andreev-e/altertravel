@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Главная</h1>
  <div class="map" id="map_canvas"></div>
    <div class="row" id="shown_on_map">
  @foreach ($pois as $poi)
    <div class="col-sm-2">
    <a href="{{ route('single-poi', $poi->url) }}">
    <img src="{{ $poi->photo }}" alt="{{ $poi->name }}" />
    <div class="title">{{ $poi->name }}</div>
    </a>
    </div>
@endforeach

</div>

@endsection
