<div class="poi p-3">
  <div class="card">
    <a href="{{ route('single-poi', $poi->url) }}">
    <img src="https://altertravel.ru/thumb.php?f=/images/{{ $poi->old_id }}.jpg" class="card-img-top" alt="{{ $poi->name }}">
    <!--<img src="{{ $poi->photo }}" class="card-img-top" alt="{{ $poi->name }}">-->
    <div class="card-body">
    <p class="h5 card-title">{{ $poi->name }}</p>
    <p class="card-text">{{ $poi->author }}</p>
    <p class="card-text card-subtitle mb-2 text-muted">
      <i class="fa fa-comments-o" aria-hidden="true"></i> {{ $poi->comments->count() }} /
      <i class="fa fa-road" aria-hidden="true"></i> {{ $poi->routes->count() }} /
      <i class="fa fa-eye" aria-hidden="true"></i> {{ $poi->views }}
    </p>
    </div>
    </a>
  </div>
</div>
