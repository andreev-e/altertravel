@push('scripts')

@endpush

@extends('layouts.app')
@section('title')Кабинет автора@endsection
@section('content')
<div class="container">
  <div class="row">
  <div class="col-sm-9"><h1>Ваши публикации</h1></div>
  @guest
  @else
  <div class="col-sm-3 text-end">
    <a class="btn btn-success" href="{{route('my_pois_add')}}">Добавить точку</a>
      <a class="btn btn-success" href="{{route('my_routes_add')}}">Добавить маршурт</a>
  </div>
  @endguest
</div>
    @guest
    <p>Вы не авторизованы
    @else
    <div class="row">
      <div class="col-12">
      <table class="table table-stried">
      @if (count($routes)>0)
      @foreach ($routes as $route)
        <tr>
        <td>
          <a href="{{ route('single-route', $route->url) }}"><b>{{ $route->name }}</b></a>
          <a  target="_blank" href="{{ route('single-route', $route->url) }}"><i class="fa fa-external-link" aria-hidden="true"></i></a>
        </td>
      @if ($route->status==1)
        <td>
          <form id="hide{{$route->id}}" action="{{ route('route-hide', $route->id) }}" method="post">
          @csrf
          <button type="submit" class="btn btn-outline-secondary" title="Скрыть публикацию">
            <i class="fa fa-eye-slash" aria-hidden="true"></i>
          </button>
          </form>
        </td>
      @endif
      @if ($route->status==0)
        <td>
          <form id="show{{$route->id}}" action="{{ route('route-show', route->id) }}" method="post">
            @csrf
          <button type="submit" class="btn btn-outline-success" title="Опубликовать на сайте">
            <i class="fa fa-eye" aria-hidden="true"></i>
          </button>
          </form>
        </td>
      @endif
      <td>
        <a href="{{ route('single-route-edit', $route->id) }}" class="btn btn-warning" title="Отредактировать">
          <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        </a>
      <td>
        <form id="delete{{$route->id}}" action="{{ route('route-delete', $route->id) }}" method="post">
        @csrf
        <button type="submit" class="btn btn-outline-danger" title="Удалить"><i class="fa fa-times" aria-hidden="true"></i></button></td>
        </form>
      </div>
      @endforeach
      </table>
      @if (method_exists($route,'links')) {{$route->links()}} @endif

    </div>
      @else
      <div class="col-12">
        <div class="alert alert-danger">
          <p>У вас нет ни одного маршрута</p>
          <p><a class="btn btn-success" href="{{route('my_routes_add')}}">Добавить маршрут</a>
      </div>
    </div>
      @endif

    @endguest
</div>

@endsection
