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
                    <div class="box-header">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">@lang('button.create')</a>
                        <a href="#" class="btn btn-danger btn-delete-user btn-delete-multiple" id="delete_user">
                            @lang('button.delete')
                        </a>
                    </div>
                    <table class="table table-bordered data_table_base">
                        <tbody>
                        <tr>
                            <th style="width: 60px">ID</th>
                            <th>{!! trans('global.name') !!}</th>
                            <th>{!! trans('global.email') !!}</th>
                            <th>{!! trans('global.user_type') !!}</th>
                            <th>{!! trans('global.status') !!}</th>
                            <th style="width: 80px">{!! trans('global.action') !!}</th>
                        </tr>
                        @foreach($users as $key => $user)
                            <tr>
                                <td>
                                    <label for="check_{{ $user->id }}">
                                        <input type="checkbox" class="minimal" name="id_user[]" data-user-id="{!! $user->id !!}">&nbsp;{!! $user->id !!}
                                    </label>
                                </td>
                                <td> {!! $user->username !!}</td>
                                <td> <a href="{{ route('admin.users.edit', $user->id) }}">{!! $user->email !!}</a></td>
                                <td> {!!  trans("global.$user->user_type") !!}</td>
                                <td> {!!  ($user->active == 1) ? trans("global.active") : trans("global.inactive") !!}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id)}}"> <i class="fa fa-pencil-square-o"></i> </a>
                                    <a href="#" data-message="{!! trans('global.confirm-to-delete') !!}"
                                       class="ico-delete-user" data-user-id="{!! $user->id !!}"> <i class="fa fa-trash-o"></i> </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    {!! $users->links() !!}
                </div>
            </div>
        </div>
    </div>
@stop
@section('js-bottom')
    <script>
        var config = {
            routes : [
                {
                    'delete_users' : "{!! route('admin.users.delete', 1) !!}",
                }
            ]
        }
    </script>
    <script src="{{ asset("/js/admins/users.js") }}"></script>
@stop