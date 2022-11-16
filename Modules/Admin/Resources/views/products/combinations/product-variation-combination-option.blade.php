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
                <h1 class="h3 m-0">Products Variation List</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('products') }}">Product Manager</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/admin/edit-product',[$product->slug]) }}">Product Details</a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Variations</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
    <div class="alert alert-success" role="alert">
        <p>{{ $message }}</p>
    </div>
    @endif
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">Product Title: {{ $product->title }}</div>
        </div>
        <div class="card">
            <div class="card-header">Product Variations <b>(Please add at least one variation option before combination add)</b>
                <a href="javascript:void(0);" class="add_veriable ml-2 float-right" data-id="{{ base64_encode($id) }}">
                    <span class="badge badge-primary float-right">Add Variation</span>
                </a>
                <a class="float-right" href="{{ url('/admin/edit-product',[$product->slug]) }}">
                    <span class="badge badge-primary float-right">Continue</span>
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Product Variant -->
            <div class="col-sm-12 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <table id="dataTable1" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" class="sr-no">#</th>
                                        <th scope="col">Variation Name</th>
                                        <th scope="col">Create At</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php  $i=1; @endphp
                                    @foreach($variables as $variable)
                                    <tr>
                                        <th scope="row" class="sr-no"> {{$i++}}</th>
                                        <td>{{ $variable->variant_name }}</td>
                                        <td>
                                            {{ date_format($variable->created_at,"d M Y")}}
                                        </td>
                                        <th scope="col">
                                            @if($variable->status == 1) 
                                            <a onclick="" href="javascript:void(0);" class="actions status_update" data-status="0" data-slug="{{ $variable->id }}">
                                                <span class="badge badge-success">Active</span>
                                            </a> 
                                            @else 
                                            <a  onclick="" href="javascript:void(0);" class="actions status_update" data-status="1" data-slug="{{ $variable->id }}">
                                                <span class="badge badge-danger">Inactive</span>
                                            </a> 
                                            @endif 
                                        </th>
                                        <td class="action">
                                            <a href="{{url('admin/com-delete-product-variation', [$variable->id,$variable->product_id])}}" onclick="return confirm('Are you sure you want to delete this variation?');"  class="actions" data-id="{{ $variable->id }}" data-token="{{ csrf_token() }}">
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

            <!-- Variation Option -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <b>Variation Option</b>
                        <a href="javascript:void(0);" class="add_variable_option" data-id="{{ base64_encode($id) }}">
                            <span class="badge badge-primary float-right">Add Variation Option</span>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <div class="box bg-white">
                            <div class="box-row">
                                <div class="box-content">
                                    <table id="dataTable2" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">Variable Name</th>
                                                <th scope="col">Option Name</th>
                                                <th scope="col">Created At</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($variables as $variable) {
                                                $options = Helper::getVeriableOption($variable->id);
                                                foreach ($options as $option) {
                                                    ?>
                                                    <tr>
                                                        <td>{{ $variable->variant_name }}</td>
                                                        <td>{{ $option->option_name }}</td>
                                                        <td scope="col">
                                                            {{ date_format($option->created_at,"d M Y")}}
                                                        </td>
                                                        <td class="action">
                                                            <a href="{{ url('admin/com-delete-option', [$option->id,$variable->id,$variable->product_id]) }}" onclick="return confirm('Are you sure you want to delete this variation option?');"  class="actions" data-id="{{ $variable->id }}" data-token="{{ csrf_token() }}">
                                                                <button title="Delete" type="button" class="icon-btn delete"><i class="fal fa-times"></i></button>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Combination -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <b>Product Combinations</b>
                        <a href="javascript:void(0);" class="add_pro_combination" data-id="{{ base64_encode($id) }}">
                            <span class="badge badge-primary float-right">Add Combination</span>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <div class="box bg-white">
                            <div class="box-row">
                                <div class="box-content">
                                    <table id="dataTable3" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="sr-no">#</th>
                                                <th scope="col">Combination</th>
                                                <?php
                                                if (@$product->getVariableCom && count($product->getVariableCom) > 0) {
                                                    foreach (@$product->getVariableCom as $gval) {
                                                        if (!$gval->getOption->isEmpty()) {
                                                            ?>
                                                            <th scope="col">{{ $gval->variant_name }}</th>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                                <th scope="col">Price</th>
                                                <th scope="col">Sale Price</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Created At</th>
                                                <th scope="col" class="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php  $i=1; @endphp
                                            @foreach($combinations as $combination)
                                            <tr>
                                                <th scope="row" class="sr-no"> {{$i++}}</th>
                                                <td>
                                                    {{ $combination->product_combination }}
                                                </td>
                                                <?php
                                                $countArr = 0;
                                                $arr = json_decode($combination->product_combination_names); //explode("-", $combination->product_combination);
                                                if (@$arr && !empty($arr)) {
                                                    $countArr = count($arr);
                                                }
                                                $vriCount = 0;
                                                if (@$product->getVariableCom && count($product->getVariableCom) > 0) {
                                                    $vriCount = 0;
                                                    foreach (@$product->getVariableCom as $gval) {
                                                        if (!$gval->getOption->isEmpty()) {
                                                            $vriCount += 1;
                                                        }
                                                    }
                                                }
                                                $loop = 0;
                                                if ($countArr != $vriCount) {
                                                    $loop = $vriCount - $countArr;
                                                }
                                                if (@$arr) {
                                                    foreach (@$arr as $arrEach) {
                                                        ?>
                                                        <td>{{ ucwords($arrEach) }}</td>
                                                        <?php
                                                    }
                                                }
                                                if ($loop > 0) {
                                                    for ($i = 1; $i <= $loop; $i++) {
                                                        echo '<td>N/A</td>';
                                                    }
                                                }
                                                ?>
                                                <td>
                                                    <?php echo $current . $combination->price; ?>
                                                </td>
                                                <td>
                                                    <?php echo ($combination->sale_price) ? $current . $combination->sale_price : $current . "0.00"; ?>
                                                </td>

                                                <td>
                                                    {{ $combination->quantity }}
                                                </td>
                                                <td>
                                                    {{ date_format($combination->created_at,"d M Y")}}
                                                </td>
                                                <td class="action">
                                                    <a class="icon-btn edit edit_combilation_cls" href="javascript:void(0);" data-id="{{ base64_encode($combination->id) }}">
                                                        <button type="button" itle="Edit" class="icon-btn edit"><i class="fal fa-edit"></i></button>
                                                    </a>
                                                    <a href="{{route('admin.delete-combination', [$combination->id,@$combination->productDetails->id])}}" onclick="return confirm('Are you sure you want to delete this combination?');"  class="actions" data-id="" data-token="{{ csrf_token() }}">
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
    </div>
</div>
@endsection
@section('js')
<style type="text/css">
    .fee_error{
        font-size: 14px;
        color: red;
        margin-top: 5px;
        display: block;
    }
</style>
<div class="fa-modal my-modal combinationpopup" style="width:50%;">
    <div class="modal-wrap">
        <div class="fa-modal__close-btn"></div>
        <div class="compare-block combination_data_app">

        </div>
    </div>    
</div>

<script type="text/javascript">
    $(function () {
        $("#dataTable1").DataTable({
            'columnDefs': [{
                    'targets': [3, 4],
                    'orderable': false
                }]
        });

        $("#dataTable2").DataTable({
            'columnDefs': [{
                    'targets': [3],
                    'orderable': false
                }]
        });

        $("#dataTable31").DataTable({
            'columnDefs': [{
                    'targets': [7],
                    'orderable': false
                }]
        });
    });
    jQuery(document).ready(function () {
        /*
         * Variable Script Start 
         */

        /*
         * Update Customer Status 
         */
        jQuery(document).on("click", ".status_update", function () {
            var status = jQuery(this).attr("data-status");
            var slug = jQuery(this).attr("data-slug");
            var this_id = jQuery(this);
            jQuery.ajax({
                url: '{{ route("com-variation-status") }}',
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


        $modalcombinationpopup = jQuery('.combinationpopup').faModal();
        jQuery(".add_veriable").on("click", function () {
            var dataID = jQuery(this).attr("data-id");
            jQuery.ajax({
                url: '{{ route("com-add-variable-product") }}',
                type: "POST",
                data: {_token: '{{ csrf_token() }}', id: dataID},
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    $modalcombinationpopup.faModal('show');
                    if (response.status == "200") {
                        jQuery('.combination_data_app').html(response.msg);
                        jQuery("#info_add_variant_name").focus();
                        jQuery("#info_add_variant_name").select2({
                            placeholder: "Please enter variant name and press keyboard enter button",
                            tags: true
                        });
                    } else {
                        swal("Error deleting!", response.msg, "error");
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
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

        jQuery(".combination_data_app").on("submit", "#frm_add_vari_cls", function (e) {
            e.preventDefault();
            var data = jQuery("#frm_add_vari_cls").serialize();
            jQuery.ajax({
                url: '{{ route("com-store-variable-product") }}',
                type: "POST",
                data: data,
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    jQuery(".fee_error").hide();

                    if (response.status == "200") {
                        $modalcombinationpopup.faModal('hide');
                        swal("Done!", response.msg, "success");

                        setTimeout(function () {
                            swal.close();
                        }, 2000);
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else if (response.status == "501") {
                        jQuery.each(response.errors, function (i, file) {
                            jQuery('#info_v_' + i).after('<span class="fee_error">' + file + '</span>');
                        });
                    } else if (response.status == "201") {
                        swal("Error deleting!", response.msg, "error");
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
                    } else {
                        swal("Error deleting!", response.msg, "error");
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
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

        jQuery(".add_variable_option").on("click", function () {
            var dataID = jQuery(this).attr("data-id");
            jQuery.ajax({
                url: '{{ route("com-add-variable-option") }}',
                type: "POST",
                data: {_token: '{{ csrf_token() }}', id: dataID},
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    $modalcombinationpopup.faModal('show');
                    if (response.status == "200") {
                        jQuery('.combination_data_app').html(response.msg);
                        jQuery("#info_op_option_name").focus();
                        jQuery("#info_op_option_name").select2({
                            placeholder: "Please enter variant option and press keyboard enter button",
                            tags: true
                        });
                    } else {
                        swal("Error deleting!", response.msg, "error");
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
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

        jQuery(".combinationpopup").on("submit", "#frm_variable_option", function (e) {
            e.preventDefault();
            var data = jQuery("#frm_variable_option").serialize();
            jQuery.ajax({
                url: '{{ route("com-store-product-option") }}',
                type: "POST",
                data: data,
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    jQuery(".fee_error").hide();

                    if (response.status == "200") {
                        $modalcombinationpopup.faModal('hide');
                        swal("Done!", response.msg, "success");

                        setTimeout(function () {
                            swal.close();
                        }, 2000);
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else if (response.status == "501") {
                        jQuery.each(response.errors, function (i, file) {
                            jQuery('#info_o_' + i).after('<span class="fee_error">' + file + '</span>');
                        });
                    } else if (response.status == "201") {
                        swal("Error deleting!", response.msg, "error");
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
                    } else {
                        swal("Error deleting!", response.msg, "error");
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
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

        jQuery(".add_pro_combination").on("click", function () {
            var dataID = jQuery(this).attr("data-id");
            jQuery.ajax({
                url: '{{ route("com-add-product-combination") }}',
                type: "POST",
                data: {_token: '{{ csrf_token() }}', id: dataID},
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    $modalcombinationpopup.faModal('show');
                    if (response.status == "200") {
                        jQuery('.combination_data_app').html(response.msg);
                        jQuery("#com_price").focus();
                    } else {
                        swal("Error deleting!", response.msg, "error");
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
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

        jQuery(".combinationpopup").on("submit", "#form_add_combination", function (e) {
            e.preventDefault();
            var data = jQuery("#form_add_combination").serialize();
            jQuery.ajax({
                url: '{{ route("com-store-product-combination") }}',
                type: "POST",
                data: data,
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    jQuery(".fee_error").hide();
                    if (response.status == "200") {
                        $modalcombinationpopup.faModal('hide');
                        swal("Done!", response.msg, "success");

                        setTimeout(function () {
                            swal.close();
                        }, 2000);
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else if (response.status == "501") {
                        jQuery.each(response.errors, function (i, file) {
                            jQuery('#com_' + i).after('<span class="fee_error">' + file + '</span>');
                        });
                    } else if (response.status == "201") {
                        jQuery(".com_product_combination_already").html('<span class="fee_error">' + response.msg + '</span>');
                    } else {
                        swal("Error deleting!", response.msg, "error");
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
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

        //CombinationUpdate
        jQuery(".edit_combilation_cls").on("click", function () {
            var dataID = jQuery(this).attr("data-id");
            jQuery.ajax({
                url: '{{ route("com-edit-product-combination") }}',
                type: "POST",
                data: {_token: '{{ csrf_token() }}', id: dataID},
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    $modalcombinationpopup.faModal('show');
                    if (response.status == "200") {
                        jQuery('.combination_data_app').html(response.msg);
                        jQuery("#info_com_price").focus();
                    } else {
                        swal("Error deleting!", response.msg, "error");
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
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

        jQuery(".combinationpopup").on("submit", "#combination_update", function (e) {
            e.preventDefault();
            var data = jQuery("#combination_update").serialize();
            jQuery.ajax({
                url: '{{ route("com-update-product-combination") }}',
                type: "POST",
                data: data,
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    jQuery(".fee_error").hide();
                    if (response.status == "200") {
                        $modalcombinationpopup.faModal('hide');

                        swal("Done!", response.msg, "success");

                        setTimeout(function () {
                            swal.close();
                        }, 2000);
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else if (response.status == "501") {
                        jQuery.each(response.errors, function (i, file) {
                            jQuery('#info_com_' + i).after('<span class="fee_error">' + file + '</span>');
                        });
                    } else {
                        swal("Error deleting!", response.msg, "error");
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
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
        /*
         * Variable Script End 
         */
    });
</script>
@endsection

