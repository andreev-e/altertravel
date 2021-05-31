@extends('layouts.app')
@section('title')Публикации автора @endsection
@section('content')
<div class="container">
  <h1>Авторы путеводителя</h1>
  <div class="row">
    <div class="col-sm-12">
      <p>Альтернативный путеводитель — проект авторский. Все достопримечательности добавляются только членами нашего творческого коллектива. Как правило, этому предшествует исследовательская работа и всегда — личное посещение места.
<p>Нас объединяет любовь к путешествиям, необычным и красивым местам. Особое внимание уделяется качеству фотографий и текстового описания. Для одних из нас путешествия - работа, для других хобби. Главное, что вся представленная информация проверена на личном опыте.
      <table class="table">
  @foreach ($users as $user)
    @if ($loop->first)<tr class="table-warning">
    @elseif ($loop->iteration==2)<tr class="table-secondary">
    @elseif ($loop->iteration==3)<tr class="table-danger">
    @endif
      <td><img src="{{asset('/storage'.$user->avatar)}}" width="200px" alt="{{$user->login}}" class="img-fluid rounded ">
      <td><a href="{{ route('user', $user->login) }}">{{ $user->name }}</a>
      <td>{{$user->about}}
      <td><a href="//{{$user->site}}" target="_blank">{{$user->site}}</a>
      </tr>
@endforeach
</table>
{{$users->links()}}
</div>
</div>
</div>

@endsection
