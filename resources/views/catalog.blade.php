@extends('layouts.app')
@section('title')Каталог - Альтернативный путеводитель @endsection
@section('content')
<div class="container">
  <h1>Каталог</h1>
  <div class="sort"><span>Показать сначала:</span>
  @foreach ($sorts as $sort)
  @if ($request->sort==$sort['sort'] or ($request->sort=='' and $sort['sort']=='id.desc'))
  <b>{{$sort['name']}}</b>
  @else
  <a href="?sort={{$sort['sort']}}">{{$sort['name']}}</a>
  @endif
  @endforeach
  </div>
  <div class="gallery d-flex flex-wrap justify-content-between align-items-stretch">
  @foreach ($pois as $poi)
  @include('blocks.poi_card')
@endforeach
</div>
{{$pois->appends(Request::query())->links()}}
</div>

@endsection
