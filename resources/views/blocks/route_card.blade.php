<div class="poi p-3">
  <div class="card">
    <a href="{{ route('single-route', $route->url) }}">
    <img src="https://altertravel.ru/thumb.php?f=/routes/{{ $route->old_id }}/1.jpg" class="card-img-top" alt="{{ $route->name }}">
    <!--<img src="{{ $route->photo }}" class="card-img-top" alt="{{ $route->name }}">-->
    <div class="card-body">
    <p class="h5 card-title">{{ $route->name }}</p>
    <p class="card-text">{{ $route->author }}</p>
    </div>
    </a>
  </div>
</div>
