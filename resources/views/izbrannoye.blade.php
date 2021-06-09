@extends('layouts.app')
@section('title')Избранное @endsection
@section('content')
<div class="container">
    <div class="col-12">
        <h1>Избранное</h1>
    @if (isset($sorts))
      @include('blocks.sort')
    @endif

  @if (isset($pois))
  <div class="row">
    @foreach ($pois as $poi)
      @include('blocks.poi_card')
    @endforeach
  </div>
  {{ $pois->appends(Request::query())->onEachSide(1)->links()}}
  @endif

</div>
</div>
@endsection
