@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="container">
        @if($isOwner)
            @include('news._list_news_owner')
        @else
            @include('news._list_news_guest')
        @endif
        </div>
    </div>
@stop
@section('js-bottom')
    <script src="{{ asset('js/lib/grid-masonry/masonry.pkgd.js') }}"></script>
    <script src="{{ asset('js/grid.js') }}"></script>
@stop