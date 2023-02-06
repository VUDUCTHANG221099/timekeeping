<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard.get') }}" class="brand-link">
        <img src="{{ asset("$base_url/website/img/vaixgroup.jfif") }}" alt="VaixGroup"
            class="brand-image img-circle elevation-3" style="opacity: .8; width: 33.59px; height: 33.59px">
        <span class="brand-text font-weight-light">VaixGroup</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset("$base_url/website/img/avatar.jpg") }}" class="img-circle elevation-2"
                    alt="Male">
            </div>
            <div class="info">
                <a href="{{ route('dashboard.get') }}" class="d-block">{{ Auth::user()->name ?? false }}</a>
            </div>
        </div>
        <!-- SidebarSearch Form -->
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard.get') }}"
                        class="nav-link
                    {{ Request::routeIs('dashboard.get') ? 'active' : false }}
                    ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @can('policyEmployee', Auth::user())
                    <li class="nav-item">
                        <a href="{{ route('employee.get.changdPassword', ['id' => Auth::user()->id]) }}"
                            class="nav-link
                        {{ Request::routeIs('employee.get.changdPassword') ? 'active' : false }}
                        ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Đổi mật khẩu</p>
                        </a>
                    </li>
                @endcan
                @can('policy', Auth::user())
                    <li class="nav-item">
                        <a href="{{ route('employee.get.all') }}"
                            class="nav-link
                         {{ (Request::routeIs('employee.get.all') or
                         Request::routeIs('employee.get.viewCreate') or
                         Request::routeIs('employee.get.viewUpdate') or
                         Request::routeIs('employee.get.changdPassword') or
                         Request::routeIs('employee.get.percent') or
                         Request::routeIs('employee.get.detail'))
                             ? 'active'
                             : false }}
                        ">
                            <i class="nav-icon fa-solid fa-users"></i>
                            <p>Nhân viên</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('project.get.all') }}"
                            class="nav-link
                            {{ (Request::routeIs('project.get.all') or
                            Request::routeIs('project.get.viewCreate') or
                            Request::routeIs('project.get.viewUpdate') or
                            Request::routeIs('project.addEmployee.get') or
                            Request::routeIs('project.get.detail') or
                            Request::routeIs('projectListEmployees'))
                                ? 'active'
                                : false }}
                            ">
                            <i class="nav-icon fas fa-thin fa-diagram-project"></i>
                            <p>Dự án</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('role.get.all') }}"
                            class="nav-link
                            {{ (Request::routeIs('role.get.all') or
                            Request::routeIs('role.get.viewCreate') or
                            Request::routeIs('role.get.viewUpdate'))
                                ? 'active'
                                : false }}
                            ">
                            <i class="nav-icon fa-brands fa-critical-role"></i>
                            <p>Phân quyền</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('position.get.all') }}"
                            class="nav-link
                            {{ (Request::routeIs('position.get.all') or
                            Request::routeIs('position.get.viewCreate') or
                            Request::routeIs('position.get.viewUpdate'))
                                ? 'active'
                                : false }}
                            ">
                            <i class="nav-icon fa fa-globe"></i>
                            <p>Vai trò</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reportAdmin') }}"
                            class="nav-link
                            {{ Request::routeIs('reportAdmin') ? 'active' : false }}
                            ">
                            <i class="nav-icon fa fa-file"></i>
                            <p>Báo cáo</p>
                        </a>
                    </li>
                    <!--Admin Report-->
                    <li class="nav-item">
                        <a href="{{ route('adminReport') }}"
                            class="nav-link
                            {{ Request::routeIs('adminReport') ? 'active' : false }}
                            ">
                            <i class="nav-icon fa fa-file"></i>
                            <p>Tổng hợp</p>
                        </a>
                    </li>
                    <!--Admin Report-->
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
