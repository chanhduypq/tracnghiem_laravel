@extends('layouts.app')
@section('js-bottom')
    <script src="{{ asset('js/slider.js') }}"></script>
    <script src="{{ asset('js/lib/grid-masonry/masonry.pkgd.js') }}"></script>
    <script src="{{ asset('js/grid.js') }}"></script>

@stop
@section('content')
    <div class="container container-home">
        @include('searchs._user_result')
        {{--Template--}}
    </div>
@endsection