@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- Box Comment -->
                    <div class="box box-widget">
                        <div class="box-header with-border">
                            <div class="user-block">
                                <img class="img-circle" src="{{ !empty($fullPathImage[$news->user_id]) ? $fullPathImage[$news->user_id] : asset('img/default_logo.png') }}" alt="User Image">
                                <span class="username"><a href="{{ route('profile', $news->user_id) }}">{{ !empty($userFullNames[$news->user_id]) ? $userFullNames[$news->user_id] : trans('message.guest') }}</a></span>
                                <span class="description">
                                    {{ $news->created_at }}
                                    @if($news->user_id == Auth::id())
                                        <a href="{{ route('news.edit', $news->news_id) }}">@lang('button.edit_fa')</a>
                                        <a href="{{ route('news.destroy', $news->news_id) }}">@lang('button.delete_fa')</a>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <h2>{{ $news->title }}</h2>
                            @if(!empty($news->image))
                                <img class="img-responsive pad" width="300px" src="{{ asset($imagePath.$news->image) }}" alt="">
                            @endif
                            @if(!empty($news->video))
                                <p>
                                    <embed width="400" height="315" src="https://www.youtube.com/embed/{{ $news->video }}"></embed>
                                </p>
                            @endif
                            <p>{!! $news->content !!}</p>
                            {!! Form::btn_like($news->liked, $news->news_id, LIKE_NEWS_TYPE) !!}
                            <span class="pull-right text-muted">@lang('message.number_like', ['n' => count($news->liked)]) - @lang('message.number_comment', ['n' => !empty($news->comment) ? count($news->comment) : 0])</span>
                        </div>
                        <!-- /.box-body -->

                        {{--{!! Form::user_comment($news->comment_paginate, $news->news_id, COMMENT_NEWS_TYPE) !!}--}}
                        @include('macros.user_comment', [
                                   'comments' => $usersCommented,
                                   'targetId' => $news->news_id,
                                   'targetType' => COMMENT_NEWS_TYPE
                               ])

                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
        </div>
    </section>
    @include('mustache_tem.comment')
@stop