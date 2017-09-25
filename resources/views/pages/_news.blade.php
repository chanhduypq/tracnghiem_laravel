<div class="row grid grid-custom" number-column="3">
    @foreach($news as $row)
        <div class="col-sm-12 col-md-6 col-lg-4 grid-item">
            <!-- Box Comment -->
            <div class="box box-widget">
                <div class="box-header with-border">
                    <div class="user-block">
                        {{ _ago($row->created_at) }}
                        @if(!empty($row->link_catalog))
                            <a target="_blank" class="btn btn-custom pull-right btn-get-catalog" target-type="{{ GET_CATALOG }}" name-customer="{{ $row->title }}" target-id="{{ $row->news_id }}" data-url="{{ $row->link_catalog }}"
                               email-customer="{{ $row->email_customer }}" data-url="{{ $row->link_catalog }}" href="{{ $row->link_catalog }}">@lang('global.get_catalog')</a>
                        @endif
                    </div>
                    <!-- /.user-block -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <h3 class="title"><a href="{{ route('user.news.detail', ['id' => $row->user_id, 'news_id' => $row->news_id]) }}">{!! $row->title !!}</a></h3>
                    <p>
                        @if($row->image)
                            <img src="{{ asset($newsImagePath['small']['path'].$row->image) }}" width="100px">
                        @endif
                        {!! $row->description !!}
                    </p>

                    {!! Form::btn_like($row->like, $row->news_id, LIKE_NEWS_TYPE) !!}
                    <span class="pull-right text-muted">@lang('message.number_like', ['n' => count($row->liked)]) &nbsp; @lang('message.number_comment', ['n' => count($row->comment)])</span>
                </div>
                <!-- /.box-body -->
            {!! Form::user_comment($row->comment_paginate, $row->news_id, COMMENT_NEWS_TYPE) !!}
            <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
        <hr>
    @endforeach
</div>