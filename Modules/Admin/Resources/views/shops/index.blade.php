@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Store List</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Store Manager</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <form class="box bg-white" method="GET" action="{{ route('admin.shops.list') }}" files="true">
                    <div class="box-title pb-0">
                        <h5>Filter</h5>
                    </div>
                    <div class="d-flex flex-wrap align-items-end py-4">
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label>Start Date</label>
                                <div class="input-group">
                                    <input type="text" id="start-date" name="start_date" value="{{ (@$requestData['start_date'])?$requestData['start_date']:"" }}" class="form-control" placeholder="Start Date" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label>End Date</label>
                                <div class="input-group">
                                    <input type="text" id="end-date" name="end_date" value="{{ (@$requestData['end_date'])?$requestData['end_date']:"" }}" class="form-control" placeholder="End Date" required="required">
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
    </div>

    <div class="col-sm-12">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success</strong> {{ $message }}
            <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        @endif
        @if ($message = Session::get('errors_catch'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error</strong> {{ $message }}
            <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        @endif
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">Store List
                <a href="{{route('admin.add.shop')}}">
                    <span class="badge badge-primary float-right">Add Store</span>
                </a>
                <a href="javascript:void(0);" id="exportData">
                    <span class="badge badge-primary float-right mr-2">Export Stores</span>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content table-responsive">
                            <table id="dataTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="display:none;"></th>
                                        <th scope="col" class="sr-no">#</th>
                                        <th scope="col">Logo</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Owner Name</th>
                                        <th scope="col">Contact Number</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Timings</th>
                                        <th scope="col">Registered On</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php  $i=1; @endphp
                                    @foreach($shops as $shop)
                                    <tr class="remove_row_<?php echo $shop->id; ?>">
                                        <td style="display:none;"></td>
                                        <th scope="row" class="sr-no"> {{$i++}}</th>
                                        <td>
                                            <?php
                                            if (@$shop->store_logo) {
                                                $img = @$shop->store_logo;
                                            } else {
                                                $img = "uploads/dummy.png";
                                            }
                                            ?>
                                            <img src="{{ asset('/storage/'.$img) }}" width="50" height="50">
                                        </td>
                                        <td>{{$shop->store_name }}</td>
                                        <td>
                                            {{$shop->store_owner_name }}
                                        </td>

                                        <td>
                                            <a href="tel:{{ @$shop->country_std_code.@$shop->store_contact_no }}">
                                                <?php echo @$shop->country_std_code . " " . @$shop->phoneNumber(@$shop->store_contact_no); ?>
                                            </a>
                                        </td>
                                        <td>
                                            {{$shop->store_address }}
                                        </td>
                                        <td class="timeing-details">
                                            <div class="timing-details-list">
                                                <ul>
                                                    <li class="titles">
                                                        <span>Day</span>
                                                        <span>Opening</span>
                                                        <span>Closing</span>
                                                    </li>
                                                    <?php
                                                    if (!@$shop->shopTiming->isEmpty()) {
                                                        foreach (@$shop->shopTiming as $infor) {
                                                            ?>
                                                            <li>
                                                                <span class="day-val">
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
                                                                </span>
                                                                <span class="open-val">{{ @$infor->start_time }}</span>
                                                                <span class="close-val">{{ @$infor->end_time }}</span>
                                                            </li>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                            <li>
                                                                <p class="d-block text-center w-100">No store timing available</p>
                                                            </li>
                                                        <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            {{ date_format($shop->created_at,"d M Y") }}
                                        </td>
                                        <th scope="col">
                                            @if($shop->status == 1) 
                                            <a href="javascript:void(0);" class="actions status_update" data-status="0" data-slug="{{ $shop->slug }}">
                                                <span class="badge badge-success">Active</span>
                                            </a> 
                                            @else 
                                            <a href="javascript:void(0);" class="actions status_update" data-status="1" data-slug="{{ $shop->slug }}">
                                                <span class="badge badge-danger">Inactive</span>
                                            </a> 
                                            @endif 
                                        </th>
                                        <td class="action" style="float:left;">
                                            <a class="icon-btn preview" href="{{ route('admin.view.shop',[$shop->slug]) }}">		
                                                <button type="button" itle="View" class="icon-btn preview"><i class="fal fa-eye"></i></button>
                                            </a>
                                            <a class="icon-btn edit" href="{{ route('admin.edit.shop',[$shop->slug]) }}">
                                                <button type="button" itle="Edit" class="icon-btn edit"><i class="fal fa-edit"></i></button>
                                            </a>
                                            <a href="javascript:void(0);" onclick="confirmDelete('<?php echo $shop->slug; ?>',<?php echo $shop->id; ?>);"  class="actions" data-slug="{{ $shop->slug }}">
                                                <button title="Delete" type="button" class="icon-btn delete"><i class="fal fa-times"></i></button>
                                            </a>
                                        </td>
                                    </tr>
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
<form method="get" id="submitExport" action="{{ route("admin.export.stores") }}">
    <input type="hidden" id="export_start_date" name="export_start_date" value="">
    <input type="hidden" id="export_end_date" name="export_end_date" value="">
</form>
@endsection
@section('js')
<style type="text/css">
    .timeing-details{
        min-width: 400px;
    }
    .timing-details-list ul{
        padding: 0;
        margin: 0;
    }
    .timing-details-list ul li{
        display: flex;
        max-width: 100%;
        flex-wrap: wrap;
        margin-left: -5px;
        margin-right: -5px;
        border-bottom: 1px solid #dee2e6;
    }
    .timing-details-list ul li.titles{
        border-bottom: 1px solid #b3b3b3;
    }
    .timing-details-list ul li:last-child{
        border-bottom-color: transparent;
    }
    .timing-details-list ul li span{
        flex: 0 0 33.33333%;
        max-width: 33.33333%;
        padding: 3px 5px;
        font-size: 12px;
        line-height: 12px;
    }
    .timing-details-list .titles span{
        font-weight: bold
    }
</style>
<script type="text/javascript">
    jQuery(function () {
        jQuery("#dataTable").DataTable({
            'columnDefs': [{
                    'targets': [1, 2, 5, 7, 9,10],
                    'orderable': false
                }],
            stateSave: true
        });

        jQuery("#exportData").on("click", function () {
            var start_date = jQuery("#start-date").val();
            var end_date = jQuery("#end-date").val();
            jQuery("#export_start_date").val(start_date);
            jQuery("#export_end_date").val(end_date);
            jQuery("#submitExport").submit();
        });
    });

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
                url: '{{ route("admin.delete.shop") }}',
                type: "POST",
                data: {_token: '{{ csrf_token() }}', slug: dataSlug},
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    if (response.status == "200") {
                        jQuery('.remove_row_' + this_id).remove();
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

    jQuery(document).ready(function () {
        /*
         * Update Customer Status 
         */
        jQuery(document).on("click", ".status_update", function () {
            var status = jQuery(this).attr("data-status");
            var slug = jQuery(this).attr("data-slug");
            var this_id = jQuery(this);
            jQuery.ajax({
                url: '{{ route("admin.shop.status") }}',
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
    });
</script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".reset-frm").on("click", function () {
            window.location.href = '<?php echo route('admin.shops.list'); ?>';
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

