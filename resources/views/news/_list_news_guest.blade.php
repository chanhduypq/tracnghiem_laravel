<div class="row grid">
@foreach($news as $row)
    <div class="col-sm-12 col-md-6 col-lg-4 grid-item">
        <div class="box box-widget box-news">
            <div class="box-header with-border">
                <p class="title text-bold"><a href="{{ route('user.news.detail', ['id' => $row->user_id, 'news_id' => $row->news_id]) }}">{!! $row->title !!}</a></p>
                <div class="user-block">
                    <img class="img-circle" src="{{ !empty($fullImagePath[$row->user_id]) ? $fullImagePath[$review->user_id] : asset('img/default_logo.png') }}" alt="User Image">
                    <span class="username"><a href="{{ route('profile', $row->user_id) }}">{{ !empty($userFullNames[$row->user_id]) ? $userFullNames[$row->user_id] : null }}</a></span>
                    <span class="description"><i class="fa fa-clock-o"></i> {{ _ago($row->created_at) }}</span>
                </div>
                <!-- /.user-block -->
            </div>
            <!-- /.box-header -->
            <div class="box-body content-wrapper-text">
                <div class="news-content-inner short-text content-container">{!! $row->description !!}</div>
                <div class="show-more-redirect">
                    <a href="{{ route('user.news.detail', ['id' => $row->user_id, 'news_id' => $row->news_id]) }}">Show more</a>
                </div>
                {!! Form::btn_like($row->like, $row->news_id, LIKE_NEWS_TYPE) !!}
                <span class="pull-right text-muted">@lang('message.number_like', ['n' => count($row->liked)]) &nbsp; @lang('message.number_comment', ['n' => count($row->comment)])</span>
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /.box -->
    </div>
@endforeach
</div>
@include('mustache_tem.comment')