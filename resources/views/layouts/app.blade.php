<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- Scripts -->
    <script src="//maps.google.com/maps/api/js?key=AIzaSyDVxoYcrB_2arTNlYpFClzzKy9KgFW3_Y8"></script>
    <script src="{{ mix('js/app.js') }}" defer></script>

     @stack('scripts')
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ route('/') }}/i/l.png" alt="Альтернативный путеводитель">Альтернативный путеводитель
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Страны
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            @foreach (App\Models\Locations::where('type','=','country')->get() as $locaton)
              <li><a class="dropdown-item" href="{{route('location',$locaton->url)}}">{{$locaton->name}}</a></li>
            @endforeach
          </ul>
        </li>
        @if (Route::has('new'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('new')}}">Новое</a>
            </li>
        @endif
        @if (Route::has('popular'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('popular')}}">Популярное</a>
            </li>
        @endif
        @if (Route::has('routes'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('routes')}}">Маршруты</a>
            </li>
        @endif
        @if (Route::has('users'))
            <li class="nav-item d-none d-md-block">
                <span class="nav-link">|</span>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('users')}}">Авторы</a>
            </li>
        @endif
      </ul>
      <ul class="navbar-nav d-flex">

        @if (Route::has('izbrannoye'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('izbrannoye') }}"><i class="fa fa-star-o" aria-hidden="true"></i> {{ __('Избранное') }}</a>
            </li>
            <li class="nav-item d-none d-md-block">
              <span class="nav-link">|</span>
            </li>
        @endif

        @guest
            @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Вход') }}</a>
                </li>
            @endif

            @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                </li>
            @endif
        @else
            <li class="nav-item">
                <a  class="nav-link" href="{{ Route('secure') }}"  >
                    Мои публикации
                </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('(Выход)') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
            </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>


        <main class="py-4">
            @yield('content')
        </main>
</body>
</html>
