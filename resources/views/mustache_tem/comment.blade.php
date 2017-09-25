<script id="temp_comment_content" type="x-tmpl-mustache">
    @{{#data}}
    @{{^grand_parent_id}}<div class="box-comment-container">@{{/grand_parent_id}}
        <div class="box-comment box-comment-inner">
            <!-- User image -->
            <a href="/user/@{{user_comment_id}}/profile">
                <img class="img-circle img-sm" src="@{{user_commented_image}}" alt="User Image">
            </a>

            <div class="comment-text" comment-id="@{{comment_id}}" user-comment-id=@{{user_comment_id}}>
                <span class="username">@{{user_commented_name}}
                    @{{#is_owner}}
                    <div class="dropdown pull-right">
                       <span class="dropdown-toggle dropdown-comment" data-toggle="dropdown"><i class="fa fa-angle-double-down"></i></span>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" comment-id="@{{comment_id}}" class="btn-edit-comment"><i class="fa fa-pencil"></i>@lang('button.edit')</a>
                            </li>
                            <li>
                                <a href="#" comment-id="@{{comment_id}}" class="btn-delete-comment"><i class="fa fa-trash-o"></i>@lang('button.delete')</a>
                            </li>
                        </ul>
                    </div>
                    @{{/is_owner}}
                    <span class="text-muted pull-right">@{{created_at}}</span>
                </span><!-- /.username -->
                <span class="txt-content-inner">@{{content}}</span>
                <p>
                    <a href="#" class="btn-reply" data-reply='{"parent_id" : @{{comment_id}}, "grand_parent_id" : @{{grand_parent_id}}@{{^grand_parent_id}}@{{comment_id}}@{{/grand_parent_id}}, "target_type" : "@{{target_type}}", "target_id" : "@{{target_id}}" }'>@lang('global.reply')</a>
                    <a href="#" class="btn-like">@lang('global.like')</a>
                </p>
                <span class="frm-content-inner" style="display: none"></span>
            </div>
            <!-- /.comment-text -->
        </div>
        <div class="box-comment-reply">
            @{{#show_more_comment_text}}<a href="#" class="show-more-child-comment"><i class="fa fa-arrow-circle-o-right"></i>@{{show_more_comment_text}}</a>@{{/show_more_comment_text}}
            @{{#childs}}
                <div class="box-comment-inner" style="display: none">
                    <!-- User image -->
                    <a href="/user/@{{user_comment_id}}/profile">
                        <img class="img-circle img-sm" src="@{{user_commented_image}}" alt="User Image">
                    </a>

                    <div class="comment-text">
                        <div class="username">@{{user_commented_name}}
                            @{{#is_owner}}
                            <div class="dropdown pull-right">
                                <span class="dropdown-toggle dropdown-comment" data-toggle="dropdown"><i class="fa fa-angle-double-down"></i></span>
                                <ul class="dropdown-menu">
                                    <li><a href="#" comment-id="@{{comment_id}}" class="btn-edit-comment"><i class="fa fa-pencil"></i>@lang('button.edit')</a></li>
                                    <li><a href="#" comment-id="@{{comment_id}}" class="btn-delete-comment"><i class="fa fa-trash-o"></i>@lang('button.delete')</a></li>
                                </ul>
                            </div>
                            @{{/is_owner}}
                            <span class="text-muted pull-right">@{{created_at}}</span>
                        </div><!-- /.username -->
                        <span class="txt-content-inner">@{{content}}</span>
                        <span class="frm-content-inner" style="display: none"></span>
                        <p>
                            <a href="#" class="btn-reply"  data-reply='{"parent_id" : @{{comment_id}}, "grand_parent_id" : @{{grand_parent_id}} @{{^grand_parent_id}}'null'@{{/grand_parent_id}}, "target_type" : "@{{target_type}}", "target_id" : "@{{target_id}}" }'>@lang('global.reply')</a>
                            <a href="#" class="btn-like">@lang('global.like')</a>
                        </p>
                    </div>
                </div>
            @{{/childs}}
            @{{^grand_parent_id}}
            <div class="input-reply-inner"></div>
            @{{/grand_parent_id}}
        </div>
    @{{^grand_parent_id}}</div>@{{/grand_parent_id}}
    @{{/data}}
</script>

<script id="temp_comment_btn-reply" type="x-tmpl-mustache">
    <div class="input-reply">
        <form>
        <div class="img-push col-sm-9">
            <input type="text" class="form-control input-sm" name="content" placeholder="Press enter to post comment">
        </div>
        <div class="col-sm-3">
            <button type="button" data-reply = "@{{data_reply}}" class="btn bg-green color-palette pull-right btn-block btn-sm btn-add-comment">@lang('global.send')</button>
        </div>
        </form>
    </div>
</script>

<script id="temp_comment_btn_add_comment" type="x-tmpl-mustache">
    <div class="box-group">
        <form>
            <div class="img-push col-sm-9">
                <input type="text" class="form-control input-sm" name="content" placeholder="Press enter to post comment">
            </div>
            <div class="col-sm-3">
                <button type="button" target-id = "@{{target_id}}" target-type="@{{target_type}}" class="btn bg-green color-palette pull-right btn-block btn-sm btn-add-comment">@lang('global.send')</button>
            </div>
        </form>
    </div>
</script>
{{--Replly comment template -- }}
<script id="temp_comment_reply" type="x-tmpl-mustache">

<div class="box-comment-reply">
    <!-- User image -->
    <a href="{{ route('profile', $comment->user_comment_id) }}">
        <img class="img-circle img-sm" src="{{ !empty($fullPathImage[$comment->user_comment_id]) ? $fullPathImage[$comment->user_comment_id] : asset('img/medium-default-avatar.png') }}" alt="User Image">
    </a>

    <div class="comment-text">
        <div class="username">{{ !empty($userFullNames[$comment->user_comment_id]) ? $userFullNames[$comment->user_comment_id] : trans('global.guest') }}
            @if(\JP_COMMUNITY\Models\User::isOwner($comment->user_comment_id))
                <div class="dropdown pull-right">
                    <span class="dropdown-toggle dropdown-comment" data-toggle="dropdown"><i class="fa fa-angle-double-down"></i></span>
                    <ul class="dropdown-menu">
                        <li><a href="#" comment-id="{{ $comment->comment_id }}" class="btn-edit-comment"><i class="fa fa-pencil"></i>@lang('button.edit')</a></li>
                        <li><a href="#" comment-id="{{ $comment->comment_id }}" class="btn-delete-comment"><i class="fa fa-trash-o"></i>@lang('button.delete')</a></li>
                    </ul>
                </div>
            @endif
            <span class="text-muted pull-right">{{ _ago($comment->created_at) }}</span>
        </div><!-- /.username -->
        <span class="txt-content-inner">{!! $comment->content !!}</span>
        <span class="frm-content-inner" style="display: none"></span>
        <p>
            <a href="#" class="btn-reply">@lang('global.reply')</a>
            <a href="#" class="btn-like">@lang('global.like')</a>
        </p>
        <div class="input-reply">
            <div class="img-push col-sm-9">
                <input type="text" class="form-control input-sm" name="reply_content" placeholder="Press enter to post comment">
            </div>
            <div class="col-sm-3">
                <button type="button" target-id = {{ $targetId }} target-type="{{ $targetType }}" data-parent-id="0" class="btn bg-green color-palette pull-right btn-block btn-sm btn-add-comment">Send</button>
            </div>
        </div>
    </div>
</div>
</script>
{{-- --}}
<script id="temp_comment_edit" type="x-tmpl-mustache">
    <div class="edit-comment-inner row">
        <div class="img-push col-sm-9">
            <input type="text" class="form-control input-sm" name="content" value="@{{content}}" placeholder="Press enter to post comment">
        </div>
        <div class="col-sm-12">
            <div class = "pull-left">
            <button type="button" target-id = "@{{comment_id}}" class="btn btn-primary pull-right btn-sm btn-update-comment">Save</button>
            <button type="button" class="btn btn-default pull-right btn-sm btn-discard-comment">Cancel</button>
            </div>
        </div>
    </div>
</script>