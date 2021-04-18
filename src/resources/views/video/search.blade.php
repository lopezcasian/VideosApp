@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="container">
        	<div class="col-md-4">
        		<h2>Search: {{ $search }}</h2>
        	</div>

        	<div class="col-md-8 float-right">
	        	<form class="col-md-4 float-right" action="{{ url('/videos/search') }}" method="POST">
                    @csrf
                    <input type="hidden" name="search" value="{{ $search }}">
	        		<label for="order">Order by</label>
	        		<select name="order" class="form-control">
	        			<option value="new" {{ $order == "new" ? "selected":"" }}>Newest</option>
	        			<option value="old" {{ $order == "old" ? "selected":"" }}>Oldest</option>
	        			<option value="atoz" {{ $order == "atoz" ? "selected":"" }}>A to Z</option>
	        		</select>
	        		<input type="submit" class="btn-filter btn btn-sm btn-primary" />
	        	</form>
        	</div>

        	<div class="clearfix"></div>

            @include('video.list') 

        </div>
    </div>
</div>
@endsection