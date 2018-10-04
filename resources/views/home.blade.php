@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="container">
            @if (session('message'))
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            <div id="videos-list">
                @foreach($videos as $video)
                <div class="video-item col-md-10 float-left card mb-2">
                    <div class="card-body">
                        <!-- Video thumbnail -->
                        @if(Storage::disk('images')->has($video->image))
                            <div class="video-image-thumb col-md-3 float-left">
                                <div class="video-image-mask">
                                    <img class="card-img-top" src="{{ url('/thumbnail/' . $video->image) }}" />
                                </div>
                            </div>
                        @endif
                        <div class="data">
                            <h4 class="video-title"><a href="">{{ $video->title }}</a></h4>
                            <p>{{ $video->user->name . ' ' . $video->user->surname}}</p>
                        </div>
                        <!-- Action buttons -->
                        <a class="btn btn-success btn-sm" href="">See</a>
                        @if(Auth::check() && Auth::user()->id == $video->user->id)
                            <a class="btn btn-warning btn-sm" href="">Edit</a>
                            <a class="btn btn-danger btn-sm" href="">Delete</a>
                        @endif

                    </div>
                </div>
                @endforeach
            </div>
        </div>
        {{ $videos->links() }}
    </div>
</div>
@endsection
