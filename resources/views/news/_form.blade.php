<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <div class="form-group">
                    <label for="title">@lang('global.title')</label>
                    {{ Form::text('title', null, ['placeholder' => trans('global.enter_new_title'), 'class' => 'form-control']) }}
                    @if ($errors->has('title'))
                        <span class="help-block text-red">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">@lang('global.description')</label>
                    {{ Form::textarea('description', null, ['placeholder' => trans('global.enter_new_description'), 'rows' => 3, 'class' => 'form-control']) }}
                    @if ($errors->has('description'))
                        <span class="help-block text-red">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>
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

                <div class="form-group">
                    <div class="col-md-2">
                        <label class="control-label">@lang('global.video')</label>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-12">
                                <span id="upload-video-info" target-type="news" target-id="{{ !empty($news) ? $news->news_id : '' }}"></span>
                                <div id="video-contain">
                                    @if(!empty($news) && !empty($news->video))
                                        <embed width="200" src="https://www.youtube.com/embed/{{ $news->video }}"></embed>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <span id="signinButton" class="pre-sign-in">
                                  <!-- IMPORTANT: Replace the value of the <code>data-clientid</code>
                                       attribute in the following tag with your project's client ID. -->
                                  <span
                                          class="g-signin"
                                          data-callback="signinCallback"
                                          data-clientid="48434194701-58u83b47l6epc3abrvfi2bd20b44j37l.apps.googleusercontent.com"
                                          data-cookiepolicy="single_host_origin"
                                          data-scope="https://www.googleapis.com/auth/youtube.upload https://www.googleapis.com/auth/youtube">
                                  </span>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-upload-container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span class="btn btn-primary btn-file pull-left">
                                                @lang('global.choose_file')
                                                {{--{{ Form::file('video', null, ['multiple'=> '', 'id' => 'video', 'class' => 'file-upload', 'onchange' => "$('#lb-upload-video-info').html($(this).val())'"]) }}--}}
                                                <input id="video" name="video_file" type="file" multiple class="file-upload" onchange="">
                                            </span>
                                            <span class="pull-left lb-upload-file-info" id="lb-upload-video-info"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="during-upload" style="display: none">
                                                <div class="progress" id="youtube-upload-progress">
                                                    <div class="progress-bar progress-bar-green" role="progressbar"><span class="text-muted progress-number" id="upload-number-progress"></span></div>

                                                </div>
                                            </div>
                                            <span class="btn btn-success hidden btn-upload-video btn-upload-file pull-left"><i class="fa fa-upload"></i></span>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('video'))
                                    <span class="help-block text-red clear-both">
                                    <strong>{{ $errors->first('video') }}</strong>
                                    </span>
                                @endif
                                {{ Form::hidden('video') }}
                                <input type="hidden" name="video_title" id="video-title" value="@lang('global.alojapan_video')">
                                <input type="hidden" name="video_description" id="video-description"  value="@lang('global.alojapan_video')">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="form-group clear-both">
                    <div class="col-sm-3 col-md-2">
                        <label class="control-label">Image</label>
                    </div>
                    <div class="col-sm-9 col-md-10">

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
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::checkbox('draft', null, null, ['class' => "minimal"]) }}
                    <label>@lang('global.draft_save')</label>
                    @if ($errors->has('draft'))
                        <span class="help-block text-red">
                                <strong>{{ $errors->first('draft') }}</strong>
                            </span>
                    @endif
                </div>
            </div>
            <!-- /.box -->
            <div class="box-footer text-center">
                <button type="submit" class="btn btn-default">@lang('button.cancel')</button>
                <button type="submit" class="btn btn-primary">@lang('button.submit')</button>
            </div>
        </div>
    </div>
    <!-- /.col-->
</div>