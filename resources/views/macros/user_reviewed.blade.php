<ul class="user-contain">
    @foreach($user_reviews as $review)
        <li>
            <div class="user-info-content pull-left">
                <div class="avatar info-box-icon">
                    <img src="{{ !empty($fullPathImage[$review->user_review_id]) ? $fullPathImage[$review->user_review_id] : asset('img/medium-default-avatar.png') }}" width="70px"  alt="Avatar" class="avatar" profile-id="{{ $review->user_review_id }}">
                </div>

                {{--<h3><div class="user-info">{{--<h3>
                    <a href="{{ route('profile', $review->user_review_id) }}">
                        {{ !empty($userFullNames[$review->user_review_id]) ? $userFullNames[$review->user_review_id] : trans('global.guest') }}
                    </a>
                </h3>
                <span class="fa fa-eye"></span>@lang('message.number_reviewed', ['n' => !empty($countUsersReviewed[$review->user_review_id]) ? $countUsersReviewed[$review->user_review_id] : 0])<br>
                <span class="fa fa-thumbs-o-up"></span>@lang('message.number_like', ['n' => !empty($countUsersLiked[$review->user_review_id]) ? $countUsersLiked[$review->user_review_id] : 0])
                </div>--}}
            </div>
            <div class="user-vote-content content-wrapper-text">
                <input name="rating" class="rating rating-loading" data-size = "xs" value="{{ $review->value }}" data-display-only="true" data-show-capction="false" data-show-clear="false">
                <p class="title">{!! $review->title !!}</p>
                <p class="content-container short-text dont-break-out">
                    {!! $review->content !!}
                    <div class="show-more">
                        <a href="#">Show more</a>
                    </div>
                </p>
                <a href="#" class="fa {{ !empty($curUserReported[$review->review_id]) ? 'fa-flag' : 'fa-flag-o' }} btn-report" data-content="{'title' : '{{ $review->title }}', 'content' : '{{ $review->content }}'" target-type="{{ REPORT_CUSTOMER_REVIEWED_TYPE }}" target-id="{{ $review->review_id }}" title="@lang('global.report_reviewed')"></a>
                @include('macros.btn_like', ['userLiked'=> !empty($userLikeReviewed[$review->review_id]) ? $userLikeReviewed[$review->review_id] : [], 'targetId' => $review->review_id, 'targetType' => LIKE_CUSTOMER_REVIEWED_TYPE])
                <a class="show-comment btn-load-add-comment" href="#"
                   data-reply ='{"parent_id" : null, "grand_parent_id" : null, "target_type" : "{{ COMMENT_CUSTOMER_REVIEW_TYPE }}", "target_id" : "{{ $review->review_id }}" }'>
                   <i class="fa fa-comments-o"></i>@lang('global.load_comment', ['n' => count($review->comments)])
                </a>
            </div>

            <div class="box-comments">
                <div class="box-footer">
                    <div class="box-header">
                        <div class="form-input-comment"></div>
                    </div>
                </div>
            </div>
        </li>
    @endforeach
    <li class="footer-inner">
        <div class="col-md-12 box-footer">
            @if($user_reviews->hasPages())
                <div class="col-md-12">
                    <span class="pull-left txt-show-more-comment">
                        <a href="#" class="btn-reviewed-load-more" total="{{ $user_reviews->total() }}" per-page="{{ $user_reviews->perPage() }}" target-type="{{ REVIEW_CUSTOMER_REVIEW_TYPE }}" target-id="{{ $targetId }}" page="{{ $user_reviews->currentPage() }}">
                            @lang('message.view_more_customer_reviewed', ['n' => ($offset = $user_reviews->perPage()*($user_reviews->currentPage() + 1)) >= $user_reviews->total() ? $user_reviews->total() - $user_reviews->perPage() : $user_reviews->perPage()])
                        </a>
                    </span>
                    <span class="pull-right txt-more-info">@lang('message.view_more_in_total', ['n' => $user_reviews->perPage(), 't' => $user_reviews->total()])</span>
                </div>
            @endif
        </div>
    </li>
</ul>
