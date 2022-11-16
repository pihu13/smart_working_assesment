@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Product Order</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Order</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <form class="box bg-white" method="GET" action="{{ route('admin.product.orders') }}" files="true">
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

        @if ($message = Session::get('errors'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error</strong> {{ $message }}
            <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        @endif
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                All Order List
                <a href="{{route('admin.product.delivery.orders')}}">
                    <span class="badge badge-primary float-right mr-2">Delivery Orders</span>
                </a>
                <a href="{{route('admin.product.pickup.orders')}}">
                    <span class="badge badge-primary float-right mr-2">Pick-up Orders</span>
                </a>
                <a href="{{route('admin.product.cancel.orders')}}">
                    <span class="badge badge-primary float-right mr-2">Cancelled Orders</span>
                </a>
                <a href="{{route('admin.product.completed.orders')}}">
                    <span class="badge badge-primary float-right mr-2">Completed Orders</span>
                </a>
                <a href="{{route('admin.product.return.orders')}}">
                    <span class="badge badge-primary float-right mr-2">Return Orders</span>
                </a>
                <a href="{{route('admin.product.order.transactions')}}">
                    <span class="badge badge-primary float-right mr-2">Product Order Transaction</span>
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
                                        <th style="display:none;"></th>
                                        <th scope="col" class="sr-no">#</th>
                                        <th scope="col">Order Number</th>
                                        <th scope="col">User Name</th>
                                        <th scope="col">Payment Mode</th>
                                        <th scope="col">Payment Status</th>
                                        <th scope="col">Order Status</th>
                                        <th scope="col">Delivery Type</th>
                                        <th scope="col">Sub Total</th>
                                        <th scope="col">Delivery Charges</th>
                                        <th scope="col">Tax</th>
                                        <th scope="col">Tip Amount</th>
                                        <th scope="col">Grand Total</th>
                                        <th scope="col">Purchased On</th>
                                        <th scope="col" class="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php  $i=1; @endphp
                                    @foreach($orders as $order)
                                    <tr class="remove_row_{{$order->id}}">
                                        <td style="display:none;"></td>
                                        <th scope="row" class="sr-no"> {{$i++}}</th>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->userDetails->name }}</td>
                                        <?php
                                        $method = "";
                                        if (@$order->payment_mode == "CARD") {
                                            $method = "Card";
                                        } else if (@$order->payment_mode == "COD") {
                                            $method = "COD";
                                        } else {
                                            $method = "N/A";
                                        }
                                        ?>
                                        <td>
                                            <b>
                                                {!! (@$method)?'<span class="badge badge-secondary">' . strtoupper(@$method) . '</span>':"N/A" !!}
                                            </b>
                                        </td>
                                        <td>
                                            <?php
                                            if (@$order->orderPaymentStatus->payment_status && $order->orderPaymentStatus->payment_status == "succeeded") {
                                                echo '<span class="badge badge-success">' . strtoupper($order->orderPaymentStatus->payment_status) . '</span>';
                                            } else {
                                                echo '<span class="badge badge-danger">N/A</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (@$order->order_status == "2") {
                                                echo '<span class="badge badge-primary">' . strtoupper('Under Transition') . '</span>';
                                            } else if (@$order->order_status == "3") {
                                                echo '<span class="badge badge-warning">' . strtoupper('On Hold') . '</span>';
                                            } else if (@$order->order_status == "4") {
                                                echo '<span class="badge badge-success">' . strtoupper('Delivered') . '</span>';
                                            } else if (@$order->order_status == "5") {
                                                echo '<span class="badge badge-danger">' . strtoupper('Cancelled') . '</span>';
                                            } else if (@$order->order_status == "6") {
                                                echo '<span class="badge badge-warning">' . strtoupper('Return') . '</span>';
                                            } else if (@$order->order_status == "7") {
                                                echo '<span class="badge badge-danger">' . strtoupper('Failed') . '</span>';
                                            } else {
                                                echo '<span class="badge badge-secondary">' . strtoupper('Pending Payment') . '</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (@$order->delivery_type == "delivery") {
                                                echo '<span class="badge badge-info">' . strtoupper($order->delivery_type) . '</span>';
                                            } else if (@$order->delivery_type == "pick-up") {
                                                echo '<span class="badge badge-secondary">' . strtoupper($order->delivery_type) . '</span>';
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            {{ (@$order->sub_total)?@$order->currency_symbol.$order->sub_total:"N/A" }}
                                        </td>

                                        <td>
                                            {{ (@$order->delivery_charges_amount)?@$order->currency_symbol.$order->delivery_charges_amount:@$order->currency_symbol."0" }}
                                        </td>
                                        <td>
                                            {{ (@$order->product_tax)?@$order->currency_symbol.$order->product_tax:@$order->currency_symbol."0" }}
                                        </td>
                                        <td>
                                            {{ (@$order->tip_amount)?@$order->currency_symbol.$order->tip_amount:@$order->currency_symbol."0" }}
                                        </td>
                                        <td>
                                            <b>{{ (@$order->grand_total)?@$order->currency_symbol.$order->grand_total:@$order->currency_symbol."0" }}</b>
                                        </td>
                                        <td>
                                            {{ date_format(@$order->created_at,"d M Y")}}
                                        </td>
                                        <td class="action" style="float: left;">
                                            <a class="icon-btn preview" href="{{ route('admin.view.product.order',[$order->order_number]) }}">		
                                                <button type="button" itle="View" class="icon-btn preview"><i class="fal fa-eye"></i></button>
                                            </a>
                                            <a href="javascript:void(0);" onclick="confirmDelete('<?php echo @$order->id; ?>',<?php echo @$order->id; ?>, '<?php echo @$order->order_number; ?>');"  class="actions" data-slug="{{ @$order->id }}">
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
    function confirmDelete(dataSlug, row_id, order_number) {
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
                url: '{{ route("admin.delete.product.order") }}',
                type: "POST",
                data: {_token: '{{ csrf_token() }}', slug: dataSlug, order_number: order_number},
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
        jQuery("#dataTable").DataTable({
            'columnDefs': [{
                    'targets': [1, 4, 5, 12, 13],
                    'orderable': false
                }],
            stateSave: true
        });
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".reset-frm").on("click", function () {
            window.location.href = '<?php echo route('admin.product.orders'); ?>';
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

