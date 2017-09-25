@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="container">
            @include('pages._users')
            @include('pages._news')
        </div>
    </div>
@stop
@section('js-bottom')
    <script src="{{ asset('js/lib/grid-masonry/masonry.pkgd.js') }}"></script>
    <script src="{{ asset('js/grid.js') }}"></script>
@stop