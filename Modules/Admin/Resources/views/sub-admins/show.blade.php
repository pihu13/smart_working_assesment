@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Sub-admin Details</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.sub.admins')}}">Sub-admin Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Subadmin</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <table id="dataTable" class="table table-striped table-bordered table-hover">
                                <tbody>
                                    <?php
                                    if (!empty($user->profile_photo)) {
                                        $img = '/storage/' . $user->profile_photo;
                                    } else {
                                        $img = '/storage/uploads/users/avatar.png';
                                    }
                                    ?>
                                    <tr>
                                        <th> Profile Picture</th>
                                        <td><img src="{{$img}}" height="100" width="100" alt="img" /></td>
                                    </tr>
                                    <tr>
                                        <th> Username:</th>
                                        <td>{{ $user['username'] }}</td>
                                    </tr>
                                    <tr>
                                        <th> First Name:</th>
                                        <td>{{ $user['first_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <th> Last Name:</th>
                                        <td>{{ $user['last_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <th> Email Address:</th>
                                        <td>
                                            <b>
                                                <a href="mailto:{{ $user['email'] }}">
                                                    {{ $user['email'] }}
                                                </a>
                                            </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Contact Number:</th>
                                        <td>
                                            <a href="tel:{{ @$user->country_std_code.@$user->phone_number }}">
                                                {{ @$user->country_std_code.@$user->phone_number }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Country STD Code:</th>
                                        <td>{{ @$user->country_std_code }}</td>
                                    </tr>
                                    <tr>
                                        <th> Country Code:</th>
                                        <td>{{ @$user->country_code }}</td>
                                    </tr>
                                    <tr>
                                        <th> Country Name:</th>
                                        <td>{{ @$user->country_name }}</td>
                                    </tr>

                                    <tr>
                                        <th> Assign Modules</th>
                                        <td>
                                            <?php
                                            $userPrPermitn = [];
                                            if (@$userPr && count($userPr) > 0) {
                                                foreach ($userPr as $key => $userPrEach) {
                                                    if ($key != "default") {
                                                        $userPrPermitn[] = "<b>" . ucwords($key) . "</b>";
                                                    }
                                                }
                                                echo implode(", ", $userPrPermitn);
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th> Registered On:</th>
                                        <td>{{ date_format($user->created_at,"d M Y")}}</td>
                                    </tr>
                                    
                                    <tr>
                                        <th> Status:</th>
                                        <td>
                                            @if($user['status'] == 1) 
                                            <span class="badge badge-success">Active</span>
                                            @else 
                                            <span class="badge badge-danger">Inactive</span>
                                            @endif 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

