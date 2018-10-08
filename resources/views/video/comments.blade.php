<hr />
<h4>Comments</h4>
<hr />

@if (session('message'))
    <div class="alert alert-success" role="alert">
        {{ session('message') }}
    </div>
@endif

@if(Auth::check())
	<form class="col-md-4" method="POST" action="{{ url('/comment') }}">
		@csrf
		<input type="hidden" name="video_id" value="{{ $video->id }}" required />
		<p>
			<textarea class="form-control" name="body" required>
				
			</textarea>
		</p>
		<input type="submit" name="" value="Add comment" class="btn btn-success" />
	</form>
	<div class="clearfix">
	</div>
	<hr />

@endif

@if(isset($video->comments))
	<div id="comments-list">
		@foreach($video->comments as $comment)
		<div class="comment-item col-md-12 mb-2">
			<div class="card video-data">
                <div class="card-header">
                    <div class="card-title">
                        <strong>{{ $comment->user->name . ' ' . $comment->user->surname }}</strong> {{ \FormatTime::LongTimeFilter($comment->created_at)}}
                    </div>
                </div>
                <div class="card-body">
                    {{ $comment->body }}

                  	@if(Auth::check() && (Auth::user()->id == $comment->user_id || Auth::user()->id == $video->user->id))
		            	<div class="float-right">
			            	<a href="#commentModal{{$comment->id}}" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
			            	
			            	<div id="commentModal{{$comment->id}}" class="modal fade">
							    <div class="modal-dialog">
							        <div class="modal-content">
							            <div class="modal-header">
							                <h4 class="modal-title">Delete comment</h4>
							                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							                	<span aria-hidden="true">&times;</span>
							                </button>
							            </div>
							            <div class="modal-body">
							                <p>Do you want to delete this comment?</p>
							                <p><small>{{ $comment->body }}</small></p>
							            </div>
							            <div class="modal-footer">
							                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							                <a href="{{ url('/delete-comment/'. $comment->id) }}" type="button" class="btn btn-danger">Delete</a>
							            </div>
							        </div>
							    </div>
							</div>
						</div>
		            @endif
                </div>
            </div>
		</div>
		@endforeach
	</div>
@endif