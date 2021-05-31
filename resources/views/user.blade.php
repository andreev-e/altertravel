@extends('layouts.app')
@section('title')Публикации автора {{$user->name}} @endsection
@section('content')
<div class="container">
  <h1>Публикации автора {{$user->name}}</h1>
  <img height="50px" class="avatar" src="{{ ((strpos ( $user->avatar_original , '//')>0)?$user->avatar_original:Storage::url($user->avatar_original))}}" alt="ava">
  Показать сначала:
  @foreach ($sorts as $sort)
  @if ($request->sort==$sort['sort'] or ($request->sort=='' and $sort['sort']=='id.desc'))
  <b>{{$sort['name']}}</b>
  @else
  <a href="?sort={{$sort['sort']}}">{{$sort['name']}}</a>
  @endif
  @endforeach
  <div class="gallery d-flex flex-wrap justify-content-between align-items-stretch">
  @foreach ($pois as $poi)
    @include('blocks.poi_card')
@endforeach
</div>
{{$pois->appends(Request::query())->links()}}
</div>

@endsection
