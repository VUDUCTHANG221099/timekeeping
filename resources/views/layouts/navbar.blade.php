<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard.get') }}" class="nav-link">Dashboard</a>
        </li>
        @can('policy', Auth::user())
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('redmineIndex') }}" class="nav-link"
                onclick="return confirm('Bạn có muốn đồng bộ hóa với Redmine không ?')">Đồng bộ hóa với Redmine</a>
            </li>
        @endcan


    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->

        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout.get') ?? false }}" role="button">

                <i class="fa fa-sign-out" aria-hidden="true"></i>

            </a>
        </li>
    </ul>
</nav>
