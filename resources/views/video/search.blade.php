@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="container">
        	<div class="col-md-4">
        		<h2>Search: {{ $search }}</h2>
        	</div>

        	<div class="col-md-8 float-right">
	        	<form class="col-md-4 float-right" action="{{ url('/search/' . $search) }}" method="GET">
	        		<label for="filter">Order by</label>
	        		<select name="filter" class="form-control">
	        			<option value="new">Newest</option>
	        			<option value="old">Oldest</option>
	        			<option value="atoz">A to Z</option>
	        		</select>
	        		<input type="submit" value="order" class="btn-filter btn btn-sm btn-primary" />
	        	</form>
        	</div>

        	<div class="clearfix"></div>

            @include('video.list') 

        </div>
    </div>
</div>
@endsection