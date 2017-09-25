@extends('admins.layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! trans('global.news') !!}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    {!! Form::model($news, ['route' => ['admin.news.update', $news->news_id], 'method' => 'patch', 'class' => 'form-horizontal', 'files' => true]) !!}
                    @include('admins.news._form')
                    {!! Form::close() !!}
                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop
@section('js-bottom')
    <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
    <script src="{{ asset('AdLTE/plugins/iCheck/icheck.min.js') }}"></script>

    <script src="//apis.google.com/js/client:plusone.js"></script>
    <script src="{{ asset('js/youtube/cors_upload.js') }}"></script>
    <script src="{{ asset('js/youtube/upload_video.js') }}"></script>

    <script>
        $(document).ready(function () {
            CKEDITOR.replace('content');
        });
    </script>
@stop