@extends('layouts.app')
@section('title')Публикации автора {{$user->name}} @endsection
@section('content')
<div class="container">
  <h1>Публикации автора {{$user->name}}</h1>
  <img height="50px" class="avatar" src="{{ ((strpos ( $user->avatar_original , '//')>0)?$user->avatar_original:Storage::url($user->avatar_original))}}" alt="ava">
  <div class="row">
  @foreach ($pois as $poi)
    <div class="col-sm-4"><a href="{{ route('single-poi', $poi->url) }}">{{ $poi->name }}</a></div>
@endforeach
</div>
</div>

@endsection
