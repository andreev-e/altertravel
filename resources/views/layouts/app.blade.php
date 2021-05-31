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
        <img src="{{ route('/') }}/i/l.png" alt="Альтернативный путеводитель">
        Altertravel.ru
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
              <li><a class="dropdown-item" href="{{route('location',[$locaton->url,''])}}"><img src="/i/flags/{{$locaton->flag}}" alt="flag"> {{$locaton->name}}</a></li>
            @endforeach
          </ul>
        </li>
        @if (Route::has('catalog'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('catalog')}}">Каталог</a>
            </li>
        @endif
        @if (Route::has('routes'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('routes')}}">Маршруты</a>
            </li>
        @endif
        @if (Route::has('users'))
            <li class="nav-item d-none d-xl-block">
                <span class="nav-link">|</span>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('users')}}">Авторы</a>
            </li>
            <hr>
        @endif
      </ul>
      <ul class="navbar-nav d-flex">

        @if (Route::has('izbrannoye'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('izbrannoye') }}" title="Избранное"><i class="fa fa-star-o" aria-hidden="true"></i></a>
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
                <a  class="nav-link" href="#"  >
                  <img height="20px" class="avatar" src="{{ ((strpos ( Auth::user()->avatar , '//')>0)?Auth::user()->avatar:Storage::url(Auth::user()->avatar))}}" alt="ava"> <b>{{Auth::user()->name}}</b>
                </a>
              </li>
              <li class="nav-item">
                <a  class="nav-link" href="{{ Route('user_edit') }}"  >
                  <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
                </li>
            <li class="nav-item">
              <a  class="nav-link" href="{{ Route('secure') }}"  >
                Публикации
              </a>
            </li>
            @if (Auth::user()->email=='andreev-e@mail.ru')
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="servicemenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Сервис
              </a>
              <ul class="dropdown-menu" aria-labelledby="servicemenu">
                  <li><a class="dropdown-item" href="{{route('import','users')}}">1 Импорт пользователей</a></li>
                  <li><a class="dropdown-item" href="{{route('import','tags')}}">2 Импорт меток</a></li>
                  <li><a class="dropdown-item" href="{{route('import','poi')}}">3 Импорт точек</a></li>
                  <li><a class="dropdown-item" href="{{route('import','routes')}}">4 Импорт маршрутов</a></li>
                  <li><a class="dropdown-item" href="{{route('import','rel')}}">5 Отношения точка тег</a></li>
                  <li><a class="dropdown-item" href="{{route('import','locating')}}">6 Парсинг локаций</a></li>
                  <li><a class="dropdown-item" href="{{route('import','slovar')}}">7 Справочник</a></li>
                  <li><a class="dropdown-item" href="{{route('import','comments')}}">8 Комменарии</a></li>
                  <li><a class="dropdown-item" href="{{route('import','comments_fix')}}">9 Привязать комменты к юзерам</a></li>
                  <li><a class="dropdown-item" href="{{route('import','photo_objects')}}">10 Фото объектов в storage</a></li>
                  <li><a class="dropdown-item" href="{{route('import','photo_routes')}}">11 Фото маршрутов в storage</a></li>
                  <li><a class="dropdown-item" href="{{route('import','photo_avatars')}}">12 Аватарки в storage</a></li>

              </ul>
            </li>
            @endif
            <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
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

        <!-- Yandex.Metrika counter -->
        <script type="text/javascript" >
           (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
           m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
           (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

           ym(10896850, "init", {
                clickmap:true,
                trackLinks:true,
                accurateTrackBounce:true
           });
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/10896850" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->

</body>
</html>
