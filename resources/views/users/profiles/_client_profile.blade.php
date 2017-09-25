@if($users->is_client)
    <section class="content">
        <div class="container">
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        User Profile
                    </h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <!-- form start -->
                    {!! Form::model($users, ['route' => ['update_profile', Auth::id()], 'method' => 'post', 'class' => 'form-horizontal', 'files' => true]) !!}
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Profile Image -->
                            <div class="box box-primary">
                                <div class="box-body box-profile">
                                    <img class="profile-user-img img-responsive img-circle" src="{{ $users->full_path_image }}" alt="User profile picture">
                                    @if(!empty($isOwner))
                                    <p class="btn-change-avatar">
                                        <span class="btn btn-default btn-file">
                                            <i class="fa fa-pencil"></i><input type="file" name="avatar">
                                        </span>
                                    </p>
                                    @endif

                                    <h3 class="profile-username text-center clear">{{ $users->client_name }}</h3>
                                    @if ($errors->has('avatar'))
                                        <span class="help-block text-red">
                                            <strong>{{ $errors->first('avatar') }}</strong>
                                        </span>
                                    @endif
                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <b>Liked</b> <a href="#" class="pull-right">{{ $users->number_liked_of }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Vote</b> <a class="pull-right">{{ $users->number_reviewed_of }}</a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->

                            <!-- About Me Box -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">@lang('global.about_me')</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <strong><i class="fa fa-map-marker margin-r-5"></i>@lang('global.location')</strong>
                                    <p class="text-muted">{{ $users->address }}</p>
                                    <hr>
                                    <strong><i class="fa fa-file-text-o margin-r-5"></i>@lang('global.description')</strong>
                                    <p>{{ !empty($users->client) ? $users->client->description : '' }}</p>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-9">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="{{ empty($isOwner) ? 'active' : '' }}"><a href="#activity" data-toggle="tab">@lang('global.activity')</a></li>
                                    @if(!empty($isOwner))
                                        <li class="active"><a href="#settings" data-toggle="tab">@lang('global.setting')</a></li>
                                    @endif
                                </ul>
                                <div class="tab-content">
                                    <div class="{{ empty($isOwner) ? 'active' : '' }} tab-pane" id="activity">
                                        @foreach($userReviewedOf as $reviews)
                                            <div class="info-box">
                                            @foreach($reviews as $index => $review)
                                                @if($index === 0)
                                                    <div class="info-box-icon bg-none">
                                                        <img src="{{ !empty($fullPathImage[$review->id]) ? $fullPathImage[$review->id] : asset('img/default_logo.png') }}" width="90px">
                                                    </div>
                                                @endif
                                                    <div class="info-box-content">
                                                        <div class="user-vote-content content-wrapper-text">
                                                            @if($index === 0)
                                                                <a href="{!! route('profile', $review->target_id) !!}" class="title text-uppercase">{{ !empty($userFullNames[$review->target_id]) ? $userFullNames[$review->target_id] : null }}</a><br><br>
                                                            @endif
                                                            <span class="pull-right">
                                                            <input name="rating" class="rating rating-loading" data-size = "xs" data-show-clear="false" data-readonly="true" data-show-caption="true"
                                                                   value="{{ $review->value }}">
                                                            </span>
                                                            <p class="title">{{ $review->title }}</p>
                                                            <div class="content-container short-text">
                                                            {!! $review->content !!}
                                                            </div>
                                                            <div class="show-more">
                                                                <a href="#">@lang('global.show_more')</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <hr>
                                                        <!-- /.info-box-content -->
                                            @endforeach
                                            </div>
                                        @endforeach
                                    </div>

                                    @if(!empty($isOwner))
                                        <div class="active tab-pane" id="settings">
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
                                                                <label for="first_name">{{ Lang::get('global.first_name') }}</label>
                                                                {!! Form::text('first_name', !empty($users->client) ? $users->client->first_name : null, ['placeholder' => trans('global.input_first_name'), 'class' => 'form-control']) !!}
                                                                @if ($errors->has('first_name'))
                                                                    <span class="help-block text-red">
                                                                        <strong>{{ $errors->first('first_name') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="last_name">{{ Lang::get('global.last_name') }}</label>
                                                                {!! Form::text('last_name', !empty($users->client) ? $users->client->last_name : null, ['placeholder' => trans('global.input_last_name'), 'class' => 'form-control']) !!}
                                                                @if ($errors->has('last_name'))
                                                                    <span class="help-block text-red">
                                                                        <strong>{{ $errors->first('last_name') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="birthday">{{ Lang::get('global.birthday') }}</label>
                                                                {!! Form::date_picker('birthday', !empty($users->client) ? $users->client->birthday : null, ['placeholder' => trans('global.input_birthday'), 'class' => 'form-control datepicker']) !!}
                                                                @if ($errors->has('birthday'))
                                                                    <span class="help-block text-red">
                                                                        <strong>{{ $errors->first('birthday') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="sex">{{ Lang::get('global.sex') }}</label> <br>
                                                                <label>
                                                                    @lang('global.male')
                                                                    <input type="radio" name="sex" class="minimal" {{ (isset($users->client) && $users->client->sex == 1) ?  'checked' : ''}} value="1">
                                                                </label>
                                                                <label>
                                                                    @lang('global.female')
                                                                    <input type="radio" name="sex" class="minimal" {{ (isset($users->client) && $users->client->sex == 0) ?  'checked' : ''}} value="0">
                                                                </label>
                                                                @if ($errors->has('sex'))
                                                                    <span class="help-block text-red">
                                                                        <strong>{{ $errors->first('sex') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="phone">{{ Lang::get('global.phone') }}</label>
                                                                {!! Form::text('phone', !empty($users->client) ? $users->client->phone : null, ['placeholder' => trans('global.input_phone'), 'class' => 'form-control']) !!}
                                                                @if ($errors->has('phone'))
                                                                    <span class="help-block text-red">
                                                                        <strong>{{ $errors->first('phone') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="country">{{ Lang::get('global.country') }}</label>
                                                                {!! Form::select('country', ['vn' => trans('global.vn'), 'ja' => trans('global.ja')], !empty($users->client) ? $users->client->country: null, ['id' => 'country', 'class' => 'form-control select select2']) !!}
                                                                @if ($errors->has('country'))
                                                                    <span class="help-block text-red">
                                                                        <strong>{{ $errors->first('country') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="suggest_address">{{ Lang::get('global.city') }}</label>

                                                                {!! Form::select('city', !empty($users->client) && !empty($cities[$users->client->country])? $cities[$users->client->country] : $cities['vn'], !empty($users->client) ? $users->client->city : null, ['id' => 'city', 'class' => 'form-control select select2']) !!}
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
                                                                        {!! Form::text('street_number', !empty($users->client) ? $users->client->street_number : null, ['placeholder' => trans('global.input_street_number'), 'class' => 'form-control']) !!}
                                                                        @if ($errors->has('street_number'))
                                                                            <span class="help-block text-red">
                                                                                <strong>{{ $errors->first('street_number') }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        {!! Form::text('street_name', !empty($users->client) ? $users->client->street_name : null, ['id' => 'route', 'class' => 'form-control']) !!}
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
                                                                {!! Form::text('postal_code', !empty($users->client) ? $users->client->postal_code : null, ['id' => 'postal_code', 'class' => 'form-control']) !!}
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
                                                                <label>{{ Lang::get('global.client_description') }}</label>
                                                                {!! Form::textarea('description', !empty($users->client) ? $users->client->description : null, ['placeholder' => trans('global.input_customer_description'), 'class' => 'form-control', 'row' => 3]) !!}
                                                                @if ($errors->has('description'))
                                                                    <span class="help-block text-red">
                                                                        <strong>{{ $errors->first('description') }}</strong>
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
                                        </div>
                                        <!-- /.tab-pane -->
                                    @endif
                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <!-- /.nav-tabs-custom -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    {!! Form::close() !!}

                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        </div>
    </section>
@endif