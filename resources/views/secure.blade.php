@push('scripts')

@endpush

@extends('layouts.app')
@section('title')Кабинет автора@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <h1>Ваши публикации</h1>
        </div>
        @guest
        @else
            <div class="col-sm-6 text-end">
                <div class="btn-group">
                    <a class="btn btn-primary" href="{{ route('pois.create') }}">Добавить точку</a>
                    <a class="btn btn-secondary" href="{{ route('routes.create') }}">Добавить маршурт</a>
                </div>
            </div>
        @endguest
    </div>
    @guest
    <p>Вы не авторизованы
    @else
    <div class="row">
      <div class="col-12">
      <table class="table table-stried">
      @if (count($pois)>0)
      @foreach ($pois as $poi)
        <tr>
        <td>
          <a href="{{ route('single-poi', $poi->url) }}"><b>{{ $poi->name }}</b></a>
          <a  target="_blank" href="{{ route('single-poi', $poi->url) }}"><i class="fa fa-external-link" aria-hidden="true"></i></a>
        </td>
        <td>
          @if (isset($poi->category->name)){{ ($poi->category->name) }}@endif
        </td>
        <td>
          @foreach ($poi->tags as $tag)
            <a href="{{ route('tag', [$tag->url,'']) }}">{{ $tag->name }}</a>{{ ($loop->last ? '' : ',') }}
          @endforeach
        </td>
      @if ($poi->status==1)
        <td>
          <form id="hide{{$poi->id}}" action="{{ route('pois.hide', $poi->id) }}" method="post">
          @csrf
          <button type="submit" class="btn btn-outline-secondary" title="Скрыть публикацию">
            <i class="fa fa-eye-slash" aria-hidden="true"></i>
          </button>
          </form>
        </td>
      @endif
      @if ($poi->status==0)
        <td>
          <form id="show{{$poi->id}}" action="{{ route('pois.publish', $poi->id) }}" method="post">
            @csrf
          <button type="submit" class="btn btn-outline-success" title="Опубликовать на сайте">
            <i class="fa fa-eye" aria-hidden="true"></i>
          </button>
          </form>
        </td>
      @endif
      <td>
        <a href="{{ route('pois.edit', $poi->id) }}" class="btn btn-warning" title="Отредактировать">
          <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        </a>
      <td>
        <form id="delete{{$poi->id}}" action="{{ route('pois.destroy', $poi->id) }}" method="post">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger" title="Удалить"><i class="fa fa-times" aria-hidden="true"></i></button></td>
        </form>
      </div>
      @endforeach
      </table>
      @if (method_exists($pois,'links')) {{$pois->links()}} @endif

    </div>
      @else
      <div class="col-12">
        <div class="alert alert-danger">
          <p>У вас нет ни одной точки</p>
          <p><a class="btn btn-success" href="{{route('my_pois_add')}}">Добавить точку</a>
      </div>
    </div>
      @endif

    @endguest
</div>

@endsection
