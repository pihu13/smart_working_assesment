@extends('admin::layouts.master')
@section('admin::content')
<?php 
$currency_symbol = "$";
?>
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Product Cancel/Return Order Transaction</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Product Cancel/Return Order Transaction</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <form class="box bg-white" method="GET" action="{{ route('admin.cancel.return.product.order.transactions') }}" files="true">
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
                        <?php
                        //@$requestData['end_date']&&"sfdf";
                        ?>
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
        <div class="alert alert-success" role="alert">
            <p>{{ $message }}</p>
        </div>
        @endif
    </div>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                Product Cancel/Return Order Transaction List
                <a href="{{route('admin.product.order.transactions')}}">
                    <span class="badge badge-primary float-right mr-2">All Order Transaction</span>
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
                                        <th scope="col" class="sr-no">#</th>
                                        <th scope="col">User Name</th>
                                        <th scope="col">Order Number</th>
                                        <th scope="col">Order Payment Return Type</th>
                                        <th scope="col">Refund Transaction ID</th>
                                        <th scope="col">Transaction ID</th>
                                        <th scope="col">Charge ID</th>
                                        <th scope="col">Sub Total</th>
                                        <th scope="col">Delivery Charges</th>
                                        <th scope="col">Tax</th>
                                        <th scope="col">Tip Amount</th>
                                        <th scope="col">Grand Total</th>
                                        <th scope="col">Payment Status</th>
                                        <th scope="col">Refund On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php  $i=1; @endphp
                                    @foreach($transections as $transection)
                                    <tr>
                                        <th scope="row" class="sr-no"> {{$i++}}</th>
                                        <td>
                                            <a href="{{ route('admin.view.customer',[@$transection->userDetails->slug]) }}" target="_blank">
                                                {{ $transection->userDetails->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.view.product.order',[@$transection->orderDetails->order_number]) }}" target="_blank">
                                                {{ $transection->orderDetails->order_number }}
                                            </a>
                                        </td>
                                        <td align="center">
                                            <?php
                                            if (@$transection->order_payment_return_type == "2") {
                                                $type = "Return";
                                            } else {
                                                $type = "Cancel";
                                            }
                                            ?>
                                            <span class="badge badge-success">
                                                {{ @$type }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                {{ @$transection->refund_id }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                {{ @$transection->refund_balance_transaction_id }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                {{ @$transection->charge_id }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $currency_symbol }}{{ (@$transection->sub_total)?@$transection->sub_total:"0" }}
                                        </td>

                                        <td>
                                            {{ $currency_symbol }}{{ (@$transection->delivery_charges_amount)?@$transection->delivery_charges_amount:"0" }}
                                        </td>
                                        <td>
                                            {{ $currency_symbol }}{{ (@$transection->product_tax)?@$transection->product_tax:"0" }}
                                        </td>
                                        <td>
                                            {{ $currency_symbol }}{{ (@$transection->tip_amount)?@$transection->tip_amount:"0" }}
                                        </td>
                                        <td>
                                            <b>{{ $currency_symbol }}{{ (@$transection->grand_total)?@$transection->grand_total:"0" }}</b>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                {{ strtoupper($transection->refund_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ date_format(@$transection->created_at,"d M Y")}}
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
        $("#dataTable").DataTable();
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".reset-frm").on("click", function () {
            window.location.href = '<?php echo route('admin.cancel.return.product.order.transactions'); ?>';
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

