@extends('layouts.app')
@section('title')Каталог - Альтернативный путеводитель @endsection
@section('content')
<div class="container">
  <h1>Каталог</h1>
  Показать сначала:
  @foreach ($sorts as $sort)
  @if ($request->sort==$sort['sort'] or ($request->sort=='' and $sort['sort']=='id.desc'))
  <b>{{$sort['name']}}</b>
  @else
  <a href="?sort={{$sort['sort']}}">{{$sort['name']}}</a>
  @endif
  @endforeach
  <div class="row">
  @foreach ($pois as $poi)
    <div class="col-sm-4"><a href="{{ route('single-poi', $poi->url) }}">{{ $poi->name }}</a></div>
@endforeach
</div>
{{$pois->links()}}
</div>

@endsection
