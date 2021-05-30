<div class="comment p-3">
  <div class="card" id="comment{{$comment->id}}">
    @if (isset($pois))
    <a href="{{ route('single-poi',$comment->poi->url) }}">
    @endif
    <div class="card-body text-start">
    <p class="h4 card-title ">
      @if ($comment->user_id==null)
      @if ($comment->email=='')
      Аноним
      @else
      {{ App\Http\Controllers\Controller::email_partial_hide($comment->email)}}
      @endif
      @else
      <a href="{{ route('user',$comment->user->login)}}"> {{ $comment->user->name}} </a>
      @endif
    </p>
    <p class="card-text card-subtitle mb-2 text-muted">{{ $comment->created_at}}
      @if ($comment->created_at!=$comment->updated_at)
      , отредактировано {{ $comment->updated_at}}
      @endif
    </p>

    <p class="card-text">{{ $comment->comment }}</p>
    </div>
    @if (isset($pois))
    </a>
    @endif
  </div>
</div>
