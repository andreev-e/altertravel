@push('scripts')

@endpush

@extends('layouts.app')
@section('title')Редактор: личные данные @endsection
@section('content')
<div class="container">
  @guest
  <p>Вы не авторизованы
  @else
  <h1>Редактор профиля {{ $user->name }}</h1>
  <img height="50px" class="avatar" src="{{ ((strpos ( $user->avatar_original , '//')>0)?$user->avatar_original:Storage::url($user->avatar_original))}}" alt="ava">

  @if ($errors->any())
  <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
  @endif

    <div class="row justify-content-center">
        <div class="col-12">
          <form method="POST" action="{{route('user_edit')}}" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="@if (strlen(old('name'))){{old('name')}}@else{{ $user->name }}@endif">
              </div>
              <div class="form-group">
                <label for="site">Сайт</label>
                <input type="text" class="form-control @error('site') is-invalid @enderror" name="site" value="@if (strlen(old('site'))){{old('site')}}@else{{ $user->site }}@endif">
              </div>
              <div class="form-group">
                <label for="login">Логин (используется в адресе личной страницы)</label>
                <input type="text" class="form-control @error('login') is-invalid @enderror" name="login" value="@if (strlen(old('login'))){{old('login')}}@else{{ $user->login }}@endif">
              </div>
              <div class="form-group">
                <label for="about">Обо мне</label>
                <textarea  class="form-control @error('about') is-invalid @enderror" name="about">@if (strlen(old('about'))){{old('about')}}@else{{ $user->about }}@endif</textarea>
              </div>
              <div class="form-group">
                <label for="avatar_full" class="form-label">Загрузите аватарку</label>
                <input class="form-control @error('avatar_full') is-invalid @enderror" name="avatar_full" type="file">
              </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
          </form>
        </div>
    </div>
  @endguest
</div>

@endsection
