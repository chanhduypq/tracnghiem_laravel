@extends('layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="container">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                News
                <small>Create</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            {!! Form::open(['method' => 'POST', 'route' => 'news.store', 'files' => true]) !!}
                @include('news._form')
            {!! Form::close() !!}
            <!-- ./row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@stop
@section('css')
@stop
@section('js-bottom')
    <script src="{{ asset('AdLTE/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/ckeditor.config.js') }}"></script>

    <script src="//apis.google.com/js/client:plusone.js"></script>
    <script src="{{ asset('js/youtube/cors_upload.js') }}"></script>
    <script src="{{ asset('js/youtube/upload_video.js') }}"></script>

    <script src="{{ asset('AdLTE/plugins/iCheck/icheck.min.js') }}"></script>

    <script>
    $(document).ready(function () {
        CKEDITOR.replace('content');

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
    });
</script>
@stop