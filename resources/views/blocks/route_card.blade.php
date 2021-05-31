<div class="poi p-3">
  <div class="card">
    <a href="{{ route('single-route', $route->url) }}">
    <img src="{{$route->thumb()}}" class="card-img-top" alt="{{ $route->name }}">
    <!--<img src="{{ $route->photo }}" class="card-img-top" alt="{{ $route->name }}">-->
    <div class="card-body">
    <p class="h5 card-title">{{ $route->name }}</p>
    <p class="card-text">{{ $route->author }}</p>
    <p class="card-text card-subtitle mb-2 text-muted">
      {!! str_repeat('<i class="fa fa-map-marker" aria-hidden="true"></i>',min($route->pois->count(),3)) !!}
      @if ($route->pois->count()>3)... @endif/
      <i class="fa fa-comments-o" aria-hidden="true"></i> {{ $route->comments->count() }} /
      <i class="fa fa-eye" aria-hidden="true"></i> {{ $route->views }}
      @if ($route->route )
       / <i class="fa fa-map-signs" aria-hidden="true"></i>
      @endif


    </p>
    </div>
    </a>
  </div>
</div>
