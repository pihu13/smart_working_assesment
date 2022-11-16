@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Offer Manager</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Offers</li>
                    </ol>
                </nav>
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
            <strong>Success</strong> {{ $message }}
            <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        @endif
    </div>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">Offer List
                <a href="{{route('admin.add.offer')}}">
                    <span class="badge badge-primary float-right">Add Offer</span>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <table id="dataTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" class="sr-no">#</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Category Name</th>
                                        <th scope="col">Offer Type</th>
                                        <th scope="col">Valid From Date</th>
                                        <th scope="col">Valid to Date</th>
                                        <th scope="col">Banner Type</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($categoryOffers as $value)
                                    <tr class="remove_row_<?php echo @$value->id; ?>">
                                        <th scope="row" class="sr-no"> {{$i++}}</th>
                                        <td>{{ @$value->title }}</td>
                                        <td>
                                            <b>
                                                {{ (@$value->categoryDetails->name)?@$value->categoryDetails->name:"N/A" }}
                                            </b>
                                        </td>
                                        <td>
                                            <b>
                                                @if(@$value->offer_type == 1) 
                                                {{ "Fixed" }}
                                                @else 
                                                {{ "Percentage" }}
                                                @endif
                                            </b> 
                                        </td>
                                        <td>
                                            {{ date("d M Y h:i:s",strtotime(@$value->valid_from_date))}}
                                        </td>
                                        <td>
                                            {{ date("d M Y h:i:s",strtotime(@$value->valid_to_date))}}
                                        </td>
                                        <td>
                                            <b>
                                                @if(@$value->banner_type == '2') 
                                                {{ "Small" }}
                                                @else 
                                                {{ "Full" }}
                                                @endif
                                            </b> 
                                        </td>
                                        <td>
                                            {{ date_format(@$value->created_at,"d M Y h:i:s") }}
                                        </td>
                                        <td scope="col">
                                            @if(@$value->status == 1) 
                                            <a href="javascript:void(0);" class="actions status_update" data-status="0" data-slug="{{ @$value->id }}">
                                                <span class="badge badge-success">Active</span>
                                            </a> 
                                            @else 
                                            <a href="javascript:void(0);" class="actions status_update" data-status="1" data-slug="{{ @$value->id }}">
                                                <span class="badge badge-danger">Inactive</span>
                                            </a> 
                                            @endif 
                                        </td>
                                        <td class="action" style="float: left;">
                                            <a class="icon-btn preview" href="{{ route('admin.view.offer',[@$value->id]) }}">		
                                                <button type="button" itle="View" class="icon-btn preview"><i class="fal fa-eye"></i></button>
                                            </a>
                                            <a class="icon-btn edit" href="{{ route('admin.edit.offer',[@$value->id]) }}">
                                                <button type="button" itle="Edit" class="icon-btn edit"><i class="fal fa-edit"></i></button>
                                            </a>
                                            <a href="javascript:void(0);" onclick="confirmDelete('<?php echo @$value->id; ?>',<?php echo @$value->id; ?>);"  class="actions" data-slug="{{ @$value->id }}">
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
@endsection
@section('js')
<script type="text/javascript">
    $(function () {
        $("#dataTable").DataTable({
            'columnDefs': [{
                    'targets': [0, 4, 5, 6, 7],
                    'orderable': false
                }]
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
                url: '{{ route("admin.delete.offer") }}',
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
                url: '{{ route("admin.offer.status") }}',
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
                        swal("Done!", "Status updated successfully!", "success");
                    } else if (response.status == "201") {
                        jQuery(this_id).attr("data-status", "1");
                        jQuery(this_id).html('<span class="badge badge-danger">Inactive</span>');
                        swal("Done!", "Status updated successfully!", "success");
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
@endsection

