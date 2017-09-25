<ul class="list-group list-group-unbordered">
    @foreach($messages as $row)
        <li class="list-group-item user-info-content {{ !empty($readMessage) && $readMessage->message_id == $row->message_id ? 'active' : '' }}"><!-- start message -->
            <a href="#" class="detail-message" data-id="{{ $row->message_id }}">
                <div class="pull-left">
                    <img src="{{ asset('/img/default_avatar.png') }}" width="50px" class="img-circle avatar" profile-id="{{ $row->sender_id }}" alt="User Image">
                </div>
                <h4>
                    {{ !empty($userFullNames[$row->sender_id]) ? $userFullNames[$row->sender_id] : null }}
                    <small class="pull-right"><i class="fa fa-clock-o"></i>&nbsp;{{ _ago($row->created_at) }}</small>
                </h4>
            </a>
            <p class="{{ $row->status == 0 ? 'text-bold' : null }}">{!! $row->subject !!}</p>
        </li>
    @endforeach
    @if($messages->hasPages())
    <li class="list-group-item">
        <div class="box-footer">
            <div class="col-md-12">
            <span class="pull-left txt-show-more-message">
                <a href="#" class="btn-message-show-more" total="{{ $messages->total() }}" per-page="{{ $messages->perPage() }}" target-id="{{ $row->received_id }}" page="{{ $messages->currentPage() }}">
                    @lang('message.show_more_message', ['n' => ($offset = $messages->perPage()*($messages->currentPage() + 1)) >= $messages->total() ? $messages->total() - $messages->perPage() : $messages->perPage()])
                </a>
            </span>
                <span class="pull-right txt-more-info">@lang('message.view_more_in_total', ['n' => $messages->perPage(), 't' => $messages->total()])</span>
            </div>
        </div>
    </li>
    @endif
</ul>