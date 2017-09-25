@if($users->is_customer)
    <section class="content">
        <div class="container">
            <!-- Content Wrapper. Contains page content -->
            <div class="col-md-12">
                <h3 class="title">{{ $users->full_name }}</h3>
                <strong><i class="fa fa-star"></i></strong>@lang('message.total_rating_for', ['t' => count($users->reviewed_for)])<br>
                <div class="col-md-6">
                    <strong><i class="fa fa-map-marker margin-r-5"></i></strong><a href="{{ !empty($users->customer) ? $users->customer->website : null }}">{{  !empty($users->customer) ? $users->customer->website : null }}</a>
                </div>
                <div class="col-md-6">
                    <strong><i class="fa fa-envelope margin-r-5"></i></strong><a href="">{{ $users->email }}</a><br>
                </div>
                <div class="col-md-6">
                    <strong><i class="fa fa-street-view margin-r-5"></i></strong>{{ $users->address }}
                </div>
                <div class="col-md-6">
                    <strong><i class="fa fa-mobile-phone margin-r-5"></i></strong>{{ $users->phone }}
                </div>
            </div>

            <!--Slide about school here-->

            <div class="col-md-12"><hr>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="{{ empty($isOwner) ? 'active' : '' }}"><a href="#summary" data-toggle="tab">@lang('global.summary')</a></li>
                        <li class=""><a href="#activity" data-toggle="tab">@lang('global.activity')</a></li>
                        @if(!empty($isOwner))
                            <li class="active"><a href="#settings" data-toggle="tab">@lang('global.setting')</a></li>
                        @else
                            <li><a href="#base_info" data-toggle="tab">@lang('global.base_information')</a> </li>
                        @endif
                        <li class="pull-right">
                            <a href="#" class="text-muted btn-like text-small" target-type="{{ LIKE_CUSTOMER_LIKE_TYPE }}" target-id="{{ $users->id }}" value = {{ !empty($userLiked[Auth::id()]) ? $userLiked[Auth::id()] : 0 }}>
                                <i class="fa fa-heart"></i>
                                @lang('message.number_like', ['n' => count($users->liked_for)])
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="{{ empty($isOwner) ? 'active' : '' }} tab-pane" id="summary">
                            <p class="text-light-blue">@lang('message.total_vote_and_like_from_system', ['v' => count($users->reviewed_for), 'l' => count($users->liked_for)])</p>
                            <strong>@lang('global.number_reviewed_from_alojapan', ['n' => count($users->reviewed_for)])</strong>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <ul class="rating-container">
                                        @foreach($users->rating_of_customer as $rating)
                                            <li>
                                                <span>@lang('global.rating_start_'.$rating->value)</span>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="{{ $rating->percentage }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $rating->percentage }}%">
                                                        <span class="text-muted">{{ $rating->vote_number }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-8">
                                    {!! Form::user_review($users) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    {{--{!! Form::user_reviewed($users->reviewed_for_user) !!}--}}
                                    @include('macros.user_reviewed', [
                                        'user_reviews' => $users->user_review_for_paginate,
                                        'clientNames' => $userFullNames,
                                        'targetId' => $users->id
                                    ])
                                </div>
                            </div>
                                @include('macros.user_comment', [
                                    'comments' => $usersCommented,
                                    'targetId' => $users->id,
                                    'targetType' => COMMENT_CUSTOMER_COMMENT_TYPE
                                ])
                            {{--{!! Form::user_comment($usersCommented, $users->id, 'customer_comment') !!}--}}
                        </div>
                        <div class="tab-pane" id="activity">
                            @include('homes.activities._news', ['news' => $users->news])
                        </div>

                        @if(!empty($isOwner))
                            <div class="active tab-pane" id="settings">
                                <!-- form start -->
                                {!! Form::model($users, ['route' => ['update_profile', Auth::id()], 'method' => 'post', 'class' => 'form-horizontal', 'files' => true]) !!}
                                    <input type="hidden" name="user_id" value="{{ $users->id }}">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-6">
                                            <div class="box box-primary">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">{{ Lang::get('global.base_information') }}</h3>
                                                </div>
                                                <!-- /.box-header -->
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="customer_name">{{ Lang::get('global.customer_name') }}</label>
                                                        {!! Form::text('customer_name', !empty($users->customer) ? $users->customer->customer_name : null, ['placeholder' => trans('global.input_customer_name'), 'class' => 'form-control']) !!}
                                                        @if ($errors->has('customer_name'))
                                                            <span class="help-block text-red">
                                                                <strong>{{ $errors->first('customer_name') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="foundation_date">{{ Lang::get('global.foundation_date') }}</label>
                                                        {!! Form::text('foundation_date', !empty($users->customer) ? $users->customer->foundation_date : null, ['placeholder' => trans('global.input_foundation_date'), 'class' => 'form-control datepicker']) !!}
                                                        @if ($errors->has('foundation_date'))
                                                            <span class="help-block text-red">
                                                                <strong>{{ $errors->first('foundation_date') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="fax">{{ Lang::get('global.fax') }}</label>
                                                        {!! Form::text('fax', !empty($users->customer) ? $users->customer->fax : null, ['placeholder' => trans('global.input_fax'), 'class' => 'form-control']) !!}
                                                        @if ($errors->has('fax'))
                                                            <span class="help-block text-red">
                                                                <strong>{{ $errors->first('fax') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="phone">{{ Lang::get('global.phone') }}</label>
                                                        {!! Form::text('phone', !empty($users->customer) ? $users->customer->phone : null, ['placeholder' => trans('global.input_phone'), 'class' => 'form-control']) !!}
                                                        @if ($errors->has('phone'))
                                                            <span class="help-block text-red">
                                                                <strong>{{ $errors->first('phone') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="website">{{ Lang::get('global.website') }}</label>
                                                        {!! Form::text('website', !empty($users->customer) ? $users->customer->website : null, ['placeholder' => trans('global.input_website'), 'class' => 'form-control']) !!}
                                                        @if ($errors->has('website'))
                                                            <span class="help-block text-red">
                                                                <strong>{{ $errors->first('website') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="country">{{ Lang::get('global.country') }}</label>
                                                        {!! Form::select('country', ['vn' => trans('global.vn'), 'ja' => trans('global.ja')], !empty($users->customer) ? $users->customer->country: null, ['id' => 'country', 'class' => 'form-control select select2', 'placeholder' => trans('global.select_country')]) !!}
                                                        @if ($errors->has('country'))
                                                            <span class="help-block text-red">
                                                                <strong>{{ $errors->first('country') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="suggest_address">{{ Lang::get('global.city') }}</label>

                                                        {!! Form::select('city', !empty($users->customer) && !empty($cities[$users->customer->country])? $cities[$users->customer->country] : [], !empty($users->customer) ? $users->customer->city : null, ['id' => 'city', 'class' => 'form-control select select2']) !!}
                                                        @if ($errors->has('city'))
                                                            <span class="help-block text-red">
                                                                <strong>{{ $errors->first('city') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="suggest_address">{{ Lang::get('global.street_address') }}</label>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                {!! Form::text('street_number', !empty($users->customer) ? $users->customer->street_number : null, ['id' => 'street_number', 'placeholder' => trans('global.input_street_number'), 'class' => 'form-control']) !!}
                                                                @if ($errors->has('street_number'))
                                                                    <span class="help-block text-red">
                                                                        <strong>{{ $errors->first('street_number') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-8">
                                                                {!! Form::text('street_name', !empty($users->customer) ? $users->customer->street_name : null, ['id' => 'route', 'class' => 'form-control']) !!}
                                                                @if ($errors->has('street_name'))
                                                                    <span class="help-block text-red">
                                                                        <strong>{{ $errors->first('street_name') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="postal_code">{{ Lang::get('global.state') }}</label>
                                                        {!! Form::text('postal_code', !empty($users->customer) ? $users->customer->postal_code : null, ['id' => 'postal_code', 'class' => 'form-control']) !!}
                                                        @if ($errors->has('postal_code'))
                                                            <span class="help-block text-red">
                                                                <strong>{{ $errors->first('postal_code') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-6">
                                            <div class="box box-primary">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">{{ Lang::get('global.base_information') }}</h3>
                                                </div>
                                                <!-- /.box-header -->
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label>{{ Lang::get('global.customer_description') }}</label>
                                                        {!! Form::textarea('description', !empty($users->customer) ? $users->customer->description : null, ['placeholder' => trans('global.input_customer_description'), 'class' => 'form-control', 'row' => 3]) !!}
                                                        @if ($errors->has('description'))
                                                            <span class="help-block text-red">
                                                                <strong>{{ $errors->first('description') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ Lang::get('global.customer_detail') }}</label>
                                                        <span id="CKEditor-upload" data-url="{!! route('uploadImageCkEditor').'?_token='.csrf_token() !!}"></span>
                                                        {!! Form::textarea('detail', !empty($users->customer) ? $users->customer->detail : null, ['placeholder' => trans('global.input_customer_detail'), 'id' => 'customer_detail', 'class' => 'form-control', 'row' => 6]) !!}
                                                        @if ($errors->has('detail'))
                                                            <span class="help-block text-red">
                                                                <strong>{{ $errors->first('detail') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        @include('youtube.btn_upload_video', ['targetType' => 'customer_profile', 'targetId' => $users->id, 'videoId' => $videoPath])
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="foundation_date">{{ Lang::get('global.upload_image') }}</label>
                                                        @if($imagePath)
                                                        <p>
                                                            <img src="{{ $imagePath }}" width="200px">
                                                        </p>
                                                        @endif
                                                        <input type="file" name="image" class="form-control" placeholder="{{ Lang::get('global.input_detail') }}"></input>
                                                        @if ($errors->has('image'))
                                                            <span class="help-block text-red">
                                                                <strong>{{ $errors->first('image') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">{{ Lang::get('global.save') }}</button>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                            <!-- /.tab-pane -->
                        @else
                            <div class="tab-pane" id="base_info">
                                {!! !empty($users->customer) ? $users->customer->detail : null !!}
                            </div>
                        @endif
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.content-wrapper -->
        </div>
    </section>
    @include('mustache_tem.comment')

@endif