@extends('layouts.app')
@section('title')Интересные {{mb_strtolower($tag->name_rod)}} на карте Альтернативного путеводителя@endsection
@section('content')
<div class="container">
  <h1>Публикации по тегу &laquo;{{$tag->name}}&raquo;</h1>
  <div class="gallery d-flex flex-wrap justify-content-between align-items-stretch">
  @foreach ($pois as $poi)
  @include('blocks.poi_card')
@endforeach
</div>
</div>

@endsection
