<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Dashboard Login - ARTUGO </title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<meta name="robots" content="noindex, nofollow">
	<!-- Bootstrap 3.3.4 -->
	<link href="{{ url('') }}/assets/backend/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
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
	<link href="{{ url('') }}/assets/backend/css/style.css" rel="stylesheet" type="text/css" />
	<link href="{{ url('') }}/assets/backend/css/custom.css" rel="stylesheet" type="text/css" />


	<!-- Icon -->

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body class="login-page">
	<div class="login-box col-md-4 col-md-offset-4">
		<div class="login-box-body">
			<div class="login-logo" style="line-height: 1;">
				<B>ARTUGO</B><br>
				<small style="font-size: 18px;">SYSTEM</small>
			</div>
			<p class="login-box-msg"><b>Sign in</b> to start your session</p>

			@if(Session::has('error'))
				<div class="alert alert-danger alert-block">
					<strong>{{ Session::get('error')}}</strong>
				</div>
			@endif
			<form action="{{ url('artmin/login-check')}}" method="post">
				{{ csrf_field() }}
				<div class="form-group has-feedback" id="username-container">
					<input type="text" name="username" id="username" class="form-control input-lg" placeholder="Username" value="{{ old('username') }}"/>
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
					<span id="userError" class="control-label"></span>
				</div>
				<div class="form-group has-feedback" id="password-container">
					<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password"/>
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					<span id="passError" class="control-label"></span>
				</div>
				<div class="row">
					<div class="form-group has-error text-center">
						<label class"control-label">

						</label>
					</div>
					<div class="col-xs-7">
						<small>Forgot your password ?<br>
							Please contact the administrator.</small>
						</div>
						<div class="col-xs-5">
							<button type="submit" class="btn btn-primary btn-block btn-flat" id="submit">Sign In</button>
						</div><!-- /.col -->
					</div>
				</form>

			</div><!-- /.login-box-body -->
		</div><!-- /.login-box -->
		<!-- Login logo large -->
		<!-- / Login logo large -->
		<script src="{{ url('') }}/assets/backend/plugins/jQuery/jQuery-2.1.3.min.js"></script>
		<!-- jQuery UI 1.11.2 -->
		<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>
	</body>
	</html>
