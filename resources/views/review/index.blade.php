@extends('layouts.index')
@section('css')
    <link href="{{ asset('css/question.css') }}?<?php echo substr(md5(microtime()), rand(0, 26), 5); ?>" rel="stylesheet" type="text/css"/>
@endsection
@section('content')    
    @include('review.partial.form_nganhnghe_capbac')
    <div class="row-fluid">
        <div class="span12"></div>
    </div>
    @include('review.partial.form_question')
@endsection





