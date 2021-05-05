@extends('layouts.app')
@section('title')Достопримечательности {{$location->name}} @endsection
@section('content')
<div class="container">
  <ul class="breadcrumbs">
  <li><a href="{{ route ('/') }}"><i class="fa fa-home" aria-hidden="true"></i></a>
  @foreach ($breadcrumbs as $breadcrumb)<li><a href="{{ route('location', $breadcrumb['url']) }}">{{$breadcrumb['name']}}</a></li>@endforeach
<li>{{$location->name}}</li>
</ul>
  <h1>Достопримечательности {{$location->name}}</h1>

  <div class="row">

  @foreach ($pois as $poi)
    <div class="col-sm-4"><a href="{{ route('single-poi', $poi->url) }}">{{ $poi->name }}</a></div>
@endforeach
</div>
</div>

@endsection
