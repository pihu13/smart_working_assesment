@extends('admin::layouts.master')
@section('admin::content')
<?php
$currency = Helper::defaultCurrency();
if (isset($currency) && !empty($currency)) {
    $currencySymbal = Helper::currencySymbal($currency);
    if (isset($currencySymbal) && !empty($currencySymbal)) {
        $current = $currencySymbal;
    } else {
        $current = env('DEFAULT_CURRENCY');
    }
} else {
    $current = env('DEFAULT_CURRENCY');
}
?>
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Offer Details</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.category.offers')}}">Offer Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Offer Details</li>
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
                                        <th> Category Name:</th>
                                        <td>
                                            <b>
                                                {{ (@$offer->categoryDetails->name)?@$offer->categoryDetails->name:"N/A" }}
                                            </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Title:</th>
                                        <td>
                                            {{ @$offer['title'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Banner Type:</th>
                                        <td>
                                            @if(@$offer['banner_type'] == '2') 
                                            <span class="badge badge-success">Small</span>
                                            @else 
                                            <span class="badge badge-success">Full</span>
                                            @endif 
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Banner Image:</th>
                                        <?php
                                        if (@$offer['banner_img']) {
                                            $img = @$offer['banner_img'];
                                        } else {
                                            $img = "uploads/dummy.png";
                                        }
                                        ?>
                                        <td>
                                            <img src="{{ asset('/storage/'.$img) }}" id="small_banner_img_cls" width="100" height="100">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th> Offer Type:</th>
                                        <td>
                                            @if(@$offer->offer_type == 1) 
                                            <span class="badge badge-success">Fixed</span>
                                            @else
                                            <span class="badge badge-success">Percentage</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Price Discount:</th>
                                        <td>
                                            {!! @$current.@$offer['price_discount'] !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Valid From Date:</th>
                                        <td>
                                            {{ date("d M Y h:i:s",strtotime(@$offer['valid_from_date']))}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Valid to Date:</th>
                                        <td>
                                            {{ date("d M Y h:i:s",strtotime(@$offer['valid_to_date']))}}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <th> Created At:</th>
                                        <td>
                                            {{ date("d M Y h:i:s",strtotime(@$offer['created_at']))}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Status:</th>
                                        <td>
                                            @if(@$offer['status'] == 1) 
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

