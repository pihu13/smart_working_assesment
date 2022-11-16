@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Store Details</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.shops.list')}}">Store Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $shop->store_name }}</li>
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
                                    <tr>
                                        <th> Name:</th>
                                        <td>
                                            {{ @$shop->store_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Owner Name:</th>
                                        <td>
                                            {{ @$shop->store_owner_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Logo:</th>
                                        <td>
                                            <img src="{{ asset("/storage/".@$shop->store_logo) }}" height="50" width="50" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Email Address:</th>
                                        <td>
                                            {{ @$shop->store_email }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Contact Number:</th>
                                        <td>
                                            {{ @$shop->store_contact_no }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Country STD Code:</th>
                                        <td>
                                            {{ @$shop->country_std_code }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Country Name:</th>
                                        <td>
                                            {{ @$shop->country_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Country Code:</th>
                                        <td>
                                            {{ @$shop->country_code }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Address:</th>
                                        <td>
                                            {{ @$shop->store_address }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th> Timing:</th>
                                        <td>
                                            <table width="100%">
                                                <tr>
                                                    <th>Week Day Name</th>
                                                    <th>Opening Time</th>
                                                    <th>Closing Time</th>
                                                </tr>
                                                <?php
                                                if (!@$shop->shopTiming->isEmpty()) {
                                                    foreach (@$shop->shopTiming as $infor) {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                @if(@$infor->day_name == "2")
                                                                Tuesday
                                                                @elseif(@$infor->day_name == "3")
                                                                Wednesday
                                                                @elseif(@$infor->day_name == "4")
                                                                Thursday
                                                                @elseif(@$infor->day_name == "5")
                                                                Friday
                                                                @elseif(@$infor->day_name == "6")
                                                                Saturday
                                                                @elseif(@$infor->day_name == "7")
                                                                Sunday
                                                                @else
                                                                Monday
                                                                @endif
                                                            </td>
                                                            <td>{{ @$infor->start_time }}</td>
                                                            <td>{{ @$infor->end_time }}</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th> Description:</th>
                                        <td>
                                            {!! @$shop->discription !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Registered On:</th>
                                        <td>
                                            {{ date_format(@$shop->created_at,"d M Y") }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Status:</th>
                                        <td>
                                            @if(@$shop['status'] == 1) 
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
@section('js')
<script type="text/javascript">
    jQuery(document).ready(function () {

    });
</script>
@endsection

