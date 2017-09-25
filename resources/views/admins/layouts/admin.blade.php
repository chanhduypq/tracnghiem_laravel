<!DOCTYPE html>
<html>
@include('admins.includes.head')
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <!-- Header -->
    @include('admins.includes.header')

    <!-- Sidebar -->
    @include('admins.includes.sidebar')

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                @include('admins.includes.messages')
                <!-- Your Page Content Here -->
                @yield('content')
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

    </div><!-- ./wrapper -->

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/messages.js') }}"></script>
    <script src="{{ asset('AdLTE/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    <script src="{{ asset('AdLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('AdLTE/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>

    <!-- bootstrap datepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{ asset('AdLTE/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('AdLTE/plugins/daterangepicker/daterangepicker.js') }}"></script>

    <script src="{{ asset('bootstrap/js/start-rating.js') }}"></script>
    <!-- iCheck 1.0.1 -->
    <script src="{{ asset('AdLTE/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('AdLTE/plugins/select2/select2.full.min.js') }}"></script>
    {{--Mustache--}}
    <script src="{{ asset('js/lib/mustache.min.js') }}"></script>

    <script src="{{ asset('js/input.js') }}"></script>
    <script src="{{ asset('js/commons.js') }}"></script>
    @yield('js-bottom')

</body>
</html>