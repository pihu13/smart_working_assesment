@extends('admin::layouts.master')
@section('admin::content')
<?php
$current = env('DEFAULT_CURRENCY');
?>
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Soft Delete Product Details</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.softdelete.products') }}">Soft Delete Product Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Soft Delete Product Details</li>
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
                                        <th> Store Name:</th>
                                        <td>
                                            {{ (@$product->shopDetails->store_name)?@$product->shopDetails->store_name:"N/A" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Product Type:</th>
                                        <td>
                                            <?php
                                            if ($product['product_type'] == "2") {
                                                echo "<b>Variable</b>";
                                            } else {
                                                echo "<b>Simple</b>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Product Title:</th>
                                        <td>{{$product['title']}}</td>
                                    </tr>
                                    <tr>
                                        <th> Product Categories:</th>
                                        <td>
                                            <?php
                                            if (@$catArr && count($catArr) > 0) {
                                                echo implode(", ", $catArr);
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    if ($product->product_type == "2") {
                                        ?>
                                        <tr>
                                            <th> Product Variations:</th>
                                            <td>
                                                <table id="dataTable3" class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
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
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php  $i=1; @endphp
                                                        @if(@$product->productCombination && count(@$product->productCombination) > 0)
                                                        @foreach(@$product->productCombination as $combination)
                                                        <tr>
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
                                                        </tr>
                                                        @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        ?>
                                        <tr>
                                            <th> Product Price:</th>
                                            <td><?php echo $current . $product['price']; ?></td>
                                        </tr>
                                        <tr>
                                            <th> Product Sale Price:</th>
                                            <td><?php echo (isset($product['sale_price']) && !empty($product['sale_price'])) ? $current . $product['sale_price'] : "N/A"; ?></td>
                                        </tr>
                                        
                                        <tr>
                                            <th> Product Stock:</th>
                                            <td>{{ isset($product['stock'])?$product['stock']:"N/A" }}</td>
                                        </tr>
                                    <?php } ?>

                                    <?php
                                    if (@$product->product_image) {
                                        $img = @$product->product_image;
                                    } else {
                                        $img = 'uploads/dummy.png';
                                    }
                                    ?>
                                    <tr>
                                        <th> Thumbnail:</th>
                                        <td>
                                            <img src="{{ asset('/storage/'.$img) }}" id="product_image_cls" width="100" height="100">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th> Product Slider Images:</th>
                                        <td>
                                            <div class="row pro_img_sec">
                                                <?php
                                                if (@$product->productSliderImages && count($product->productSliderImages) > 0) {
                                                    foreach ($product->productSliderImages as $img) {
                                                        ?>
                                                        <div class="col-md-2">
                                                            <div class="pro_img">
                                                                <div class="table-wrap">
                                                                    <div class="align-wrap">
                                                                        <ul class="gallery-box">
                                                                            <li style="width: 100%;" data-src="{{ asset('/storage/'.@$img['full_image'])}}">
                                                                                <a href="javascript:void(0);">
                                                                                    <img src="{{ asset('/storage/'.@$img['full_image'])}}" width="150" height="150" id="product_image_cls">
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <div class="col-md-2">
                                                        <div class="pro_img">
                                                            <div class="table-wrap">
                                                                <div class="align-wrap">
                                                                    <img src="{{ asset('/storage/uploads/dummy.png') }}" width="150" height="150" id="product_image_cls">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Product Short Description:</th>
                                        <td>@php echo (!empty($product['short_description']))?$product['short_description']:"N/A"; @endphp</td>
                                    </tr>
                                    <tr>
                                        <th> Description:</th>
                                        <td>@php echo (!empty($product['description']))?$product['description']:"N/A"; @endphp</td>
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
<style type="text/css">
    .pro_offer_sec tr td {
        vertical-align: top;
    }
    .pro_offer_sec tr td:last-child {
        vertical-align: middle;
        text-align: right;
        padding-right: 0;
    }
    .pro_offer_sec table {
        border-bottom: 1px solid #eee;
        margin-bottom: 15px;
    }
    .pro_offer_sec td, .pro_offer_sec th {
        padding: 0 15px 15px;
    }
    .pro_offer_sec th:last-child {
        padding-right: 0;
    }
    .pro_offer_sec th.align-right {
        text-align: right;
    }
    .add_row, .add_tc_row {
        background-color: #28a745;
        color: #fff;
        padding: 3px 8px;
        margin-left: 5px;
    }
    .add_row:hover, .remove_row:hover, .add_tc_row:hover, .remove_row_tc {
        color: #fff;
    }
    .add_row i, .remove_row i, .add_tc_row i, .remove_row_tc i {
        background-color: transparent;
    }
    .remove_row, .remove_row_tc {
        background-color: red;
        color: #fff;
        padding: 3px 8px;
        margin-left: 5px;
    }
    /*================*/
    .pro_img{
        position: relative;
        border: 2px solid #e5e5e5;
        text-align: center;
        height: 160px;
        overflow: hidden;
        padding: 10px;
    }

    .table-wrap {
        display: table;
        width: 100%;
        height: 100%;
    }
    .align-wrap {
        display: table-cell;
        vertical-align: middle;
        width: 100%;
        height: 100%;
    }
    .loading-box {
        position: fixed;
        z-index: 99999999;
        left: 0;
        right: 0;
        bottom: 0;
        top: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background: rgba(255, 255, 255, 0.92);
    }
    /*    .crop_img_slider_cls {
            width: 70%;
        }*/
    .crop_img_slider_cls .fa-modal__close-btn, .crop_img_thamb_cls .fa-modal__close-btn {
        top: -10px;
        right: -10px;
        color: #fff;
        background-color: #32BE30;
        line-height: 22px;
    }
    .pro_img_sec .pro_img img {
        height: 135px;
    }
    .pro_img_sec .close-icon {
        position: absolute;
        top: -5px;
        right: 5px;
        color: #fff;
        background-color: #32BE30;
        border-radius: 100%;
        width: 20px;
        height: 20px;
        z-index: 1;
        text-align: center;
        font-size: 13px;
    }

</style>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.gallery-box').lightGallery();
    });
</script>
@endsection
