<div class="row grid">
    @foreach($users as $user)
        @if(!empty($user->customer))
        <div class="col-md-6 grid-item">
            <div class="box box-default box-home-custom">
                <div class="box-header">
                    <div class="info-box">
                        <div class="info-box-icon bg-none">
                            <img src="{{ !empty($fullPathImage[$user->id]) ? $fullPathImage[$user->id] : asset('img/default_logo.png') }}" width="90px">
                        </div>
                        <div class="info-box-content">
                            <div class="info-box-text">
                                <a href="{!! route('profile', $user->id) !!}" class="title text-uppercase">{{ !empty($user->customer) ? $user->customer->customer_name : '' }}</a><br>
                                <a href="#" class="text-muted btn-like text-small" target-type="{{ LIKE_CUSTOMER_LIKE_TYPE }}" target-id="{{ $user->id }}" value = {{ isset($userLiked[$user->id]) ? $userLiked[$user->id] : 0 }}>
                                    <i class="fa fa-heart"></i>
                                    @lang('message.number_like', ['n' => !empty($countLikedForCustomer[$user->id]) ? $countLikedForCustomer[$user->id] : 0])
                                </a>
                                <a href="#" class="text-muted btn-link-comment">
                                    <i class="fa fa-comment"></i>@lang('message.number_comment', ['n' => !empty($countCommentedForCustomer[$user->id]) ? $countCommentedForCustomer[$user->id] : 0])
                                </a>
                            </div>
                            <div class="pull-right">
                                @include('macros.user_review', ['targetUser' => $user])
                            </div>
                            {{--*/ $i = 0 /*--}}
                            <div class="user-vote-small-ico pull-right">
                                <?php $i = 0; $userReviewedPaginate = $user->user_review_for_paginate;?>
                                @foreach($userReviewedPaginate as $row)
                                    {{--*/ $i++ /*--}}
                                    <?php $i++ ?>
                                    @if($i >3)
                                        @break
                                    @endif
                                    <span class="user-info-content">
                                        <img class="avatar" profile-id="{{ $row->user_review_id }}" width="20px" src="{{ !empty($fullPathImage[$row->user_review_id]) ? $fullPathImage[$row->user_review_id] : asset('img/medium-default-avatar.png') }}">
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                <div class="box-body">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="#tab_1_{{ $user->id }}" data-toggle="tab">@lang('global.user_review')</a></li>
                            <li><a href="#tab_2_{{ $user->id }}" data-toggle="tab">@lang('global.activity')</a></li>
                            <li class="active"><a href="#tab_3_{{ $user->id }}" data-toggle="tab">@lang('global.information')</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="tab_1_{{ $user->id }}">
                                <div class="row">
                                    <div class="col-md-12_{{ $user->id }}">
                                        {{--{!! Form::user_reviewed($user->user_review_for) !!}--}}
                                        @include('macros.user_reviewed', [
                                            'user_reviews' => $userReviewedPaginate,
                                            'userFullNames' => $userFullNames,
                                            'targetId' => $user->id,
                                            'targetType' => REVIEW_CUSTOMER_REVIEW_TYPE
                                        ])
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane active" id="tab_3_{{ $user->id }}">
                                <p class="title text-justify">{{ !empty($user->customer) ? $user->customer->description : null }}</p>

                                <strong><i class="fa fa-envelope margin-r-5"></i></strong><a href="#">{{ $user->email }}</a>
                                <strong><i class="fa fa-mobile-phone margin-r-5"></i></strong>{{ !empty($user->customer) ? $user->customer->phone : null }}<br>
                                <strong><i class="fa fa-street-view margin-r-5"></i></strong>{{ !empty($user->customer) ? $user->customer->address : null}}
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_2_{{ $user->id }}">
                                @include('homes.__customer_activity')
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>