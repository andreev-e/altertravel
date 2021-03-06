@extends('layouts.app')
@section('title')Публикации автора {{$user->name}} @endsection
@section('content')
<div class="container">
  <h1>Публикации автора {{$user->name}} <img height="50px" class="avatar" src="@if ($user->avatar!='-' ){{asset('/storage'.$user->avatar)}} @else /i/empty.jpg @endif"  alt="{{$user->login}}" alt="ava"></h1>

  @if (isset($sorts))
    @include('blocks.sort')
  @endif
  <div class="gallery d-flex flex-wrap justify-content-between align-items-stretch">
  @foreach ($pois as $poi)
    @include('blocks.poi_card')
@endforeach
</div>
{{$pois->appends(Request::query())->links()}}
</div>

@endsection
