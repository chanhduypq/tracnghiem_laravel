@if(!empty($imagePath))
    <p>
        <img src="{{ $imagePath }}" width="200px">
    </p>
@endif
<div class="btn-upload-container">
    <span class="btn btn-primary btn-file pull-left">
        @lang('global.choose_file')
        <input id="image" name="image" type="file" multiple class="file-loading file-upload" onchange="$('#lb-upload-image-info').html($(this).val())">

    </span>
    <span class="pull-left lb-upload-file-info" id="lb-upload-image-info"></span>
</div>
@if ($errors->has('image'))
    <span class="help-block text-red">
        <strong>{{ $errors->first('image') }}</strong>
    </span>
@endif