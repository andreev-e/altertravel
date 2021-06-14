<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <script src="//code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="//maps.google.com/maps/api/js?key=AIzaSyDVxoYcrB_2arTNlYpFClzzKy9KgFW3_Y8"></script>
    <script src="//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="//unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
     @stack('scripts')
    <link href="//cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
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
        <li class="nav-item">
                <a class="nav-link" href="{{route('location',['','',''])}}">Каталог</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Страны
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            @foreach (App\Models\Locations::where('type','=','country')->get() as $locaton)
              <li><a class="dropdown-item" href="{{route('location',[$locaton->url,'',''])}}"><img src="/i/flags/{{$locaton->flag}}" alt="flag"> {{$locaton->name}}</a></li>
            @endforeach
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Категории
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            @foreach (App\Models\Categories::get() as $category)
              <li><a class="dropdown-item" href="{{route('category',[$category->url,''])}}">{{$category->name}}</a></li>
            @endforeach
          </ul>
        </li>

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
                <a class="nav-link" href="{{ route('izbrannoye') }}" title="Избранное"><i class="fa fa-star-o" aria-hidden="true"></i> Избранное</a>
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

            <li class="nav-item dropdown">
                <a  class="nav-link dropdown-toggle" href="#"  id="usermenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <img height="20px" class="avatar" src="@if (Auth::user()->avatar!='-' ){{asset('/storage'.Auth::user()->avatar)}} @else /i/empty.jpg @endif"  alt="ava"> <b>{{Auth::user()->name}}</b>
                </a>
                <ul class="dropdown-menu" aria-labelledby="usermenu">
                  <li><a class="dropdown-item" href="{{ Route('my_pois') }}">Мои точки</a></li>
                  <li><a class="dropdown-item" href="{{ Route('my_routes') }}">Мои маршруты</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <a  class="nav-link" href="{{ Route('user_edit') }}"  >
                  <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
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
                  <li><a class="dropdown-item" href="{{route('import','slovar')}}">7 Справочник локаций</a></li>
                  <li><a class="dropdown-item" href="{{route('import','comments')}}">8 Комменарии</a></li>
                  <li><a class="dropdown-item" href="{{route('import','comments_fix')}}">9 Привязать комменты к юзерам</a></li>
                  <li><a class="dropdown-item" href="{{route('import','photo_objects')}}">10 Фото объектов в storage</a></li>
                  <li><a class="dropdown-item" href="{{route('import','photo_routes')}}">11 Фото маршрутов в storage</a></li>
                  <li><a class="dropdown-item" href="{{route('import','photo_avatars')}}">12 Аватарки в storage</a></li>
                  <li><a class="dropdown-item" href="{{route('import','edits')}}">13 Внесение правок</a></li>
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

        <script>
          (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-2550364618248551",
            enable_page_level_ads: true
          });
        </script>


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
