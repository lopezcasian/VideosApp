<div id="videos-list">
    @if(count($videos) >= 1)
    @foreach($videos as $video)
    <div class="video-item col-md-10 float-left card mb-2">
        <div class="card-body">
            <!-- Video thumbnail -->
            @if( $video->image )
                <div class="video-image-thumb col-md-3 float-left">
                    <div class="video-image-mask">
                        <img class="card-img-top" src="{{ url('/videos/miniature/' . $video->image) }}" />
                    </div>
                </div>
            @endif
            <div class="data">
                <h4 class="video-title"><a href="{{ route('videos.show', ['video_id' => $video->id]) }}">{{ $video->title }}</a></h4>
                <p><a href="{{ route('users.show', ['user_id' => $video->user->id]) }}">{{ $video->user->name . ' ' . $video->user->surname }}</a>| {{ \FormatTime::LongTimeFilter($video->created_at)}}</p>
            </div>
            <!-- Action buttons -->
            <a class="btn btn-success btn-sm" href="{{ route('videos.show', ['video_id' => $video->id]) }}">See</a>
            @if(Auth::check() && Auth::user()->id == $video->user->id)
                <a class="btn btn-warning btn-sm" href="{{ url('/videos/' . $video->id . '/edit') }}">Edit</a>
                <a href="#videoModal{{$video->id}}" role="button" class="btn btn-danger btn-sm" data-toggle="modal">Delete</a>
                
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
                                <form action="{{ url('/videos/' . $video->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
    @endforeach
    @else
        <div class="alert alert-warning">There are no videos.</div>
    @endif
    <div class="clearfix"></div>
    {{ $videos->links() }}
</div>