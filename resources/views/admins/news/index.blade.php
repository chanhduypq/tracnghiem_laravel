@extends('admins.layouts.admin')
@section('js-bottom')
    <script src="{{ asset("/js/admins/tool_action.js") }}"></script>
@stop
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! trans('global.news') !!}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="box-header">
                        {!! Form::open(array('route' => 'admin.news.index', 'method' => 'get', 'class' => 'form-horizontal', 'files' => true)) !!}
                        <ul class="row">
                            <li class="col-md-2">
                                <a href="{{ route('admin.news.create') }}" class="btn btn-primary">@lang('button.create')</a>
                                <a href="#" class="btn btn-danger btn-delete-multiple" id="delete_news" name-target="news_id[]" url-current="{!! route('admin.news.index') !!}" url-target="/admin/news/delete">
                                    @lang('button.delete')
                                </a>
                            </li>
                            <li class="col-md-8">
                                <div class="form-group pull-left col-md-5">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        {!! Form::text('created_at', Request::get('created_at'), ['class' => 'form-control pull-right date-range-picker', 'placeholder' => 'Created date', 'data-default' => '']) !!}
                                    </div>
                                </div>
                                <div class="form-group pull-left col-md-7">
                                    {!!
                                        Form::select('pages[]', $pages, Request::get('pages'),
                                        ['multiple' => 'multiple', 'class' => 'form-control select2', 'data-placeholder' => 'Select page'])
                                    !!}
                                </div>
                            </li>
                            <li class="col-md-2">
                                <input type="submit" class="btn btn-primary" value="@lang('button.search')">
                            </li>
                        </ul>
                        {{ Form::close() }}
                    </div>
                    <table class="table table-bordered data_table_base">
                        <tbody>
                            <tr>
                                <th style="width: 60px">#</th>
                                <th>@lang('global.category')</th>
                                <th>@lang('global.created_date')</th>
                                <th>@lang('global.created_by')</th>
                                <th>@lang('global.title')</th>
                                <th>@lang('global.description')</th>
                                <th>@lang('global.overview')</th>
                                <th>@lang('global.status')</th>
                                <th style="width: 80px">{!! trans('global.action') !!}</th>
                            </tr>
                            @foreach($news as $row)
                                <tr>
                                    <td><label for="check_1"><input type="checkbox" class="minimal" name="news_id[]" data-id="{!! $row->news_id !!}">{{ $row->news_id }}</label></td>
                                    <td>{{ $row->txt_target_page }}</td>
                                    <td>{{ $row->created_at }}</td>
                                    <td>{{--{{ !empty($usersFullName[$row->user_id]) ? $usersFullName[$row->user_id] : $row->user->name }}--}}</td>
                                    <td><?php if(strlen($row->title)>20) echo substr($row->title, 0, 20);?></td>
                                    <td>{{ $row->description }}</td>
                                    <td>@lang('message.number_like_comment', ['l' => count($row->liked), 'c' => count($row->comment)])</td>
                                    <td class="{{ $row->draft == 1 ? 'label-warning' : ($row->active == 1 ? 'label-success' : 'label-danger') }}"><label class="label">{{ $row->draft == 1 ? trans('global.draft') : ($row->active == 1 ? trans('global.active') : trans('global.inactive')) }}</label></td>
                                    <td>
                                        <a href="{{ route('admin.news.edit', $row->news_id)}}"> <i class="fa fa-pencil-square-o"></i> </a>
                                        <a href="#" data-message="{!! trans('global.confirm-to-delete') !!}"
                                           class="delete-item ico-delete" data-id="{!! $row->news_id !!}" url-target="/admin/news/delete" url-current="{!! route('admin.news.index') !!}"> <i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="box-footer clearfix">
                    {!! $news->links() !!}
                </div>
            </div>
        </div>
    </div>
@stop