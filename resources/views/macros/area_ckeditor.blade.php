<div class="form-group">
    <label for="content">@lang('global.content')</label>
    <span id="CKEditor-upload" data-url="{!! route('uploadImageCkEditorNews').'?_token='.csrf_token() !!}"></span>
    {{ Form::textarea('content', null, ['placeholder' => trans('global.enter_new_content'), 'rows' => 10, 'class' => 'form-control']) }}
    @if ($errors->has('content'))
        <span class="help-block text-red">
            <strong>{{ $errors->first('content') }}</strong>
        </span>
    @endif
</div>