@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Кабинет автора</h1>
    @guest
    <p>Вы не авторизованы
    @else
    <div class="row">
      @foreach ($pois as $poi)
        <div class="col-sm-4"><a href="{{ route('single-poi-edit', $poi->url) }}">{{ $poi->name }}</a></div>
      @endforeach
    </div>
    @endguest
</div>

@endsection
