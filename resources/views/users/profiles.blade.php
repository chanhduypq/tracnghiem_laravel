@extends('layouts.app')
@section('css')
@stop
@section('js-bottom')
{{--
    <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
--}}
    <script src="{{ asset('AdLTE/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/ckeditor.config.js') }}"></script>
    <script src="{{ asset('AdLTE/plugins/iCheck/icheck.min.js') }}"></script>

    <script src="//apis.google.com/js/client:plusone.js"></script>
    <script src="{{ asset('js/youtube/cors_upload.js') }}"></script>
    <script src="{{ asset('js/youtube/upload_video.js') }}"></script>

    <script>
        $(document).ready(function () {
            if ($('textarea[name=detail]').length) {
                CKEDITOR.replace('detail');
            }
        });
    </script>
@stop
@section('content')
    @include('users.profiles._customer_profile')
    @include('users.profiles._client_profile')
    @include('users.profiles._staff_profile')
@endsection