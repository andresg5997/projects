<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ config('website_title', null) }} | {{ ! empty($title) ? $title : 'Dashboard' }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ url('assets/images/favicons/favicon.png') }}" sizes="32x32">
    <!-- Bootstrap 3.3.4 -->
    {!! Html::style('/assets/admin/assets/dist/icons/font-awesome.min.css') !!}
    <!-- https://bootswatch.com/lumen/bootstrap.min.css-->
    <!-- https://bootswatch.com/flatly/bootstrap.min.css-->
    <!-- Font Awesome -->
    {!! Html::style('/assets/admin/assets/bootstrap/css/bootstrap.min.css') !!}
    <!-- Theme style -->
    @yield('style')
    {!! Html::style('/assets/admin/assets/dist/css/AdminLTE.min.css') !!}
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    {!! Html::style('/assets/admin/assets/dist/css/skins/skin-blue.css') !!}
    {!! Html::style('/assets/admin/assets/dist/fonts/fonts-fa.css') !!}
    {!! Html::style('/assets/admin/assets/plugins/notifications/toastr.css') !!}
    {!! Html::style('/assets/css/pnotify.custom.min.css') !!}
    {!! Html::style('assets/admin/assets/dist/css/pace.min.css') !!}


    <!-- {!! Html::style('/assets/public/admin/assets/dist/css/bootstrap-rtl.min.css') !!} ->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <![endif]-->

</head>
<!--<body class="skin-black sidebar-collapse sidebar-mini"> -->
<body class="skin-blue sidebar-mini">
<div class="wrapper">
    <!-- Include Top Header -->
    @include('admin.header')
    <!-- Include Left side column. contains the logo and sidebar -->
    @include('admin.sidebar')
    <!-- Include Content Wrapper. Contains page content -->
    @yield('page-content')
    <!-- /.content-wrapper -->
    <!-- Include Footer -->
    @include('admin.footer')
</div><!-- ./wrapper -->

<!-- jQuery 2.1.4 -->
{!! Html::script('/assets/admin/assets/plugins/jQuery/jQuery-2.1.4.min.js') !!}
<!-- jQuery UI 1.11.4 -->
{!! Html::script('/assets/admin/assets/plugins/jquery-ui/jquery-ui-1.11.4.min.js') !!}
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.4 -->
{!! Html::script('/assets/admin/assets/bootstrap/js/bootstrap.min.js') !!}
<!-- Slimscroll -->
{!! Html::script('/assets/admin/assets/plugins/slimScroll/jquery.slimscroll.min.js') !!}
<!-- FastClick -->
{!! Html::script('/assets/admin/assets/plugins/fastclick/fastclick.min.js') !!}

@yield('javascript')
<!-- AdminLTE App -->
{!! Html::script('/assets/admin/assets/dist/js/app.min.js') !!}
{!! Html::script('/assets/admin/assets/plugins/notifications/toastr.min.js') !!}

<script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
<script src="{{ url('assets/js/pnotify.custom.min.js') }}"></script>

<script>
    <!-- pace script -->
    // To make Pace works on Ajax calls
    $(document).ajaxStart(function() { Pace.restart(); });

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>
</body>
</html>
