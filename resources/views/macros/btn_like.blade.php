<button type="button" class="btn btn-default btn-xs btn-like {{ in_array(Auth::id(), $userLiked) ? 'btn-primary' : 'btn-default' }}"
        value="{{ in_array(Auth::id(), $userLiked) ? '1' : '0' }}" target-type="{{ $targetType }}" target-id="{{ $targetId }}">
    <i class="fa fa-thumbs-o-up"></i>@lang('button.like')
</button>