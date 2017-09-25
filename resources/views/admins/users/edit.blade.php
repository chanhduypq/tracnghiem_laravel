@extends('admins.layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('global.user')</h3>
                    <span class="text-small">@lang('global.edit')</span>
                </div>
            <!-- /.box-header -->
                <div class="box-body">
                    {!! Form::model($user, ['route' => ['admin.users.update', $user->id], 'method' => 'patch', 'class' => 'form-horizontal', 'files' => true]) !!}
                        {!! Form::hidden('user_id', $user->id) !!}
                        @include('admins.users._form')
                    {!! Form::close() !!}
                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>
@stop