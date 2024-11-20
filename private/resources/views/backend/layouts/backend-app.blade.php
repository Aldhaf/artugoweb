<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - ARTUGO SERVICE </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="robots" content="noindex, nofollow">
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ url('') }}/assets/backend/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap-grid.min.css') }}" type="text/css" />
    <!-- FontAwesome 4.3.0 -->
    <link href="{{ url('') }}/assets/backend/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 -->
    <link href="{{ url('') }}/assets/backend/plugins/ionicons/css/ionicons.min.css" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Tags Input -->
    <link href="{{ url('') }}/assets/backend/plugins/bootstrap-tags-input/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('') }}/assets/backend/plugins/bootstrap-tags-input/bootstrap-tagsinput-typeahead.css" rel="stylesheet" type="text/css" />


    <!-- Add fancyBox -->
    <link rel="stylesheet" href="{{ url('') }}/assets/backend/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />

    <!-- Optionally add helpers - button, thumbnail and/or media -->
    <link rel="stylesheet" href="{{ url('') }}/assets/backend/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />

    <link rel="stylesheet" href="{{ url('') }}/assets/backend/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />

    <!-- Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="{{ url('') }}/assets/backend/css/skins/_all-skins.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="{{ url('') }}/assets/backend/plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="{{ url('') }}/assets/backend/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="{{ url('') }}/assets/backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="{{ url('') }}/assets/backend/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('') }}/assets/plugins/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="{{ url('') }}/assets/backend/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="{{ url('') }}/assets/backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
    <!-- DATA TABLES -->
    <link rel="stylesheet" href="{{ url('') }}/assets/backend/plugins/datatables/media/css/dataTables.bootstrap.css"/>

    <!-- SELECTIZE -->
    <link rel="stylesheet" href="{{ url('') }}/assets/backend/plugins/selectize/css/selectize.css"/>
    <link rel="stylesheet" href="{{ url('') }}/assets/backend/plugins/selectize/css/selectize.bootstrap3.css"/>
    <!-- Full Calendar -->
    <!-- <link rel="stylesheet" href="{{ url('') }}/assets/backend/plugins/fullcalendar/fullcalendar.min.css"/> -->



    <!-- Theme style -->
    <link href="{{ url('') }}/assets/backend/css/style.css?v=1.0.3" rel="stylesheet" type="text/css" />
    <link href="{{ url('') }}/assets/backend/css/custom.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('assets/plugins/slick/slick.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/slick/slick-theme.css') }}" type="text/css" />

    <!-- Icon -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<!-- jQuery 2.1.3 -->
<script src="{{ url('') }}/assets/backend/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/slick/slick.min.js')}}"></script>
<!-- jQuery UI 1.11.2 -->
<script src="https://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!-- Chart JS -->
<script src="{{ url('') }}/assets/backend/plugins/chartjs/Chart.min.js" type="text/javascript"></script>
<!-- Morris Chart -->
<script src="{{ url('') }}/assets/backend/plugins/morris/morris.min.js" type="text/javascript"></script>

<script src="{{ url('') }}/assets/backend/plugins/selectize/js/selectize.min.js" type="text/javascript"></script>

<!-- <script src="{{ url('') }}/assets/backend/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
{{-- <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script> --}}

<body class="skin-black <?php if(isset($_SESSION['sidebar'])){ if($_SESSION['sidebar'] == "1") echo "sidebar-collapse"; } ?>">
    <div class="wrapper">
        <!-- Header -->
        @include('backend.layouts.header')
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            @include('backend.layouts.sidebar')
            <!-- /.sidebar -->
        </aside>
        <div class="content-wrapper" id="content">
            @yield('content')
        </div><!-- /.content-wrapper -->
        <footer class="main-footer no-print">
            <div class="pull-right hidden-xs">
                <!-- <b>Version</b> 1.0 -->
            </div>
            <strong>ARTUGO Service System.</strong>
        </footer>
    </div><!-- ./wrapper -->



<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.2 JS -->
<script src="{{ url('') }}/assets/backend/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js" type="text/javascript"></script>

<script src="{{ url('') }}/assets/backend/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- datepicker -->
<script src="{{ url('') }}/assets/backend/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="{{ url('') }}/assets/plugins/bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js" type="text/javascript"></script>

<!-- Bootstrap WYSIHTML5 -->
<script src="{{ url('') }}/assets/backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<!-- Slimscroll -->
<script src="{{ url('') }}/assets/backend/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src="{{ url('') }}/assets/backend/plugins/fastclick/fastclick.min.js"></script>
<!-- iCheck -->
<script src="{{ url('') }}/assets/backend/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<!-- Full Calendar
<script src="{{ url('') }}/assets/backend/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script> -->
<!-- Tiny MCE -->
<script src="{{ url('') }}/assets/backend/plugins/tinymce/tinymce.min.js" type="text/javascript"></script>
<!-- Bootstrap Tags Input -->
<script src="{{ url('') }}/assets/backend/plugins/bootstrap-tags-input/bootstrap-tagsinput.js" type="text/javascript"></script>

<!-- DATA TABES SCRIPT -->
<script src="{{ url('') }}/assets/backend/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="{{ url('') }}/assets/backend/plugins/datatables/media/js/dataTables.bootstrap.min.js"></script>
<script src="{{ url('') }}/assets/backend/plugins/datatables/media/js/jquery.dataTables.dd-mmm-yyyy.js"></script>
<script src="{{ url('') }}/assets/backend/plugins/datatables/media/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">
$('.data-table-np').DataTable({
    "paging": false
});
</script>

<!-- Price Format -->
<script src="{{ url('') }}/assets/backend/js/jquery.price_format.2.0.min.js" type="text/javascript"></script>


<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="{{ url('') }}/assets/backend/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<script type="text/javascript" src="{{ url('') }}/assets/backend/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<script type="text/javascript" src="{{ url('') }}/assets/backend/plugins/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="{{ url('') }}/assets/backend/plugins/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<script type="text/javascript" src="{{ url('') }}/assets/backend/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<!-- Script -->
<script src="{{ url('') }}/assets/backend/js/script.js?v={{env('SCRIPTJS_VERSION')}}" type="text/javascript"></script>
<!-- App -->
<script src="{{ url('') }}/assets/backend/js/app.js" type="text/javascript"></script>

<script>

    $('.label-url').on("click", function(){
        $(this).select();
    })

</script>

</body>
</html>
