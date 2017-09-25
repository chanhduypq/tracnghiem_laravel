@if($users->is_staff)
    <section class="content">
        <div class="container">
            <!-- form start -->
            <form role="form" target="{{ route('update_profile', Auth::user()->id) }}" method="post">
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{ Lang::get('global.base_information') }}</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="customer_name">{{ Lang::get('global.customer_name') }}</label>
                                    <input type="text" class="form-control" id="customer_name" placeholder="{{ Lang::get('global.input_customer_name') }}"></input>
                                </div>
                                <div class="form-group">
                                    <label for="foundation_date">{{ Lang::get('global.foundation_date') }}</label>
                                    <input type="text" class="form-control datepicker" id="foundation_date" placeholder="{{ Lang::get('global.input_foundation_date') }}"></input>
                                </div>
                                <div class="form-group">
                                    <label for="fax">{{ Lang::get('global.fax') }}</label>
                                    <input type="text" class="form-control" id="fax" placeholder="{{ Lang::get('global.input_fax') }}"></input>
                                </div>
                                <div class="form-group">
                                    <label for="locationField">{{ Lang::get('global.location_field') }}</label>
                                    <input id="autocomplete" class="form-control" placeholder="{{ Lang::get('global.input_your_address') }}" onFocus="geolocate()" type="text"></input>
                                </div>
                                <div class="form-group">
                                    <label for="suggest_address">{{ Lang::get('global.street_address') }}</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="street_number" placeholder="{{ Lang::get('global.input_street_number') }}" disabled="true"></input>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control" class="form-control" id="route" disabled="true"></input>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="suggest_address">{{ Lang::get('global.city') }}</label>
                                    <input type="text" class="form-control" id="locality" disabled="true"></input>
                                </div>
                                <div class="form-group">
                                    <label for="postal_code">{{ Lang::get('global.state') }}</label>
                                    <input type="text" class="form-control" id="postal_code" disabled="true"></input>
                                </div>
                                <div class="form-group">
                                    <label for="administrative_area_level_1">{{ Lang::get('global.zip_code') }}</label>
                                    <input type="text" class="form-control" id="administrative_area_level_1" disabled="true"></input>
                                </div>
                                <div class="form-group">
                                    <label for="country">{{ Lang::get('global.country') }}</label>
                                    <input type="text" class="form-control" id="country" disabled="true"></input>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{ Lang::get('global.base_information') }}</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group">
                                    <label>{{ Lang::get('global.customer_description') }}</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="{{ Lang::get('global.input_customer_description') }}"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>{{ Lang::get('global.customer_detail') }}</label>
                                    <textarea class="textarea" rows="6" id="customer_detail" name="detail" placeholder="{{ Lang::get('global.input_detail') }}"
                                              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="foundation_date">{{ Lang::get('global.upload_video') }}</label>
                                    <input type="file" name="detail" class="form-control" placeholder="{{ Lang::get('global.input_detail') }}"></input>
                                </div>
                                <div class="form-group">
                                    <label for="foundation_date">{{ Lang::get('global.upload_image') }}</label>
                                    <input type="file" name="detail" class="form-control" placeholder="{{ Lang::get('global.input_detail') }}"></input>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">{{ Lang::get('global.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endif