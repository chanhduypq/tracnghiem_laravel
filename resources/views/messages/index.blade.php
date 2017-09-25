@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="container">
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-3 col-sm-4">
                        <div class="box box-primary">
                            <div class="box-header"><h3>@lang('global.message_inbox')</h3></div>
                            <div class="box-list">
                                @include('macros.list_message', ['messages' => $authInfo['message']['received']])
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-8">
                        <div class="box box-primary">
                            <div class="box-header"><h3>@lang('global.header_content_message')</h3></div>
                            <div class="box-body">
                                <div id="box-message-content">
                                    @if(!empty($showMessage))
                                        {{ $showMessage->content }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('mustache_tem.modal_profile')
@stop