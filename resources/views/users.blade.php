@extends('layouts.app')
@section('title')Публикации автора @endsection
@section('content')
<div class="container">
  <h1>Авторы путеводителя</h1>
  <div class="row">
  @foreach ($users as $user)
    <div class="col-sm-12"><a href="{{ route('user', $user->login) }}">{{ $user->name }}</a></div>
@endforeach
</div>
</div>

@endsection
