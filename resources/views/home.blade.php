@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are not logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-6">
<h1>Главная</h1>
    </div>
    <div class="col-6 d-none">
<h2>JQuery работает</h2>
<script type="text/javascript">
alert($('h2').text());
</script>
    </div>
  </div>
</div>

@endsection
