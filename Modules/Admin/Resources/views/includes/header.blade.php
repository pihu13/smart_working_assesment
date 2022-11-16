<?php
$addProduct = \Route::current()->getName();
if ($addProduct != "admin.add.product") {
    Helper::deleteTempListProduct();
}
?>
<div class="navbar navbar-expand flex-column flex-md-row align-items-center navbar-custom">
    <div class="container-fluid">
        <?php
        $headerLogo = Helper::getGeneralSettingLogo("header-logo");
        ?>
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand mr-0 mr-md-2 logo">
            <?php
            if (@$headerLogo["option_value"] && !empty($headerLogo["option_value"])) {
                ?>
                <img src="{{ url('/images/1639979612.jpg')  }}" alt="Logo">
            <?php } else { ?>
                <img src="{{ url('/images/1639979612.jpg')  }}" alt="Logo">
            <?php } ?>
        </a>
        <button type="button" class="navigation-btn"><i class="fal fa-bars"></i></button>
        <ul class="navbar-nav flex-row ml-auto d-flex align-items-center list-unstyled topnav-menu mb-0">
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <i class="far fa-bell"></i>
                    <span class="noti-icon-badge">0</span>
                </a>
            </li>
            <li class="dropdown user-link">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <i class="far fa-cog"></i>
                    <span class="noti-icon-badge"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-lg">
                    <a href="{{route('admin.edit.admin')}}" class="dropdown-item"> <i class="fal fa-user"></i> My Profile</a>
                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="{{ route('admin.logout') }}"onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="fal fa-sign-out"></i> Logout</a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="GET" style="display: none;">@csrf</form>

                </div>
            </li>
        </ul>
    </div>
</div> 