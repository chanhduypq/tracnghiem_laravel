{!! Form::open(['route' => ['user_review', $targetUser->id], 'action' => 'POST', 'id' => 'frm_user_review']) !!}
    <label class="pull-right">@lang('message.number_reviewed', ['n' => count($targetUser->reviewed_for)])</label>
    @if($_userType == 'is_client')
    <input name="rating" class="rating rating-loading" data-size = "xs" data-show-clear="false" data-show-caption="false"
           value="{{ !empty($_curUserReviewed[$targetUser->id]) ? $_curUserReviewed[$targetUser->id] : null }}">
    @elseif($_userType == 'is_customer')
        <input name="rating" class="rating rating-loading" data-readonly="true" data-size = "xs"
               data-show-clear="false" data-show-caption="false" value="5">
    @else
        <input name="rating" class="rating rating-loading" data-readonly="false" data-size = "xs"
               data-show-clear="false" data-show-caption="false" value="0">
    @endif
{!! Form::close() !!}

{{--
{!! Form::open(['route' => ['user_review', $targetUser->id], 'action' => 'POST', 'id' => 'frm_user_review']) !!}
    <ul class="user-contain">
        <li>
            <div class="user-info-content pull-left">
                <div class="avatar">
                    <img src="{{ Auth::check() && !empty($fullImagePath[Auth::id()]) ? $fullImagePath[Auth::id()] : asset('img/medium-default-avatar.png')}}" width="100px" alt="Avatar">
                </div>
                <div class="user-info">
                    <h3>{{ !empty($user) ? $user->client_name : trans('global.guest') }}</h3>
                </div>
            </div>
            <div class="user-vote-content pull-left">
                <h3 class="title">@lang('message.start_rating_for_customer', ['name' => $targetUser->customer_name])</h3>
                <input name="rating" class="rating rating-loading" data-size = "xs" data-show-clear="false">
            </div>
        </li>
    </ul>
{!! Form::close() !!}--}}
