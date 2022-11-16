@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Soft Delete Order Details</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.product.softdelete.orders')}}">Soft Delete Order Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$order->order_number}}</li>
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

        @if ($message = Session::get('errors'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error</strong> {{ $message }}
            <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        @endif
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
                                        <th> Order Number:</th>
                                        <td>
                                            <b>{{ $order->order_number }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> User Name:</th>
                                        <td>{{ $order->userDetails->name }}</td>
                                    </tr>
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
                                    <tr>
                                        <th> Payment Mode:</th>
                                        <td>
                                            <b>
                                                {!! (@$method)?'<span class="badge badge-secondary">' . strtoupper(@$method) . '</span>':"N/A" !!}
                                            </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Payment Status:</th>
                                        <td>
                                            <b>
                                                <?php
                                                if (@$order->orderPaymentStatus->payment_status && $order->orderPaymentStatus->payment_status == "succeeded") {
                                                    echo '<span class="badge badge-success">' . strtoupper($order->orderPaymentStatus->payment_status) . '</span>';
                                                } else {
                                                    echo '<span class="badge badge-danger">N/A</span>';
                                                }
                                                ?>
                                            </b>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th> Order Transaction ID:</th>
                                        <td>
                                            <b>
                                                <?php
                                                if (@$order->orderPaymentStatus->payment_status && $order->orderPaymentStatus->payment_status == "succeeded") {
                                                    echo '<span class="badge badge-success">' . strtoupper($order->orderPaymentStatus->transection_id) . '</span>';
                                                } else {
                                                    echo '<span class="badge badge-danger">N/A</span>';
                                                }
                                                ?>
                                            </b>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th> Order Status:</th>
                                        <td>
                                            <b>
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
                                            </b>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th> Sub Total:</th>
                                        <td>
                                            <b>{{ @$order->currency_symbol.$order->sub_total }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Delivery Type:</th>
                                        <td>
                                            {!! (@$order->delivery_type)?'<span class="badge badge-success">' . strtoupper($order->delivery_type) . '</span>':"N/A" !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Delivery Charge:</th>
                                        <td>
                                            {{ (@$order->delivery_charges_amount)?$order->currency_symbol.$order->delivery_charges_amount:@$order->currency_symbol."0" }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Tax Amount:</th>
                                        <td>
                                            {{ (@$order->product_tax)?$order->currency_symbol.$order->product_tax:@$order->currency_symbol."0" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tip Amount:</th>
                                        <td>
                                            {{ (@$order->tip_amount)?$order->currency_symbol.$order->tip_amount:@$order->currency_symbol."0" }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th> Grand Total:</th>
                                        <td>
                                            <b>
                                                {{ $order->currency_symbol.$order->grand_total }}
                                            </b>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Special Delivery Instractions:</th>
                                        <td>
                                            {{ (@$order->special_delivery_instractions)?$order->special_delivery_instractions:"N/A" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Preferred Delivery Date:</th>
                                        <td>
                                            {{ (@$order->prefered_delivery_date)?$order->prefered_delivery_date:"N/A" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Preferred Delivery From Time:</th>
                                        <td>
                                            {{ (@$order->prefered_delivery_start_time)?$order->prefered_delivery_start_time:"N/A" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Preferred Delivery To Time:</th>
                                        <td>
                                            {{ (@$order->prefered_delivery_end_time)?$order->prefered_delivery_end_time:"N/A" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Deleted At:</th>
                                        <td>
                                            <span class="badge badge-warning">
                                                {{ date_format(@$order->updated_at,"d M Y")}}
                                            </span>
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

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">Order Shipping Process</div>
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
                                        <th scope="col">Shipping Status</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php  $i=1; @endphp
                                    @foreach($order->orderShippingProduct as $shipping)
                                    <tr>
                                        <th scope="row" class="sr-no"> {{$i++}}</th>
                                        <td>{{ @$shipping->title }}</td>
                                        <td>
                                            <?php
                                            if (@$shipping->shipping_status == "1") {
                                                echo '<span class="badge badge-success">' . @$shipping->title . '</span>';
                                            } else {
                                                if (@$order->order_number && @$shipping->id && @$shipping->product_order_id) {
                                                    $url = route("admin.product.order.shipping.status", [@$order->order_number, @$shipping->id, @$shipping->product_order_id]);
                                                } else {
                                                    $url = "javascript:void(0)";
                                                }
                                                ?>
                                                <a href="{{ @$url }}">
                                                    <span class="badge bg-primary" style="color:#ffffff;">
                                                        Click Here to {{ @$shipping->title }}
                                                    </span>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            @if (@$shipping->shipping_status == "1")
                                            {{ date_format(@$shipping->updated_at,"d M Y h:m:s A")}}
                                            @else
                                            {{ "N/A" }}
                                            @endif
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

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">Order Items</div>
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
                                        <th scope="col">Product Title</th>
                                        <th scope="col">Product Type</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Product Variation</th>
                                        <th scope="col">Product Combination</th>
                                        <th scope="col">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php  $i=1; @endphp
                                    @foreach($order->productOrderItemDetails as $item)
                                    <tr>
                                        <th scope="row" class="sr-no"> {{$i++}}</th>
                                        <td>{{ @$item->productDetails->title }}</td>
                                        <td>
                                            <?php
                                            if (@$item->productDetails->product_type == "2") {
                                                echo '<span class="badge badge-success">Variation Product</span>';
                                            } else {
                                                echo '<span class="badge badge-success">Simple Product</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>
                                            <?php
                                            if (@$item->productDetails->product_type == "2") {
                                                $product_variation = json_decode(@$item->product_variation);
                                                if (@$product_variation && @$item->productDetails->id) {
                                                    echo $product_variation;
                                                }
                                            } else {
                                                echo "NA";
                                            }
                                            ?>
                                        </td>
                                        <td>{{ (@$item->product_combination)?$item->product_combination:"N/A" }}</td>
                                        <td>
                                            <b>{{ $item->currency_symbol.$item->price }}</b>
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

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">Order Address</div>
        </div>
        <div class="row">
            <div class="col-sm-12 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <?php
                            if (@$order->productOrderAddressDetails && !empty($order->productOrderAddressDetails)) {
                                ?>
                                <table id="dataTable" class="table table-striped table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th> Address Type:</th>
                                            <td>
                                                <?php
                                                if (@$order->productOrderAddressDetails->address_type == "2") {
                                                    echo "Work";
                                                } else if (@$order->productOrderAddressDetails->address_type == "2") {
                                                    echo "Home";
                                                } else {
                                                    echo "Other";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th> Full Name:</th>
                                            <td>{{ @$order->productOrderAddressDetails->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <th> Email Address:</th>
                                            <td>{{ @$order->productOrderAddressDetails->email }}</td>
                                        </tr>
                                        <tr>
                                            <th> Phone Number:</th>
                                            <td>{{ @$order->productOrderAddressDetails->country_std_code.@$order->productOrderAddressDetails->phone_number }}</td>
                                        </tr>
                                        <tr>
                                            <th> Country ISD Code:</th>
                                            <td>{{ @$order->productOrderAddressDetails->country_std_code }}</td>
                                        </tr>
                                        <tr>
                                            <th> Country Name:</th>
                                            <td>{{ @$order->productOrderAddressDetails->country_name }}</td>
                                        </tr>
                                        <tr>
                                            <th> Country Code:</th>
                                            <td>{{ @$order->productOrderAddressDetails->country_code }}</td>
                                        </tr>
                                        <tr>
                                            <th> Street Address:</th>
                                            <td>{{ @$order->productOrderAddressDetails->street_address }}</td>
                                        </tr>
                                        <tr>
                                            <th> Apartment/Unit Number:</th>
                                            <td>{{ @$order->productOrderAddressDetails->apartment_unit }}</td>
                                        </tr>
                                        <tr>
                                            <th> City:</th>
                                            <td>{{ @$order->productOrderAddressDetails->city }}</td>
                                        </tr>
                                        <tr>
                                            <th> State:</th>
                                            <td>{{ @$order->productOrderAddressDetails->state }}</td>
                                        </tr>
                                        <tr>
                                            <th> Country:</th>
                                            <td>{{ @$order->productOrderAddressDetails->country }}</td>
                                        </tr>
                                        <tr>
                                            <th> Zip Code:</th>
                                            <td>{{ @$order->productOrderAddressDetails->zip_code }}</td>
                                        </tr>
                                        <tr>
                                            <th> Delivery Instructions:</th>
                                            <td>{{ @$order->productOrderAddressDetails->delivery_instractions }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

