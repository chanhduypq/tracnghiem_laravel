@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="container">
            {!! Form::open(array('route' => ['user.review.create', $user->id], 'method' => 'post', 'class' => 'form-horizontal', 'files' => true)) !!}

            <div class="row">
                <div class="avatar-customer col-md-4 text-right">
                    <img width="150px" src="{{ !empty($fullPathImage[$user->id]) ? $fullPathImage[$user->id] : asset('img/default_logo.png') }}">
                </div>
                <div class="customer-info col-md-8">
                    <h3 class="title">{{ !empty($userFullNames[$user->id]) ? $userFullNames[$user->id] : null }}</h3>
                    <strong><i class="fa fa-star"></i></strong>@lang('message.total_rating_for', ['t' => count($user->reviewed_for)])
                    <strong><i class="fa fa-envelope margin-r-5"></i></strong><a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                    <strong><i class="fa fa-mobile-phone margin-r-5"></i></strong>{{ !empty($user->customer) ? $user->customer->phone : null }}<br>
                    <strong><i class="fa fa-street-view margin-r-5"></i></strong>{{ !empty($user->customer) ? $user->customer->address : null }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="avatar text-right">
                        {{ Form::hidden('target_id', $user->id) }}
                        {{ Form::hidden('target_type', 'customer_review') }}
                    </div>
                </div>
                <div class="col-md-8">
                    <strong><i class="fa fa-check-circle"></i>@lang('global.your_overall_rating')</strong><br>
                    {{ Form::text('value', !empty($dataPost) ? $dataPost['rating'] : null, [ 'id' => 'rating', 'data-size' => "xs", 'class' => "rating rating-loading", 'data-show-clear' => "false", 'data-min' => "0", 'data-max' => "5", 'data-step' => "1"]) }}
                    @if ($errors->has('value'))
                        <span class="help-block text-red">
                            <strong>{{ $errors->first('value') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="title">@lang('global.vote_title')</label>
                    {{ Form::text('title', null, ['class' => 'form-control']) }}
                    @if ($errors->has('title'))
                        <span class="help-block text-red">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="title">@lang('global.content')</label>
                    {{ Form::textarea('content', null, ['class' => 'form-control', 'rows' => 5]) }}
                    @if ($errors->has('content'))
                        <span class="help-block text-red">
                            <strong>{{ $errors->first('content') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="confirm">
                        {{ Form::checkbox('review_confirm', null, ['class' => 'minimal']) }}&nbsp;@lang('global.vote_confirm')
                    </label>
                    @if ($errors->has('review_confirm'))
                        <span class="help-block text-red">
                            <strong>{{ $errors->first('review_confirm') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">@lang('button.submit')</button>
                    <a href="/" class="btn btn-default">@lang('button.cancel')</a>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop
