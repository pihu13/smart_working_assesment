<div class="dashboard-menu">
    <div class="nav-menu">
        <div class="user-info">
            <div class="user-icon">
                <?php
                if (@Auth::user()->profile_photo && !empty(@Auth::user()->profile_photo)) {
                    $adminImg = @Auth::user()->profile_photo;
                } else {
                    $adminImg = "";
                }
                ?>
                <img src="{{ url('/images/avatar-1.jpg')  }}" alt="img">
            </div>
            <div class="user-name">
                <h5>{{ @Auth::user()->name }}</h5>
            </div>
        </div>
        <ul class="list-unstyled nav">
            <li class="nav-item"><span class="menu-title text-muted">NAVIGATION</span></li>
            <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fal fa-home-alt"></i> Dashboard</a></li>

             

            <!-- Customer Manager-->
            <?php if (Auth::user()->can('admin.customers.list') && Auth::user()->can('admin.add.customer')) { ?>   
                <li class="nav-item <?php echo (in_array(\Route::current()->getName(), array('admin.customers.list', 'admin.add.customer', 'admin.edit.customer', 'admin.view.customer', 'admin.softdelete.customers', 'admin.softdelete.view.customer'))) ? 'active' : "" ?>">
                    <a href="{{route('admin.customers.list')}}" class="nav-link"><i class="fa fa-users"></i> Manage Doctor's</a>
                </li>
            <?php } else if (Auth::user() && !auth()->user()->roles->isEmpty() && Auth::user()->roles[0]->id == 1) { ?>
                <li class="nav-item <?php echo (in_array(\Route::current()->getName(), array('admin.customers.list', 'admin.add.customer', 'admin.edit.customer', 'admin.view.customer', 'admin.softdelete.customers', 'admin.softdelete.view.customer'))) ? 'active' : "" ?>">
                    <a href="{{route('admin.customers.list')}}" class="nav-link"><i class="fa fa-users"></i> Manage Doctor's</a>
                </li>
            <?php } ?>


             <!-- Customer Manager-->
             <?php if (Auth::user()->can('admin.cancers.list') && Auth::user()->can('admin.add.cancer')) { ?>   
                <li class="nav-item <?php echo (in_array(\Route::current()->getName(), array('admin.cancers.list', 'admin.add.cancer', 'admin.edit.cancer', 'admin.view.cancer', 'admin.softdelete.cancer', 'admin.softdelete.view.cancer'))) ? 'active' : "" ?>">
                    <a href="{{route('admin.cancers.list')}}" class="nav-link"><i class="fa fa-users"></i> Manage Cancer Type</a>
                </li>
            <?php } else if (Auth::user() && !auth()->user()->roles->isEmpty() && Auth::user()->roles[0]->id == 1) { ?>
                <li class="nav-item <?php echo (in_array(\Route::current()->getName(), array('admin.cancers.list', 'admin.add.cancer', 'admin.edit.cancer', 'admin.view.cancer', 'admin.softdelete.cancer', 'admin.softdelete.view.cancer'))) ? 'active' : "" ?>">
                    <a href="{{route('admin.cancers.list')}}" class="nav-link"><i class="fa fa-users"></i> Manage Cancer Type</a>
                </li>
            <?php } ?>


            <!-- Email Manager-->
            <?php if (Auth::user()->can('admin.emails') && Auth::user()->can('admin.add.email')) { ?>   
                <li class="nav-item <?php echo (in_array(\Route::current()->getName(), array('admin.emails', 'admin.add.email', 'admin.edit.email', 'admin.view.email', 'admin.delete.email', 'admin.email.status'))) ? 'active' : "" ?>">
                    <a href="{{route('admin.emails')}}" class="nav-link"><i class="fa fa-envelope"></i> Manage Email Templates</a>
                </li>
            <?php } else if (Auth::user() && !auth()->user()->roles->isEmpty() && Auth::user()->roles[0]->id == 1) { ?>
                <li class="nav-item <?php echo (in_array(\Route::current()->getName(), array('admin.emails', 'admin.add.email', 'admin.edit.email', 'admin.view.email', 'admin.delete.email', 'admin.email.status'))) ? 'active' : "" ?>">
                    <a href="{{route('admin.emails')}}" class="nav-link"><i class="fa fa-envelope"></i> Manage Email Templates</a>
                </li>
            <?php } ?> 

            <!-- Enquiry Manager-->
            <?php if (Auth::user()->can('admin.user.enquiries') && Auth::user()->can('admin.view.user.enquiry')) { ?>   
                <li class="nav-item <?php echo (in_array(\Route::current()->getName(), array('admin.user.enquiries', 'admin.view.user.enquiry'))) ? 'active' : "" ?>">
                    <a href="{{route('admin.user.enquiries')}}" class="nav-link"><i class="fa fa-file"></i> Manage Inquiries</a>
                </li>
            <?php } else if (Auth::user() && !auth()->user()->roles->isEmpty() && Auth::user()->roles[0]->id == 1) { ?>
                <li class="nav-item <?php echo (in_array(\Route::current()->getName(), array('admin.user.enquiries', 'admin.view.user.enquiry'))) ? 'active' : "" ?>">
                    <a href="{{route('admin.user.enquiries')}}" class="nav-link"><i class="fa fa-file"></i> Manage Inquiries</a>
                </li>
            <?php } ?>   

 



               

            <!-- Administrator Manager-->
            <?php if (Auth::user() && !auth()->user()->roles->isEmpty() && Auth::user()->roles[0]->id == 1) { ?>
                <li class="nav-item <?php echo (in_array(\Route::current()->getName(), array('admin.sub.admins', 'admin.add.sub.admin', 'admin.edit.sub.admin', 'admin.view.sub.admin', 'admin.delete.sub.admin'))) ? 'active' : "" ?>">
                    <a href="{{route('admin.sub.admins')}}" class="nav-link"><i class="fa fa-users"></i> Manager Subadmins </a>
                </li>
            <?php } ?>

        </ul>
    </div>
</div>