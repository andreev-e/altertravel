<div class="sort"><span>Показать сначала:</span>
  @foreach ($sorts as $sort)
    @if ($request->sort==$sort[0] or ($request->sort=='' and $sort[0]=='id.desc'))
    <b>{{$sort[1]}}</b>
    @else
    <a href="?sort={{$sort[0]}}">{{$sort[1]}}</a>
    @endif
  @endforeach
</div>
