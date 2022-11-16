@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Doctor Details</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.customers.list')}}">Doctor Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Doctor Details</li>
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
                                        $img = $user->profile_photo;
                                    } else {
                                        $img = '/images/avatar-2.jpg';
                                    }
                                    ?>
                                    <tr>
                                        <th> Profile Picture:</th>
                                        <td><img src="{{ url('/images/'.$img)  }}"  height="100" width="100" alt="img" /></td>
                                    </tr>
                                    <tr>
                                        <th> Full Name:</th>
                                        <td>{{$user['name']}}</td>
                                    </tr>
                                    <tr>
                                        <th> Email Address:</th>
                                        <td>{{$user['email']}}</td>
                                    </tr>
                                   
                                 
                                 
                                    
                                    <tr>
                                        <th> Status:</th>
                                        <td>
                                            <?php
                                            if ($user['status'] == '1') {
                                                echo '<span class="badge badge-success">Active</span>';
                                            } else {
                                                echo '<span class="badge badge-danger">Inactive</span>';
                                            }
                                            ?>
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

