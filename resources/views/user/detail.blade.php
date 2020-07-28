@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="container">
          @if(Auth::check() && Auth::user()->id == $user->id)
            <button>Edit account</button>
          @endif
          <div class="video-image-mask">
              <img class="card-img-top" src="{{ url('/profile/' . $user->image) }}" />
          </div>

        	<h2>{{ $user->name . $user->surname}}</h2>

        	<div class="clearfix"></div>

          @include('video.list') 

        </div>
    </div>
</div>
@endsection