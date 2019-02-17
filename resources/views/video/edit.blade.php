@extends('layouts.app')

@section('content')
	<div class="container">
		<h2>Edit {{ $video->title }}</h2>
		<div class="row">
			<form action="{{ url('/videos/' . $video->id) }}" method="POST" enctype="multipart/form-data" class="col-lg-7">
				@csrf
				@method('PUT')
				@if($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" name="title" class="form-control" id="title" value="{{$video->title}}"/>
				</div>
				<div class="form-group">
					<label for="description">Description</label>
					<textarea  name="description" class="form-control" id="description">{{$video->description}}</textarea> 
				</div>
				<div class="form-group">
					<label for="image">Miniature</label>
					@if(Storage::disk('images')->has($video->image))
                        <div class="video-image-thumb">
                            <div class="video-image-mask">
                                <img class="card-img-top" src="{{ url('/thumbnail/' . $video->image) }}" />
                            </div>
                        </div>
                    @endif
					<input type="file" name="image" class="form-control" id="image" />
				</div>
				<div class="form-group">
					<label for="video">Video file</label>
					<br/>
					<video controls id="video-player">
		                <source src="{{ route('fileVideo', ['filename' => $video->video_path]) }}" type="video/mp4" />
		                Your browser is not compatible with HTLM5.
		            </video>
					<input type="file" name="video" class="form-control" id="video" />
				</div>

				<button type="submit" class="btn btn-primary">Edit video</button>
			</form>
		</div>
	</div>
@endsection