<div class="box-footer box-comments">
    <div class="box-header">
        <form action="#" method="post">
            <!-- .img-push is used to add margin to elements next to floating images -->
            <div class="img-push col-sm-9">
                <input type="text" class="form-control input-sm" name="content" placeholder="Press enter to post comment">
            </div>
            <div class="col-sm-3">
                <button type="button" data-reply ='{"parent_id" : null, "grand_parent_id" : null, "target_type" : "{{ $targetType }}", "target_id" : "{{ $targetId }}" }' class="btn bg-green color-palette pull-right btn-block btn-sm btn-add-comment">Send</button>
            </div>
        </form>
    </div>
    @if(!empty($comments['data']))
    @foreach($comments['data'] as $comment)
        <div class="box-comment-container">
        <div class="box-comment box-comment-inner" comment-id="{{ $comment['comment_id'] }}" user-comment-id="{{ $comment['user_comment_id'] }}">
            <!-- User image -->
            <a href="{{ route('profile', $comment['user_comment_id']) }}">
                <img class="img-circle img-sm" src="{{ !empty($fullPathImage[$comment['user_comment_id']]) ? $fullPathImage[$comment['user_comment_id']] : asset('img/medium-default-avatar.png') }}" alt="User Image">
            </a>

            <div class="comment-text">
                <span class="username">{{ !empty($userFullNames[$comment['user_comment_id']]) ? $userFullNames[$comment['user_comment_id']] : trans('global.guest') }}
                    @if(\JP_COMMUNITY\Models\User::isOwner($comment['user_comment_id']))
                        <div class="dropdown pull-right">
                            <span class="dropdown-toggle dropdown-comment" data-toggle="dropdown"><i class="fa fa-angle-double-down"></i></span>
                            <ul class="dropdown-menu">
                                <li><a href="#" comment-id="{{ $comment['comment_id'] }}" class="btn-edit-comment"><i class="fa fa-pencil"></i>@lang('button.edit')</a></li>
                                <li><a href="#" comment-id="{{ $comment['comment_id'] }}" class="btn-delete-comment"><i class="fa fa-trash-o"></i>@lang('button.delete')</a></li>
                            </ul>
                        </div>
                    @endif
                    <span class="text-muted pull-right">{{ _ago($comment['created_at']) }}</span>
                </span><!-- /.username -->
                <span class="txt-content-inner">{!! $comment['content'] !!}</span>
                <span class="frm-content-inner" style="display: none"></span>
                <p>
                    <a href="#" class="btn-reply" data-reply='{"parent_id" : {{ $comment['comment_id'] }}, "grand_parent_id" : {{ $comment['comment_id'] }}, "target_type" : "{{ $targetType }}", "target_id" : "{{ $targetId }}" }'>@lang('global.reply')</a>
                    <a href="#" class="btn-like">@lang('global.like')</a>
                </p>
            </div>
            <!-- /.comment-text -->
        </div>
        <div class="box-comment-reply">
        @if(!empty($comment['childs']))
            <a href="#" class="show-more-child-comment"><i class="fa fa-arrow-circle-o-right"></i>@lang('global.show_more_comment', ['n'=> count($comment['childs'])])</a>
            @foreach($comment['childs'] as $commentChild)
            <div class="box-comment-inner" style="display: none">
                <!-- User image -->
                <a href="{{ route('profile', $commentChild['user_comment_id']) }}">
                    <img class="img-circle img-sm" src="{{ !empty($fullPathImage[$commentChild['user_comment_id']]) ? $fullPathImage[$commentChild['user_comment_id']] : asset('img/medium-default-avatar.png') }}" alt="User Image">
                </a>

                <div class="comment-text">
                    <div class="username">{{ !empty($userFullNames[$commentChild['user_comment_id']]) ? $userFullNames[$commentChild['user_comment_id']] : trans('global.guest') }}
                        @if(\JP_COMMUNITY\Models\User::isOwner($commentChild['user_comment_id']))
                            <div class="dropdown pull-right">
                                <span class="dropdown-toggle dropdown-comment" data-toggle="dropdown"><i class="fa fa-angle-double-down"></i></span>
                                <ul class="dropdown-menu">
                                    <li><a href="#" comment-id="{{ $commentChild['comment_id'] }}" class="btn-edit-comment"><i class="fa fa-pencil"></i>@lang('button.edit')</a></li>
                                    <li><a href="#" comment-id="{{ $commentChild['comment_id'] }}" class="btn-delete-comment"><i class="fa fa-trash-o"></i>@lang('button.delete')</a></li>
                                </ul>
                            </div>
                        @endif
                        <span class="text-muted pull-right">{{ _ago($commentChild['created_at']) }}</span>
                    </div><!-- /.username -->
                    <span class="txt-content-inner">{!! $commentChild['content'] !!}</span>
                    <span class="frm-content-inner" style="display: none"></span>
                    <p>
                        <a href="#" class="btn-reply"  data-reply='{"parent_id" : {{ $commentChild['comment_id'] }}, "grand_parent_id" : {{ $comment['comment_id'] }}, "target_type" : "{{ $targetType }}", "target_id" : "{{ $targetId }}" }'>@lang('global.reply')</a>
                        <a href="#" class="btn-like">@lang('global.like')</a>
                    </p>
                </div>
            </div>
            @endforeach
        @endif
            <div class="input-reply-inner"></div>
        </div>
        </div>
    @endforeach
    @endif

    <div class="box-footer">
        @if($comments['total'] > $comments['per_page'])
            <div class="col-md-12">
                <span class="pull-left txt-show-more-comment">
                    <a href="#" class="btn-comment-view-more" total="{{ $comments['total'] }}" per-page="{{ $comments['per_page'] }}" target-type="{{ $targetType }}" target-id="{{ $targetId }}" page="{{ $comments['current_page'] }}">
                        @lang('message.view_more_comment', ['n' => ($offset = $comments['per_page']*($comments['current_page'] + 1)) >= $comments['total'] ? $comments['total'] - $comments['per_page'] : $comments['per_page']])
                    </a>
                </span>
                <span class="pull-right txt-more-info">@lang('message.view_more_in_total', ['n' => $comments['per_page'], 't' => $comments['total']])</span>
            </div>
        @endif
    </div>
</div>