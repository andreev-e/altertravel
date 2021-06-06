<div class="m-auto">
  <h1>Вы не авторизованы</h1>
  <div class="btn-group">
  @if (Route::has('login'))
          <a class="btn btn-primary" href="{{ route('login') }}">{{ __('Вход') }}</a>
  @endif

  @if (Route::has('register'))
          <a class="btn btn-secondary" href="{{ route('register') }}">{{ __('Регистрация') }}</a>
  @endif
  </div>
</div>
