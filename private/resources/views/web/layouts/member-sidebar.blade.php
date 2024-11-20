
<div class="member-sidebar">
    <div class="sidebar-user">
        <!-- <b>Hi, {{ Session::get('member_name') }}</b> -->
    </div>
    <a href="{{ url('member/dashboard') }}" class="sidebar-item <?php if(Request::segment(2) == "dashboard") echo "active" ?>">Dashboard</a>
    <a href="{{ url('member/service') }}" class="sidebar-item <?php if(Request::segment(2) == "service") echo "active" ?>">Service</a>
    <a href="{{ url('member/profile') }}" class="sidebar-item <?php if(Request::segment(2) == "profile") echo "active" ?>">Profile</a>
    <a href="{{ url('member/point') }}" class="sidebar-item <?php if(Request::segment(2) == "point") echo "active" ?>">Point</a>
    <a href="{{ url('member/logout') }}" class="sidebar-item">Logout</a>
</div>
