<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('artmin') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
            ART
        </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg" style="font-size: 16px;">
            <b>ARTUGO</b>
        </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <div class="loading">
                <div class="loader"></div>
            </div>
            <ul class="nav navbar-nav">
                <li class="">
                    <a href="#" data-toggle="" data-placement="bottom" title="Profile">
                        Hi, {{ Auth::user()->name }}
                    </a>
                </li>
                <li>
                    <a href="{{ url('artmin/logout/') }}" data-toggle="tooltip" data-placement="bottom" title="Logout">
                        <i class="ion ion-log-out" style="font-size: 20px;"></i>
                    </a>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <!-- <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
            </li> -->
        </ul>
    </div>
</nav>
</header>
