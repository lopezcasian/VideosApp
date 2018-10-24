@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="container">

        	<h2>{{ $user->name . $user->surname}} channel.</h2>

        	<div class="clearfix"></div>

            @include('video.videos_list') 

        </div>
    </div>
</div>
@endsection