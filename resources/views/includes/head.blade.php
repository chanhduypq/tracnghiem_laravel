<head>
    <title>@yield('title')</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8;" />
   
    <script type="text/javascript" src="{{ asset('js/jquery-2.0.3.js') }}"></script>
    <!--bootstrap-->
    <link href="{{ asset('bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="textcss"/>
    <link href="{{ asset('bootstrap/css/bootstrap-responsive.css') }}" rel="stylesheet" type="textcss"/> 
    <script type="text/javascript" src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>        
    <!--end bootstrap-->
    <!--menu-->
    <link href="{{ asset('css/menu/menu.css') }}" media="screen" rel="stylesheet" type="textcss" />				 
    <!--end menu-->  
    <!-- top bottom-->
    <link href="{{ asset('css/top_bottom.css') }}" rel="stylesheet" type="textcss"/>
    <!-- end top bottom-->
    <link href="{{ asset('css/phan_trang.css') }}" rel="stylesheet" type="textcss"/> 
    <!--layout-->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="textcss"/>
    <!--end layout--> 

    <link rel="stylesheet" href="{{ asset('jquery-ui-1.10.3/themes/smoothness/jquery-ui.css') }}" type="textcss"/>
    <link rel="stylesheet" type="textcss" href="{{ asset('css/jquery.cluetip.css') }}" media="all" />

    <script type="text/javascript" src="{{ asset('js/jquery.textarea-expander.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.cluetip.js') }}"></script>
    <script type="text/javascript" src="{{ asset('jquery-ui-1.10.3/ui/jquery-ui.js') }}"></script>          
    <!--progress load page-->
    <script type="text/javascript" src="{{ asset('js/page_loader/jquery.queryloader2.js') }}"></script>
    <!--end progress load page-->


    <script type="text/javascript" src="{{ asset('js/jquery.alerts.js') }}"></script>
    <link href="{{ asset('css/jquery.alerts.css') }}" rel="stylesheet" type="textcss"/>

    <!-- append form for click in index page-->
    <!--<script type="text/javascript" src="{{ asset('js/build_form_click.js') }}"></script>-->
    <!-- end append form for click in index page-->

    <script type="text/javascript" src="{{ asset('js/jquery.dropotron.min.js') }}"></script>


    <!--hiệu ứng chuyển động ảnh-->
    @if ($logo['dynamic'] == "1") 
        <script type="text/javascript" src="{{ asset('js/jquery.cycle.all.js') }}"></script>
    @endif
    <!--end hiệu ứng chuyển động ảnh-->
    <!--chạy chữ-->
    @if ($header_text_dynamic == "1") 
        <script type="text/javascript" src="{{ asset('js/animation_text/textTyper.js') }}"></script>
    @endif
    <!--end chạy chữ-->     
    <script type="text/javascript">

        /**
         * index.phtml             
         */
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $("#gotop").removeAttr("href");
                $("#gotop").stop().animate({
                    bottom: "0",
                    right: "0"
                }, {
                    duration: 800,
                    queue: false
                });
            } else {
                $("#gotop").stop().animate({
                    bottom: "-50",
                    right: "-50"
                }, {
                    duration: 1000,
                    queue: false
                });
            }
            if ($(this).scrollTop() > 100) {
                $("#gobottom").removeAttr("href");
                $("#gobottom").stop().animate({
                    top: "-50",
                    right: "-50"
                }, {
                    duration: 800,
                    queue: false
                });
            } else {
                $("#gobottom").stop().animate({
                    top: "0",
                    right: "0"
                }, {
                    duration: 1000,
                    queue: false
                });
            }

        });
        /**
         * end index.phtml
         **/
        jQuery(function ($) {

@if ($logo['dynamic'] == "1") 
                $('#logo_header').cycle({
                    fx: 'zoom', sync: false, delay: -2000
                });
@endif

@if ($header_text_dynamic == "1") 
                $('div#simpleDiv').textTyper({repeatAnimation: true, repeatTimes: 100, speed: 50});
@endif


            /**
             * index.phtml
             **/
            $("body").queryLoader2({
                barColor: "#6e6d73",
                backgroundColor: "#fff1b0",
                percentage: true,
                barHeight: 5,
                completeAnimation: "grow",
                minimumTime: 100
            });
            $("#gotop").click(function () {
                $("html, body").animate({
                    scrollTop: 0
                }, "slow");
                return false;
            });
            $("#gobottom").click(function () {
                $("html, body").animate({
                    scrollTop: $('#footer').offset().top
                }, 970);
                return false;
            });
            /**
             * end index.phtml
             **/
@if (isset($questions) && count($questions) > 0) 
                $("#fixed").css('top', $("#add-new").offset().top + $("#add-new").height() + 20);
                h = $(window).height() - ($("#add-new").offset().top + $("#add-new").height()) - 40;
                $("#fixed").css('height', h + 'px');
@endif
        });

    </script>

</head>