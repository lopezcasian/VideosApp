@extends('layouts.app')

@section('content')

    <div class="col-md-10 offset-md-1">
    	<h2>{{ $video->title }}</h2>
        <hr />
        <div class="col-md-8">
            <!-- Video -->

            <video controls id="video-player">
                <source src="{{ route('videos.get.video', ['filename' => $video->video_path]) }}" type="video/mp4" />
                Your browser is not compatible with HTLM5.
            </video>

            <!-- Description -->
            <div class="card video-data">
                <div class="card-header">
                    <div class="card-title">
                        Uploaded by <strong><a href="{{ route('users.show', ['user_id' => $video->user->id]) }}">{{ $video->user->name . ' ' . $video->user->surname }}</a></strong> {{ \FormatTime::LongTimeFilter($video->created_at)}}
                    </div>
                </div>
                <div class="card-body">
                    {{ $video->description }}
                </div>
            </div>
            <br />
            <!-- Comments -->
            @include('video.comments')

    	</div>
    </div>
@endsection