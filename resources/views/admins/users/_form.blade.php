<div class="box-body">
    <div class="form-group">
        <label for="user_type" class="col-sm-2 control-label">{!! trans('global.user_type') !!}</label>
        <div class="col-sm-10">
            {{ Form::select('user_type', $user_type , null, ['class' => 'form-control']) }}
            @if ($errors->has('user_type'))
                <span class="help-block text-red">
                    <strong>{{ $errors->first('user_type') }}</strong>
                </span>
            @endif
        </div>
    </div>

     <div class="form-group">
        <label for="email" class="col-sm-2 control-label">{!! trans('global.email') !!}</label>
        <div class="col-sm-10">
            {!! Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) !!}

            @if ($errors->has('email'))
                <span class="help-block text-red"">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="col-sm-2 control-label">{!! trans('global.password') !!}</label>
        <div class="col-sm-10">
            {!! Form::password('password', array('class' => 'form-control')) !!}

            @if ($errors->has('password'))
                <span class="help-block text-red">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="col-sm-2 control-label">{!! trans('global.repassword') !!}</label>
        <div class="col-sm-10">
            {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        <label for="active" class="col-sm-2 control-label">{!! trans('global.active_user') !!}</label>
        <div class="col-sm-10">
            {!! Form::checkbox('active', null, !empty($user->active) ? true : false, ['class' => 'minimal']) !!}
        </div>
    </div>
</div>

<div class="box-footer text-center">
    <button type="submit" class="btn btn-primary">{!! trans('button.submit') !!}</button>
    <a class="btn btn-default" href="{!! route('admin.users.index') !!}">@lang('button.cancel')</a>
</div>