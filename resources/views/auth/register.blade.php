@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Регистрация') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-1">
                            <div class="col-6 col-lg-4">
                              <label for="name" class="col-form-label">{{ __('Имя') }}</label>
                            </div>
                            <div class="col-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-1">
                          <div class="col-6 col-lg-4">
                            <label for="email" class="col-form-label">{{ __('E-Mail') }}</label>
                          </div>

                            <div class="col-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-1">
                          <div class="col-6 col-lg-4">
                            <label for="password" class="col-form-label">{{ __('Пароль') }}</label>
                          </div>

                            <div class="col-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-6 col-lg-4">
                              <label for="password-confirm" class="col-form-label">{{ __('Подтверждение пароля') }}</label>
                            </div>

                            <div class="col-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-1 text-center">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Зарегистрироваться') }}
                                </button>
                            </div>
                        </div>
                    </form>


                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                          <p class="h4">Вход через социальные сети</p>
                          @if (Route::has('oauth'))
                          <a href="{{route('oauth','google')}}" class="btn btn-primary">
                              <i class="fa fa-google" aria-hidden="true"></i>
                          </a>
                          <a href="{{route('oauth','facebook')}}" class="btn btn-primary">
                              <i class="fa fa-facebook" aria-hidden="true"></i>
                          </a>
                          <a href="{{route('oauth','yandex')}}" class="btn btn-primary">
                              Yandex
                          </a>
                          @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
