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
                            <h4 class="video-title"><a href="{{ route('detailVideo', ['video_id' => $video->id]) }}">{{ $video->title }}</a></h4>
                            <p>{{ $video->user->name . ' ' . $video->user->surname }}</p>
                        </div>
                        <!-- Action buttons -->
                        <a class="btn btn-success btn-sm" href="">See</a>
                        @if(Auth::check() && Auth::user()->id == $video->user->id)
                            <a class="btn btn-warning btn-sm" href="">Edit</a>
                            <a href="#videoModal{{$video->id}}" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
                            
                            <div id="videoModal{{$video->id}}" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Delete video</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Do you want to delete this video?</p>
                                            <p><small>{{ $video->title }}</small></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <a href="{{ url('/delete-video/'. $video->id) }}" type="button" class="btn btn-danger">Eliminar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
