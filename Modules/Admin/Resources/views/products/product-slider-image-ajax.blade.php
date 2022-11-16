<?php
$pro_slider_img_db = Session::get('pro_slider_img_db_arr');
?>
<input type="hidden" name="pro_slider_img_db" id="pro_slider_img_db" value="{{ json_encode($pro_slider_img_db) }}" class="form-control">
<?php
$pro_slider_small_img_db = Session::get('pro_slider_small_img_db_arr');
?>
<input type="hidden" name="pro_slider_small_img_db" id="pro_slider_small_img_db" value="{{ json_encode($pro_slider_small_img_db) }}" class="form-control">
<div class="form-group row">
    <?php
    if (@$product->productSliderImages && count($product->productSliderImages) > 0) {
        foreach ($product->productSliderImages as $img) {
            ?>
            <div class="col-sm-2 pro_img_sec">
                <a href="javascript:void(0);" class="close-icon remove-slider-image-db" data-proid="{{ $img->product_id }}" data-id="{{ $img->id }}"><i class="fa fa-times" aria-hidden="true"></i></a>
                <img src="{{ asset('/storage/'.@$img['full_image'])}}" id="product_slider_image_cls" width="100" height="100">
            </div>
            <?php
        }
    }

    $sessionProSliderImg = Session::get('pro_slider_img_arr');
    if (@$sessionProSliderImg && count($sessionProSliderImg) > 0) {
        foreach (@$sessionProSliderImg as $key => $val) {
            ?>
            <div class="col-sm-2 pro_img_sec">
                <a href="javascript:void(0);" class="close-icon remove-slider-image" data-id="{{ $key }}"><i class="fa fa-times" aria-hidden="true"></i></a>
                <img src="{{ $val }}" id="product_slider_image_cls" width="100" height="100">
            </div>
            <?php
        }
    }
    if (@$product->productSliderImages && count($product->productSliderImages) > 0 || @$sessionProSliderImg && count($sessionProSliderImg) > 0) {
        
    } else {
        ?>
        <div class="col-sm-2 pro_img_sec">
            <img src="{{ asset('/storage/uploads/dummy.png') }}" id="product_slider_image_cls" width="100" height="100">
        </div>
        <?php
    }
    ?>
</div>