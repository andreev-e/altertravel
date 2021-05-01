@extends('layouts.app')
@section('title')Кабинет автора@endsection
@section('content')
<div class="container">
  <h1>Ваши публикации</h1>
    @guest
    <p>Вы не авторизованы
    @else
    <div class="row">
      @if (count($pois)>0)
      @foreach ($pois as $poi)
        <div class="col-sm-4"><a href="{{ route('single-poi-edit', $poi->url) }}">{{ $poi->name }}</a></div>
      @endforeach
      @else
      <div class="col-12">
        <div class="alert alert-danger">
          <p>У вас нет ни одной публикации</p>
      </div>
    </div>
      @endif
    <div class="col-12">
    <h2>Добавить публикацию</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <form method="POST" action="{{route('add')}}" enctype="multipart/form-data">
        @csrf
        <!-- поля формы -->
        <div class="form-group">
          <label for="title">Название объекта</label>
          <input type="text" class="form-control @error('title') is-invalid @enderror" name="title">
          <small>Местоположение будет определено по координатам, не указывайте его в названии</small>
        </div>

        <div class="form-group">
          <label for="lat">Широта</label>
          <input type="text" class="form-control @error('lat') is-invalid @enderror" name="lat">
        </div>
        <div class="form-group">
          <label for="lng">Долгота</label>
          <input type="text" class="form-control @error('lng') is-invalid @enderror" name="lng">
        </div>
        <div class="form-group">
          <label for="description">Подробное описание</label>
          <textarea  class="form-control @error('description') is-invalid @enderror" name="description"></textarea>
        </div>
        <div class="form-group">
          <label for="photos">Фотографии</label>
          <input multiple="multiple"  class="form-control @error('photos') is-invalid @enderror" name="photos[]" type="file">
          <small>Хотя бы одно фото</small>
        </div>
        <div class="form-group">
            <label for="category">Категория</label>
            <select class="form-control @error('category') is-invalid @enderror" name="category">
                <option value="Архитектура">Архитектура</option>
                <option value="Природа">Природа</option>
                <option value="История/Культура">История/Культура</option>
                <option value="Техноген">Техноген</option>
                <option value="Музей">Музей</option>
                <option value="Памятник">Памятник</option>
                <option value="Памятник">Ночлег</option>
                <option value="Памятник">Еда</option>
                <option value="Памятник">Покупки</option>
                <option value="Памятник">Развлечения</option>
            </select>
        </div>
        <div class="form-group">
          <label for="route">Как добраться?</label>
          <textarea  class="form-control @error('route') is-invalid @enderror" name="route"></textarea>
          <small>На машине и общественным транспортом</small>
        </div>
        <div class="form-group">
          <label for="prim">Примечание</label>
          <textarea  class="form-control @error('prim') is-invalid @enderror" name="prim"></textarea>
          <small>Что еще хотите сообщить</small>
        </div>
        <div class="form-group">
          <label for="video">Видео</label>
          <input type="text" class="form-control @error('video') is-invalid @enderror"  name="video">
          <small>https://www.youtube.com/watch?v=<b>xxxxxxx</b></small>
        </div>
        <button type="submit" class="btn btn-primary">Опубликовать</button>
    </form>
</div>
    @endguest
</div>

@endsection
