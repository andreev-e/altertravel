@extends('layouts.app')
@section('title')Путешествия и маршруты - Альтернативный путеводитель @endsection
@section('content')
<div class="container">
  <h1>Путешествия и маршруты</h1>
  <div class="row">
  @foreach ($routes as $route)
    <div class="col-sm-4"><a href="{{ route('single-route', $route->url) }}">{{ $route->name }}</a></div>
@endforeach
</div>
{{$routes->appends(Request::query())->links()}}
</div>

@endsection
