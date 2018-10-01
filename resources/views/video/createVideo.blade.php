@extends('layouts.app')

@section('content')
	<div class="container">
		<h2>Create new video</h2>
		<div class="row">
			<form action="{{ route('saveVideo') }}" method="POST" ectype="multipart/form-data" class="col-lg-7">
				{!! csrf_field() !!}

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
					<input type="text" name="title" class="form-control" id="title" value="{{old('title')}}"/>
				</div>
				<div class="form-group">
					<label for="description">Description</label>
					<textarea  name="description" class="form-control" id="description">{{old('description')}}</textarea> 
				</div>
				<div class="form-group">
					<label for="image">Miniature</label>
					<input type="file" name="image" class="form-control" id="image" />
				</div>
				<div class="form-group">
					<label for="video">Video file</label>
					<input type="file" name="video" class="form-control" id="video" />
				</div>

				<button type="submit" class="btn btn-primary">Upload video</button>
			</form>
		</div>
	</div>
@endsection