@extends('layouts.app')
@section('title')Путешествия и маршруты - Альтернативный путеводитель @endsection
@section('content')
<div class="container">
  <h1>Путешествия и маршруты</h1>
  Показать сначала:
  @foreach ($sorts as $sort)
  @if ($request->sort==$sort['sort'] or ($request->sort=='' and $sort['sort']=='id.desc'))
  <b>{{$sort['name']}}</b>
  @else
  <a href="?sort={{$sort['sort']}}">{{$sort['name']}}</a>
  @endif
  @endforeach

  <div class="d-flex flex-wrap align-items-stretch">
  @foreach ($routes as $route)
    @include('blocks.route_card')
@endforeach
</div>
{{$routes->appends(Request::query())->links()}}
</div>

@endsection
