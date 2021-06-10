<div class="comment p-3" @if (isset($pois)) style="cursor:pointer;" onclick="location.href='{{ route('single-poi',$comment->poi->url) }}'" @endif>
  <div class="card flex-row" id="comment{{$comment->id}}">
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
      <img class="comment_card_thumb d-none d-sm-block" src="{{$comment->poi->thumb()}}"  alt="{{ $comment->poi->name }}">
    @endif

      @guest
      @else
        @if ($comment->user_id==Auth::user()->id or Auth::user()->email=='andreev-e@mail.ru')
          <form action="{{ route('pois_comments_delete', $comment->id) }}" method="post">
          @csrf
          <button type="submit" class="btn btn-outline-danger" title="Удалить"><i class="fa fa-times" aria-hidden="true"></i></button>
          </form>
        @endif

        @if (Auth::user()->email=='andreev-e@mail.ru' and $comment->status!=1)
          <form  action="{{ route('pois_comments_approve', $comment->id) }}" method="post">
          @csrf
          <button type="submit" class="btn btn-outline-success" title="Принять"><i class="fa fa-check" aria-hidden="true"></i></button>
          </form>
          <form  action="{{ route('pois_comments_delete_all', $comment->id) }}" method="post">
          @csrf
          <button type="submit" class="btn btn-outline-danger" title="Все с данной почтой удалить"><i class="fa fa-times" aria-hidden="true"></i><i class="fa fa-times" aria-hidden="true"></i></button>
          </form>
        @endif
      @endguest
  </div>


</div>
