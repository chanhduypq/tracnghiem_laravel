@extends('admins.layouts.admin')
@section('js-bottom')
    <script src="{{ asset("/js/admins/tool_action.js") }}"></script>
@stop
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary box-container">
                <div class="box-header">
                    @lang('global.japan')
                    <button class="btn btn-primary btn-sm pull-right btn-add-address" country-code="ja"><i class="fa fa-plus-square"></i>@lang('button.add')</button>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="title">@lang('global.city_name')</label>
                        <div class="col-md-12">
                            <div class="city-container">
                                <div class="input-inner">
                                    @if(!empty($locations['ja']))
                                        @foreach($locations['ja'] as $location)
                                            <div class="form-group">
                                                <div class="input-group my-colorpicker2 colorpicker-element">
                                                    <input class="form-control input-field" type="text" name="city_name[]" readonly="readonly" placeholder="@lang('global.input_name')" value="{{ $location->city_name }}">

                                                    <div class="input-group-addon">
                                                        <a href="#" class="btn-tool-action btn-edit" data-id="{{ $location->location_id }}" country-code="ja"><i class="fa fa-pencil"></i></a>
                                                        <a href="#" class="btn-tool-action btn-delete" data-id="{{ $location->location_id }}"><i class="fa fa-trash-o"></i></a>
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary box-container">
                <div class="box-header">
                    @lang('global.viet_nam')
                    <button class="btn btn-primary btn-sm pull-right btn-add-address" country-code="vn"><i class="fa fa-plus-square"></i>@lang('button.add')</button>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="title">@lang('global.city_name')</label>
                        <div class="col-md-12">
                            <div class="city-container">
                                <div class="input-inner">
                                    @if(!empty($locations['vn']))
                                        @foreach($locations['vn'] as $location)
                                            <div class="form-group">
                                                <div class="input-group my-colorpicker2 colorpicker-element">
                                                    <input class="form-control input-field" type="text" name="city_name[]" readonly="readonly" placeholder="@lang('global.input_name')" value="{{ $location->city_name }}">

                                                    <div class="input-group-addon">
                                                        <a href="#" class="btn-tool-action btn-edit" data-id="{{ $location->location_id }}" country-code="vn"><i class="fa fa-pencil"></i></a>
                                                        <a href="#" class="btn-tool-action btn-delete" data-id="{{ $location->location_id }}"><i class="fa fa-trash-o"></i></a>
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
    </div>
    <script id="temp_form_add_location" type="x-tmpl-mustache">
        <div class="form-group">
            <div class="input-group my-colorpicker2 colorpicker-element">
                <input class="form-control input-field" type="text" name="city_name[]" placeholder="@lang('global.input_name')">

                <div class="input-group-addon">
                    <a href="#" class="btn-tool-action btn-save" country-code="@{{ countryCode }}"><i class="fa fa-check"></i></a>
                    <a href="#" class="btn-tool-action btn-delete"><i class="fa fa-trash-o"></i></a>
                </div>
            </div>
            <!-- /.input group -->
        </div>
    </script>
@stop