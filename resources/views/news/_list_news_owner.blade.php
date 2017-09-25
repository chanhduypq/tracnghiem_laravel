<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">List of news <a href="{{ route('news.create') }}">@lang('button.create_fa')</a> </h3>
                        <div class="box-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>@lang('global.id')</th>
                                <th>@lang('global.created_date')</th>
                                <th>@lang('global.title')</th>
                                <th>@lang('global.short_content')</th>
                                <th>@lang('global.rating')</th>
                                <th>@lang('global.action')</th>
                            </tr>
                            @foreach($news as $row)
                                <tr>
                                    <td>{{ $row->news_id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d') }}</td>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->description }}</td>
                                    <td>@lang('message.number_like', ['n' => count($row->like)]) &nbsp; @lang('message.number_comment', ['n' => count($row->comment)])</td>
                                    <td>
                                        <a href="{{ route('news.show', $row->news_id) }}" class=""><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('news.edit', $row->news_id) }}"><i class="fa fa-edit "></i></a>
                                        <a href="{{ route('news.destroy', $row->news_id) }}" class="btn-delete-news"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
</div>