@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Каталог</h1>
  <div class="row">
  @foreach ($pois as $poi)
    <div class="col-sm-4"><a href="/place/{{ $poi->url }}">{{ $poi->name }}</a></div>
@endforeach
</div>
</div>

@endsection
