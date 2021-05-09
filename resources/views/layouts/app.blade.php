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
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ route('/') }}/i/l.png" alt="Альтернативный путеводитель">Альтернативный путеводитель
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">


                      @if (Route::has('catalog'))
                          <li class="nav-item dropdown">
                              <a class="nav-link  dropdown-toggle"  id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Страны</a>

                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                          @foreach (App\Models\Locations::where('type','=','country')->get() as $locaton)
                            <a class="dropdown-item" href="{{route('location',$locaton->url)}}">{{$locaton->name}}</a>
                          @endforeach
                          </div>
                          </li>
                      @endif
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
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
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
