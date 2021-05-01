@extends('layouts.app')

@section('title'){{$poi->name}}@endsection

@section('content')
<div class="container">
  <h1>Страница объекта {{$poi->name}}</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">

        </div>
    </div>
</div>

@endsection
