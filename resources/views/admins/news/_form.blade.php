<div class="box-body">
    <div class="form-group">
        <label for="user_type" class="col-sm-2 control-label">{!! trans('global.page') !!}</label>
        {{ Form::hidden('user_id', Auth::id()) }}
        <div class="col-sm-10">
            {{ Form::select('target_page[]', $targetPage, !empty($news->target_page) ? json_decode($news->target_page) : [], ['class' => 'form-control select2', 'multiple' => 'multiple']) }}
            @if ($errors->has('target_page'))
                <span class="help-block text-red">
                    <strong>{{ $errors->first('target_page') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">{!! trans('global.title') !!}</label>
        <div class="col-sm-10">
            {!! Form::text('title', null, ['class' => 'form-control', 'id' => 'title']) !!}
            @if ($errors->has('title'))
                <span class="help-block text-red">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">{!! trans('global.description') !!}</label>
        <div class="col-sm-10">
            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => trans('global.description')]) !!}
            @if ($errors->has('description'))
                <span class="help-block text-red">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="content" class="col-sm-2 control-label">{!! trans('global.content') !!}</label>
        <div class="col-sm-10">
            <span id="CKEditor-upload" data-url="{!! route('uploadImageCkEditorNews').'?_token='.csrf_token() !!}"></span>
            {!! Form::textarea('content', null, ['class' => 'form-control', 'rows' => 10, 'placeholder' => trans('global.content')]) !!}
            @if ($errors->has('content'))
                <span class="help-block text-red">
                    <strong>{{ $errors->first('content') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="content" class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <div class="col-md-6">
                <label>@lang('global.link_catalog_customer')</label>
                {{ Form::text('link_catalog', null, ['class' => 'form-control']) }}
                @if ($errors->has('link_catalog'))
                    <span class="help-block text-red">
                    <strong>{{ $errors->first('link_catalog') }}</strong>
                </span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="email_customer">@lang('global.email_customer')</label>
                {{ Form::text('email_customer', null, ['class' => 'form-control', 'id' => 'email_customer']) }}
                @if ($errors->has('email_customer'))
                    <span class="help-block text-red">
                    <strong>{{ $errors->first('email_customer') }}</strong>
                </span>
                @endif
            </div>
        </div>
    </div>


    <div class="form-group">
        <div class="row">
            <label for="content" class="col-sm-2 col-md-2 control-label">{!! trans('global.media') !!}</label>
            <div class="col-md-5">
                <label class="control-label pull-left">Image</label>
                @if(!empty($imagePath))
                    <p>
                        <img src="{{ $imagePath }}" width="200px">
                    </p>
                @endif
                <span class="btn btn-primary btn-file pull-left">
                                @lang('global.choose_file')
                    <input id="image" name="image" type="file" multiple class="file-upload">
                </span>
                @if ($errors->has('image'))
                    <span class="help-block text-red">
                        <strong>{{ $errors->first('image') }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-md-5">
                @include('youtube.btn_upload_video', ['targetType' => 'news', 'targetId' => !empty($news) ? $news->news_id : null, 'videoId' => !empty($news) ? $news->video : null])
            </div>
        </div>

    </div>

    <div class="form-group">
        <label for="draft" class="col-sm-2 control-label">{!! trans('global.draft') !!}</label>
        <div class="col-sm-10">
            {{ Form::checkbox('draft', null, !empty($news->draft) ? true : false, ['class' => 'minimal']) }}
            @if ($errors->has('draft'))
                <span class="help-block text-red">
                    <strong>{{ $errors->first('draft') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="active" class="col-sm-2 control-label">{!! trans('global.active') !!}</label>
        <div class="col-sm-10">
            {{ Form::checkbox('active', null, !empty($news->active) ? true : false, ['class' => 'minimal']) }}
            @if ($errors->has('active'))
                <span class="help-block text-red">
                    <strong>{{ $errors->first('active') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
<div class="box-footer text-center">
    <button type="submit" class="btn btn-primary">{!! trans('button.submit') !!}</button>
    <a class="btn btn-default" href="{!! route('admin.news.index') !!}">@lang('button.cancel')</a>
</div>