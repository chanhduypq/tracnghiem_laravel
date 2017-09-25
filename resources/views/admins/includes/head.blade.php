<head>
    <meta charset="UTF-8">
    <title>JP Community</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{!! csrf_token() !!}" />
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ asset('/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link href="{{ asset('/AdLTE/plugins/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/AdLTE/dist/css/AdminLTE.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('/AdLTE/dist/css/skins/skin-blue.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/admins/commons.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/AdLTE/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('/AdLTE/plugins/daterangepicker/daterangepicker.css') }}">


@yield('css')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery 2.1.4 -->
</head>