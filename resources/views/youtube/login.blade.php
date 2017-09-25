@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="container">
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Youtube Login required
                    </h1>
                    <a href="{{ $authUrl }}">@lang('global.login')</a>
                </section>
            </div>
        </div>
    </section>
@stop