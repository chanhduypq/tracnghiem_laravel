<div class="overlay custom-modal" style="display: none">
    <i class="fa fa-refresh fa-spin"></i>
</div>
<div id="modal_message"></div>
<script id="temp_modal_profile" type="x-tmpl-mustache">
    <div class="modal" id="profile-user-@{{id}}">
        <input type='hidden' name='user_info' value=@{{ id }} profile-id=@{{ id }}>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close btn-modal-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <strong><i class="fa fa-star"></i></strong>@{{ txt_total_rating_for }}
                        <strong><i class="fa fa-envelope margin-r-5"></i></strong><a href="">@{{ email }}</a>
                        <strong><i class="fa fa-mobile-phone margin-r-5"></i></strong>@{{ phone }}<br>
                        <strong><i class="fa fa-street-view margin-r-5"></i></strong>@{{ address }}
                    </div>
                    <div class="col-md-12">
                        <ul class="rating-container">
                        @{{ #user_rating_for }}
                            @{{ #5 }}
                            <li>
                                <span>@lang('global.rating_start_5')</span>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100" style="width: @{{ percentage }}%">
                                        <span class="text-muted">@{{ vote_number }}</span>
                                    </div>
                                </div>
                            </li>
                            @{{ /5 }}
                            @{{ #4 }}
                            <li>
                                <span>@lang('global.rating_start_4')</span>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100" style="width: @{{ percentage }}%">
                                        <span class="text-muted">@{{ vote_number }}</span>
                                    </div>
                                </div>
                            </li>
                            @{{ /4 }}
                            @{{ #3 }}
                            <li>
                                <span>@lang('global.rating_start_3')</span>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100" style="width: @{{ percentage }}%">
                                        <span class="text-muted">@{{ vote_number }}</span>
                                    </div>
                                </div>
                            </li>
                            @{{ /3 }}
                            @{{ #2 }}
                            <li>
                                <span>@lang('global.rating_start_2')</span>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100" style="width: @{{ percentage }}%">
                                        <span class="text-muted">@{{ vote_number }}</span>
                                    </div>
                                </div>
                            </li>
                            @{{ /2 }}
                            @{{ #1 }}
                            <li>
                                <span>@lang('global.rating_start_1')</span>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100" style="width: @{{ percentage }}%">
                                        <span class="text-muted">@{{ vote_number }}</span>
                                    </div>
                                </div>
                            </li>
                            @{{ /1 }}

                        @{{ /user_rating_for }}
                        </ul>
                    </div>
                    </div>
                    <div class="modal-footer">
                    <div class="text-center">
                        <a href="user/@{{id}}/profile"><span class="btn btn-primary">@lang('global.profile')</span></a>
                        <a href="#" class="btn btn-primary btn-open-message" send-to=@{{ id }}>@lang('button.send_message')</a>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</script>
<script id="temp_modal_message" type="x-tmpl-mustache">
    <div class="modal" id="modal-send-message">
        {!! Form::open(['method' => 'POST']) !!}
            <input type="hidden" name="received_id" value="@{{ user_id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close btn-modal-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="subject">@lang('global.subject')</label>
                            {{ Form::text('subject', NULL, ['class' => "form-control"]) }}
                            <span class="help-block text-red subject">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="content">@lang('global.content')</label>
                            {{ Form::textarea('content', NULL, ['class' => "form-control", 'rows' => 3]) }}
                            <span class="help-block text-red content">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="text-center">
                            <a href="#" class="btn btn-default btn-modal-close">@lang('button.cancel')</a>
                            <a href="#" class="btn btn-primary btn-send-message" send-to=@{{ user_id }}>@lang('button.send')</a>
                        </div>
                    </div>
                </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        {!! Form::close() !!}
    </div>
<!-- /.modal -->
</script>