<div class="form-group">
    <div class="col-md-2">
        <label class="control-label">@lang('global.video')</label>
    </div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-12">
                <span id="upload-video-info" target-type="{{ $targetType }}" target-id="{{ $targetId }}"></span>
                <div id="video-contain">
                    @if(!empty($videoId))
                        <embed width="200" src="https://www.youtube.com/embed/{{ $videoId }}"></embed>
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
                      data-clientid="{{ config('youtube.client_id') }}"
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
                                <input id="video" name="video_file" type="file" multiple class="file-upload" onchange="$('#lb-upload-video-info').html($(this).val())">
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