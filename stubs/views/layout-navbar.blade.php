<nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center bg-white container-p-x" id="layout-navbar">

    @empty($hide_layout_sidenav_toggle)
    <!-- Sidenav toggle -->
    <div class="layout-sidenav-toggle navbar-nav align-items-lg-center">
        <a class="nav-item nav-link px-0 ml-2" href="javascript:void(0)">
            <i class="ion ion-md-menu text-large align-middle"></i>
        </a>
    </div>
    @endempty

    <!-- Brand demo (see resources/assets/css/demo.css) -->
    <a href="/" class="navbar-brand app-brand demo d-lg-none py-0 mx-0">
        <span class="app-brand-logo demo rounded-0">
            @include('layouts.includes.sub.logo')
        </span>
        <span class="app-brand-text demo font-weight-normal ml-2">CPC1 Hà Nội</span>
    </a>

    <!-- Navbar toggle -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#layout-navbar-collapse">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-collapse collapse" id="layout-navbar-collapse">
        <!-- Divider -->
        <hr class="d-lg-none w-100 my-2">

        <div class="navbar-nav align-items-lg-center ml-auto">
            <div class="demo-navbar-notifications nav-item dropdown mr-lg-3">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" data-toggle="dropdown">
                    <i class="ion ion-md-notifications-outline navbar-icon align-middle"></i>
                    <span class="badge badge-primary badge-dot indicator"></span>
                    <span class="d-lg-none align-middle">&nbsp; Notifications</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="bg-primary text-center text-white font-weight-bold p-3">
                        Chức năng đang được phát triển
                    </div>
                    <div class="list-group list-group-flush">
                    </div>

                    <a href="javascript:void(0)" class="d-block text-center text-light small p-2 my-1">Show all notifications</a>
                </div>
            </div>

            <div class="demo-navbar-messages nav-item dropdown mr-lg-3">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" data-toggle="dropdown">
                    <i class="ion ion-ios-mail navbar-icon align-middle"></i>
                    <span class="badge badge-primary badge-dot indicator"></span>
                    <span class="d-lg-none align-middle">&nbsp; Messages</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="bg-primary text-center text-white font-weight-bold p-3">
                        Chức năng đang được phát triển
                    </div>
                    <div class="list-group list-group-flush">

                    </div>

                    <a href="javascript:void(0)" class="d-block text-center text-light small p-2 my-1">Show all messages</a>
                </div>
            </div>

            <!-- Divider -->
            @auth

                <div class="nav-item d-none d-lg-block text-big font-weight-light line-height-1 opacity-25 mr-3 ml-1">|</div>

                <div class="demo-navbar-user nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                        <span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
                            <img src="/img/avatars/cpc1hn.png" alt class="d-block ui-w-30 rounded-circle">
                            <span class="px-1 mr-lg-2 ml-2 ml-lg-0">[{{ Auth::user()->id }}] {{ Auth::user()->name }}</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0)" class="dropdown-item"><i class="ion ion-ios-person text-lightest"></i> &nbsp; My profile</a>
                        <a href="javascript:void(0)" class="dropdown-item"><i class="ion ion-ios-mail text-lightest"></i> &nbsp; Messages</a>
                        <a href="javascript:void(0)" class="dropdown-item"><i class="ion ion-md-settings text-lightest"></i> &nbsp; Account settings</a>
                        <div class="dropdown-divider"></div>

                        <span class="dropdown-item"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            <i class="ion ion-ios-log-out text-danger"></i> &nbsp; Đăng xuất
                        </span>

                        <form id="logout-form" action="{{ route('logout', ['urlback' => url()->current()]) }}" method="POST" class="d-none">
                            @csrf
                        </form>

                    </div>
                </div>

            @endauth

        </div>
    </div>

</nav>
