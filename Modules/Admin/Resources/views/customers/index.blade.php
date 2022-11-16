@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Doctor List</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Doctors Manager</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <form class="box bg-white" method="GET" action="{{ route('admin.customers.list') }}" files="true">
                    <div class="box-title pb-0">
                        <h5>Filter</h5>
                    </div>
                    <div class="d-flex flex-wrap align-items-end py-4">
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label>Start Date</label>
                                <div class="input-group">
                                    <input type="text" id="start-date" name="start_date" value="{{ isset($requestData['start_date'])?$requestData['start_date']:"" }}" class="form-control" placeholder="Start Date" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label>End Date</label>
                                <div class="input-group">
                                    <input type="text" id="end-date" name="end_date" value="{{ isset($requestData['end_date'])?$requestData['end_date']:"" }}" class="form-control" placeholder="End Date" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="form-group mb-0">
                                <button type="button" class="btn btn-primary w-100 reset-frm">Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-12">
            @if ($message = Session::get('success'))    
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success</strong> {{ $message }}
                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            @endif

            @if ($message = Session::get('errors'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error</strong> {{ $message }}
                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            @endif
        </div>

        <div class="card">
            <div class="card-header">Doctor List
                <a href="{{ route('admin.add.customer') }}">
                    <span class="badge badge-primary float-right mr-2">Add New Doctor</span>
                </a>
                <a href="{{route('admin.softdelete.customers')}}">
                    <span class="badge badge-primary float-right mr-2">Soft Deleted Doctors List</span>
                </a>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12 mb-4 mt-3" >
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <table id="dataTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="display:none;"></th>
                                        <th scope="col" class="sr-no">#</th>
                                        <th scope="col">Photo</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email Address</th>
                                        
                                       
                                        <th scope="col">Status</th>
                                        <th scope="col" class="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach ($customers as $key => $user)
                                    <?php
                                    $addressArr = [];
                                    if (@$user->userProfile->apartment_unit && !empty($user->userProfile->apartment_unit)) {
                                        $addressArr[] = @$user->userProfile->apartment_unit;
                                    }
                                    if (@$user->userProfile->city && !empty($user->userProfile->city)) {
                                        $addressArr[] = @$user->userProfile->city;
                                    }
                                    if (@$user->userProfile->state && !empty($user->userProfile->state)) {
                                        $addressArr[] = @$user->userProfile->state;
                                    }
                                    if (@$user->userProfile->country && !empty($user->userProfile->country)) {
                                        $addressArr[] = @$user->userProfile->country;
                                    }
                                    if (@$user->userProfile->zip_code && !empty($user->userProfile->zip_code)) {
                                        $addressArr[] = @$user->userProfile->zip_code;
                                    }

                                    if (@$addressArr && count($addressArr) > 0) {
                                        $fullAddress = implode(", ", $addressArr);
                                    } else {
                                        $fullAddress = "N/A";
                                    }
                                    ?>
                                    <tr class="rem_row_{{$user->id}}">
                                        <td style="display:none;"></td>
                                        <th scope="row" class="sr-no">{{ $i }}</th>
                                        <?php
                                        if (!empty($user->profile_photo)) {
                                            $img = $user->profile_photo;
                                        } else {
                                            $img = '/storage/uploads/users/avatar.png';
                                        }
                                        ?>
                                        <td>
                                            <img src="{{ url('/images/'.$img)  }}"  height="50" width="50" alt="img" />
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>

                                    
                                       
                                        <td scope="col">
                                            @if($user->status == 1) 
                                            <a onclick="" href="javascript:void(0);" class="actions status_update" data-status="0" data-slug="{{ $user->slug }}">
                                                <span class="badge badge-success">Active</span>
                                            </a> 
                                            @else 
                                            <a onclick="" href="javascript:void(0);" class="actions status_update" data-status="1" data-slug="{{ $user->slug }}">
                                                <span class="badge badge-danger">Inactive</span>
                                            </a> 
                                            @endif 
                                        </td>
                                        <td class="action" style="float: left;">
                                            <a class="icon-btn preview" href="{{ route('admin.view.customer',[$user->slug]) }}">		
                                                <button type="button" itle="View" class="icon-btn preview"><i class="fal fa-eye"></i></button>
                                            </a>
                                            <a class="icon-btn edit" href="{{ route('admin.edit.customer',[$user->slug]) }}">
                                                <button type="button" itle="Edit" class="icon-btn edit"><i class="fal fa-edit"></i></button>
                                            </a>
                                            <a href="javascript:void(0);" onclick="confirmDelete('<?php echo $user->slug; ?>',<?php echo $user->id; ?>);"  class="actions" data-slug="{{ $user->slug }}">
                                                <button title="Delete" type="button" class="icon-btn delete"><i class="fal fa-times"></i></button>
                                            </a>
                                        </td>
                                    </tr>
                                    @php $i++; @endphp
                                    @endforeach 
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
    function confirmDelete(dataSlug, row_id) {
        var this_id = row_id;
        swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (!isConfirm)
                return;
            jQuery.ajax({
                url: '{{ route("admin.delete.customer") }}',
                type: "POST",
                data: {_token: '{{ csrf_token() }}', slug: dataSlug},
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    if (response.status == "200") {
                        jQuery('.rem_row_' + this_id).remove();
                        swal("Done!", "Row deleted successfully!", "success");
                    } else {
                        swal("Error deleting!", "Please try again", "error");
                    }
                    jQuery(".loading-box").hide();
                },
                error: function (xhr) {
                    swal("Error deleting!", "Please try again", "error");
                    jQuery(".loading-box").hide();
                },
                complete: function () {
                    jQuery(".loading-box").hide();
                }
            });
        });
    }

    jQuery(function () {
        jQuery("#dataTable").DataTable({
            'columnDefs': [{
                    'targets': [1,2,5,6,8],
                    'orderable': false
                }],
            stateSave: true
        });
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        /*
         * Update Customer Status 
         */
        jQuery(document).on("click", ".status_update", function () {
            var status = jQuery(this).attr("data-status");
            var slug = jQuery(this).attr("data-slug");
            var this_id = jQuery(this);
            jQuery.ajax({
                url: '{{ route("admin.customer.status") }}',
                type: "POST",
                data: {_token: '{{ csrf_token() }}', status: status, slug: slug},
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    if (response.status == "200") {
                        jQuery(this_id).attr("data-status", "0");
                        jQuery(this_id).html('<span class="badge badge-success">Active</span>');
                        swal("Done!", "Status update successfully!", "success");
                    } else if (response.status == "201") {
                        jQuery(this_id).attr("data-status", "1");
                        jQuery(this_id).html('<span class="badge badge-danger">Inactive</span>');
                        swal("Done!", "Status update successfully!", "success");
                    } else {
                        swal("Error deleting!", "Please try again", "error");
                    }
                    jQuery(".loading-box").hide();
                },
                error: function (xhr) {
                    swal("Error deleting!", "Please try again", "error");
                    //alert(xhr.statusText + xhr.responseText);
                    jQuery(".loading-box").hide();
                },
                complete: function () {
                    jQuery(".loading-box").hide();
                }
            });
        });

        jQuery(".reset-frm").on("click", function () {
            window.location.href = '<?php echo route('admin.customers.list'); ?>';
        });


        jQuery(document).ready(function () {
            jQuery(function () {
                var dateToday = new Date();
                var dates = jQuery("#start-date, #end-date").datepicker({
                    dateFormat: "yy-mm-dd",
                    defaultDate: "+0w",
                    changeMonth: true,
                    numberOfMonths: 1,
                    maxDate: dateToday,
                    onSelect: function (selectedDate) {
                        var option = this.id == "start-date" ? "minDate" : "maxDate",
                                instance = jQuery(this).data("datepicker"),
                                date = jQuery.datepicker.parseDate(instance.settings.dateFormat || jQuery.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                        dates.not(this).datepicker("option", option, date);
                    }
                });
            });
        });
    });
</script>
@endsection