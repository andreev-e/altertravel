@extends('layouts.app')
@section('title')Публикации автора @endsection
@section('content')
<div class="container">
  <h1>Публикации автора</h1>
  <div class="row">
  @foreach ($pois as $poi)
    <div class="col-sm-4"><a href="{{ route('single-poi', $poi->url) }}">{{ $poi->name }}</a></div>
@endforeach
</div>
</div>

@endsection
