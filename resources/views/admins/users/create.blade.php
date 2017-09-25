@extends('admins.layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! trans('global.users') !!}</h3>
                </div>
            <!-- /.box-header -->
                <div class="box-body">
                    {!! Form::open(array('route' => 'admin.users.store', 'method' => 'post', 'class' => 'form-horizontal', 'files' => true)) !!}
                    {{ Form::hidden('user_id', Auth::id()) }}
                    @include('admins.users._form')
                    {!! Form::close() !!}
                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>
@stop