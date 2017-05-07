<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ $name or "default" }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">MENU</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="{{ url('home') }}"><i class="fa fa-tv"></i> <span>Home</span></a></li>
            <li><a href="{{ url('hosts') }}"><i class="fa  fa-cubes"></i> <span>Hosts</span></a></li>
            {{--<li><a href="#"><i class="fa fa-gear"></i>  <span>Configuration</span></a></li>--}}

            <li class="treeview">
                <a href="#"><i class="fa fa-heartbeat"></i> <span>Monitor</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('monitor/tasks') }}"><i class="fa fa-file"></i>  <span>Tasks</span></a></li>
                    <li><a href="{{ url('monitor/message') }}"><i class="fa fa-envelope"></i>  <span>Messages</span></a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#"><i class="fa fa-stethoscope"></i> <span>WebShell Detection</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('webshell/tasks') }}"><i class="fa fa-file"></i>  <span>Scan Tasks</span></a></li>
                    <li><a href="{{ url('webshell/discover') }}"><i class="fa fa-envelope"></i>  <span>WebShell Discovered</span></a></li>
                    <li><a href="{{ url('webshell/discover/whitelist') }}"><i class="fa fa-list-alt"></i>  <span>WebShell White List</span></a></li>
                    <li><a href="{{ url('webshell/discover/history') }}"><i class="fa fa-history"></i>  <span>Option History</span></a></li>
                    {{--<li><a href="{{ url('webshell/rules') }}"><i class="fa fa-map-o"></i>  <span>WebShell Rules</span></a></li>--}}
                </ul>
            </li>

            @if (env("ADMIN_ACCOUNT")===session("email")[0])
            <li class="treeview">
                <a href="#"><i class="fa fa-cog"></i> <span>Management</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('users') }}"><i class="fa fa-group"></i>  <span>Users</span></a></li>
                    <li><a href="#"></a></li>
                </ul>
            </li>
            @endif
            <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> <span>Log Out</span></a></li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
