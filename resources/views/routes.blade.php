@extends('layouts.app')
@section('title')Путешествия и маршруты - Альтернативный путеводитель @endsection
@section('content')
<div class="container">
  <h1>Путешествия и маршруты</h1>
  @if (isset($sorts))
    @include('blocks.sort')
  @endif
  <div class="gallery d-flex flex-wrap justify-content-between align-items-stretch">
  @foreach ($routes as $route)
    @include('blocks.route_card')
@endforeach
</div>
{{$routes->appends(Request::query())->links()}}
</div>

@endsection
