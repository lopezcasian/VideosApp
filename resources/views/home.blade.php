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

            @include('video.videos_list') 

        </div>
    </div>
</div>
@endsection
