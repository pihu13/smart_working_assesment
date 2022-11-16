@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Add Product</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.list') }}">Product Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Product</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Add Product</strong>
        </div>
        <div class="card-body">
            @if (count($errors) > 0)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul style="list-style: none;padding-left: 0;">
                    @foreach ($errors->all() as $error)
                    <li><strong>Error!</strong> {{ $error }}</li>
                    @endforeach
                </ul>
                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            @endif
            <form action="{{ route('admin.add.product.post') }}" id="add_pro_frm" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <?php
                $session_product_id = Session::get('session_product_id');
                if (@$session_product_id && !empty($session_product_id)) {
                    $session_product_id = $session_product_id;
                } else {
                    $session_product_id = "";
                }
                $session_product_title = old('title');
                ?>
                <input type="hidden" id="product_id" name="product_id" class="product_id" value="{{ @$session_product_id }}" />

                <div class="form-group row">
                    <div class="col-md-12 select2cuscls">
                        <label class="col-form-label">Store Name<span style="color:red;">*</span></label>
                        <select class="form-control" id="shop_id" name="shop_id">
                            <option value="">Select</option>
                            <?php
                            if (@$stores && count($stores) > 0) {
                                foreach ($stores as $store) {
                                    ?>
                                    <option value="{{ @$store->id }}" <?php echo (@$store->id == @old("shop_id")) ? 'selected="selected"' : ""; ?> >
                                        {{ @$store->store_name }}
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        @if ($errors->has('shop_id'))
                        <div class="invalid-feedback" style="display:block;">
                            {{ $errors->first('shop_id') }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="col-form-label">Product Type <span style="color:red;">*</span></label>
                        <select class="form-control" id="product_type" name="product_type">
                            <option value="1" <?php echo (old('product_type') == '1') ? 'selected="selected"' : ""; ?> >Simple</option>
                            <option value="2" <?php echo (old('product_type') == '2') ? 'selected="selected"' : ""; ?>>Variable</option>
                        </select>
                        @if ($errors->has('product_type'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('product_type') }}</div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="col-form-label" for="text-input">Product Title <span style="color:red;">*</span></label>
                        <input class="form-control" minlength="3" maxlength="100" id="title" name="title" type="text" value="{{ @$session_product_title }}" title="Title" placeholder="Please Enter Title" autocomplete="title">
                        @if ($errors->has('title'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('title') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group row appned_variation_sec"></div>

                <div class="form-group row">
                    <label class="col-md-12 col-form-label" for="text-input">Thumbnail <span style="color:red;">*</span></label>
                    <div class="col-md-6">
                        <input type="file" name="pro_image_thamb" accept=".png, .jpg, .jpeg" id="pro_image_thamb" value="{{old('pro_image_thamb')}}" class="form-control image pro_image_thamb">
                        <span>Minimum image size 400*250</span>
                        @if ($errors->has('pro_thumbnail_img_db'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('pro_thumbnail_img_db') }}</div>
                        @endif
                    </div>
                    <?php
                    $pro_thumbnail_img_db = Session::get('pro_thumbnail_img_db');
                    ?>
                    <input type="hidden" name="pro_thumbnail_img_db" id="pro_thumbnail_img_db" value="{{ $pro_thumbnail_img_db }}" class="form-control">
                    <div class="col-md-6">
                        <?php
                        $SessionThumbnailImg = Session::get('pro_thumbnail_img');
                        if (isset($SessionThumbnailImg)) {
                            $img = $SessionThumbnailImg;
                        } else {
                            $img = asset('/storage/uploads/dummy.png');
                        }
                        ?>
                        <img src="{{ $img }}" id="product_image_cls" width="100" height="100">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="col-form-label" for="text-input">Product Slider Images <span style="color:red;">*</span></label>
                        <input type="file" name="pro_slider_image" accept=".png, .jpg, .jpeg" id="pro_slider_image" class="form-control pro_slider_image">
                        <span>Minimum image size 600*600</span>
                        @if ($errors->has('pro_slider_img_db'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('pro_slider_img_db') }}</div>
                        @endif
                    </div>

                    <div class="col-md-6 slider_img_cls">
                        <?php
                        $pro_slider_img_db = Session::get('pro_slider_img_db_arr');
                        if ($pro_slider_img_db == null) {
                            $pro_slider_img_db = "";
                        } else {
                            $pro_slider_img_db = json_encode($pro_slider_img_db);
                        }
                        ?>
                        <input type="hidden" name="pro_slider_img_db" id="pro_slider_img_db" value="{{ $pro_slider_img_db }}" class="form-control">
                        <?php
                        $pro_slider_small_img_db = Session::get('pro_slider_small_img_db_arr');
                        if ($pro_slider_small_img_db == null) {
                            $pro_slider_small_img_db = "";
                        } else {
                            $pro_slider_small_img_db = json_encode($pro_slider_small_img_db);
                        }
                        ?>
                        <input type="hidden" name="pro_slider_small_img_db" id="pro_slider_small_img_db" value="{{ $pro_slider_small_img_db }}" class="form-control">
                        <div class="form-group row">
                            <?php
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
                            } else {
                                ?>
                                <div class="col-sm-2 pro_img_sec">
                                    <img src="{{ asset('/storage/uploads/dummy.png') }}" id="product_slider_image_cls" width="100" height="100">
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>


                <div class="form-group row price_section">
                    <div class="col-md-6">
                        <label class="col-form-label" for="text-input">Product Price <span style="color:red;">*</span></label>
                        <input class="form-control" id="price" minlength="1" maxlength="10" name="price" type="text" value="{{old('price')}}" title="Price" placeholder="Please Enter Price" autocomplete="price">
                        @if ($errors->has('price'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('price') }}</div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="col-form-label" for="text-input">Product Sale Price</label>
                        <input class="form-control" id="sale_price" minlength="1" maxlength="10" name="sale_price" type="text" value="{{ old('sale_price') }}" title="Sale Price" placeholder="Please Enter Sale Price" autocomplete="price">
                        <span id="sale_price_span"></span>
                        @if ($errors->has('sale_price'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('sale_price') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group row price_section">
                    <div class="col-md-12">
                        <label class="col-form-label" for="text-input">Product Stock <span style="color:red;">*</span></label>
                        <input class="form-control" id="stock" minlength="1" maxlength="5" name="stock" type="text" value="{{old('stock')}}" title="Stock" placeholder="Please Enter Stock" autocomplete="stock">
                        @if ($errors->has('stock'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('stock') }}</div>
                        @endif
                    </div>
<!--                    <div class="col-md-6">
                        <label class="col-form-label">Product Tax</label>
                        <input class="form-control product_tax" id="product_tax" minlength="1" maxlength="10" name="product_tax" type="text" value="{{ (@old('product_tax'))?old('product_tax'):"0" }}" title="Product Tax" placeholder="Please Enter Product Tax" autocomplete="off">
                        @if ($errors->has('product_tax'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('product_tax') }}</div>
                        @endif
                    </div>-->
                </div>
                
                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label" for="text-input">SKU <span style="color:red;">*</span></label>
                        <input class="form-control" id="sku" minlength="1" maxlength="255" name="sku" type="text" value="{{old('sku')}}" title="SKU" placeholder="Please Enter SKU" autocomplete="off">
                        @if ($errors->has('sku'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('sku') }}</div>
                        @endif
                    </div>
                </div>
                
                <div class="form-group row price_section">
                    <div class="col-md-12">
                        <label class="col-form-label" for="text-input">Product Default Value <span style="color:red;">*</span></label>
                        <input class="form-control" id="stock" minlength="1" maxlength="255" name="product_default_value" type="text" value="{{old('product_default_value')}}" title="Stock" placeholder="Please Product Default Value" autocomplete="off">
                        @if ($errors->has('product_default_value'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('product_default_value') }}</div>
                        @endif
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-md-12 select2cuscls">
                        <label class="col-form-label" for="select2">Product Categories <span style="color:red;">*</span></label>
                        <select class="form-control form-control-lg" id="category_id" name="category_id[]" multiple="multiple">
                            @foreach($categories as $category)
                            <option value="{{$category->id}}" <?php echo (@in_array($category->id, old('category_id'))) ? 'selected="selected"' : ""; ?>>
                                {{$category->name}}
                            </option>
                            @foreach ($category->children as $child)
                            <option value="{{$child->childCat->id}}" <?php echo (@in_array($child->childCat->id, old('category_id'))) ? 'selected="selected"' : ""; ?>>
                                -{{ $child->childCat->name }}
                            </option>
                            @if(count($child->childCat->children))
                            @include('admin::products.recursive-category',['childs' => $child->childCat->children])
                            @endif
                            @endforeach
                            @endforeach

                        </select>
                        @if ($errors->has('category_id'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('category_id') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-12 col-form-label" for="text-input">Product Description <span style="color:red;">*</span></label>
                    <div class="col-md-12">
                        <textarea class="form-control" maxlength="1000" id="description" name="description">{{old('description')}}</textarea>
                        <span>Max Characters limit 1000</span>
                        @if ($errors->has('description'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('description') }}</div>
                        @endif
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-md-12 col-form-label" for="text-input">Other Info</label>
                    <div class="col-md-12">
                        <textarea class="form-control" minlength="1" maxlength="1000" id="short_description" name="short_description">{{ old('short_description') }}</textarea>
                        <span>Max Characters limit 1000</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-12 col-form-label" for="text-input">Status <span style="color:red;">*</span></label>
                    <div class="col-md-12">
                        <div class="form-check form-check-inline form-check-sm mr-2">
                            <input type="radio"  class="form-check-input" id="inline-radio1" name="status" value="1" <?php echo (old('status') == "1") ? 'checked="checked"' : ""; ?>>
                            <label class="form-check-label" for="inline-radio1">Active</label>
                        </div>
                        <div class="form-check form-check-inline form-check-sm mr-2">
                            <input type="radio" class="form-check-input" id="inline-radio2"" name="status" value="0" <?php echo (old('status') == "0") ? 'checked="checked"' : ""; ?>>
                            <label class="form-check-label" for="inline-radio2">Inactive</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        @if ($errors->has('status'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                </div>

                <div class="card-footer">
                    <button class="btn btn-sm btn-primary" type="submit">Save</button>
                    <button class="btn btn-sm btn-danger reset_form" type="reset"> Reset</button>
                </div> 
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<div class="fa-modal class_crop_popup crop_img_thamb crop_img_thamb_cls" style="width:30%;">
    <div class="modal-wrap">
        <div class="fa-modal__close-btn"></div>
        <div class="panel panel-info">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div id="upload-thamb-img"></div>
                    </div>
                    <div class="col-md-6">
                        <input type="file" name="pro_image_thamb" accept=".png, .jpg, .jpeg" id="pro_image_thamb" class="form-control pro_image_thamb">
                        <span>Minimum image size 400*250</span>
                    </div>
                    <div class="col-md-6 text-center">
                        <button class="btn btn-primary btn-block upload-thamb-image">Upload Image</button>
                        <div class="alert alert-success" id="upload-success" style="display: none;margin-top:10px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fa-modal class_crop_popup crop_img_slider crop_img_slider_cls full_image_pop_scroll" style="width:50%;">
    <div class="modal-wrap">
        <div class="fa-modal__close-btn"></div>
        <div class="panel panel-info">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div id="upload-slider-img"></div>
                    </div>
                    <div class="col-md-6">
                        <input type="file" name="pro_slider_image" accept=".png, .jpg, .jpeg" id="pro_slider_image" class="form-control pro_slider_image">
                        <span>Minimum image size 600*600</span>
                    </div>
                    <div class="col-md-6 text-center">
                        <button class="btn btn-primary btn-block upload-slider-image">Upload Image</button>
                        <div class="alert alert-success" id="upload-success" style="display: none;margin-top:10px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style type="text/css">
    .crop_img_slider_cls .fa-modal__close-btn, .crop_img_thamb_cls .fa-modal__close-btn {
        top: -10px;
        right: -10px;
        color: #fff;
        background-color: #32BE30;
        line-height: 22px;
    }

</style>
<style type = "text/css">
    .close-icon {
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
    .disabled_cls {
        opacity: 0.65;
        cursor: not-allowed;
        pointer-events: none;
    }
    .custom-select2 .select2-selection {
        height: 48px!important;
    }
    .custom-select2 .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px!important;
        background-image: url('{{ asset("/front/assets/images/down-arrow2.svg") }}')!important;
        background-position: 95% 50%!important;
        background-size: 11.5px auto!important;
        background-repeat: no-repeat!important;
    }
    .custom-select2 .select2-selection__arrow b {
        display: none;
    }
    .custom-select2 .select2-container {
        width: 100%!important;
        /*        height: 100% !important; */
    }

    .custom-select2 .select2-container selection {
        disply: inline-block;
        width: 100%;
    }
    .custom-select2 .custom-select2 .select2-selection {
        height: 48px!important;
    }
    .custom-select2 .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 46px!important;
        font-size: 18px;
        font-weight: 400;
        color: #9B9B9B;
        padding: 0 20px;
    }
</style>
<script type = "text/javascript">
//    window.onload = function () {
//        //description
//        CKEDITOR.instances.description.on('key', function (event) {
//            var deleteKey = 46;
//            var backspaceKey = 8;
//            var keyCode = event.data.keyCode;
//            if (keyCode === deleteKey || keyCode === backspaceKey)
//                return true;
//            else
//            {
//                var textLimit = 500;
//                var str = CKEDITOR.instances.description.getData();
//                if (str.length >= textLimit)
//                    return false;
//            }
//        });
//    };

    jQuery(document).ready(function () {
        CKEDITOR.replace('description');
        CKEDITOR.replace('short_description');
        
        jQuery('#category_id').select2({
            placeholder: "Please Select",
            allowClear: true,
            allowHtml: true
        });
    });

    /*
     * Upload product thaimnail image 
     */
    $(document).ready(function () {
        var resize = $('#upload-thamb-img').croppie({
            enableExif: true,
            enableOrientation: true, viewport: {
                width: 400,
                height: 250,
                type: 'square'
            },
            boundary: {
                width: 500,
                height: 400
            }
        });
        $modalCropImgThamb = jQuery('.crop_img_thamb').faModal();

        $('.pro_image_thamb').on('change', function () {
            $modalCropImgThamb.faModal('show');
            var reader = new FileReader();
            reader.onload = function (e) {
                resize.croppie('bind', {
                    url: e.target.result
                }).then(function () {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.upload-thamb-image').on('click', function (ev) {
            resize.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (img) {
                $.ajax({
                    url: "{{route('admin.crop.product.image')}}",
                    type: "POST",
                    data: {_token: '{{ csrf_token() }}', "image": img},
                    beforeSend: function () {
                        $(".loading-box").show();
                    },
                    success: function (data) {
                        $("#product_image_cls").attr('src', data.file_name);
                        $("#pro_thumbnail_img_db").val(data.file_name_db);
                        $modalCropImgThamb.faModal('hide');
                    },
                    error: function (xhr) {
                        $(".loading-box").hide();
                    },
                    complete: function () {
                        $(".loading-box").hide();
                    }
                });
            });
        });
    });

    jQuery(document).ready(function () {
        jQuery('#shop_id').select2({
            placeholder: "Select",
            allowClear: true,
            allowHtml: true
        });

        jQuery(document).on("keyup", "#sale_price", function () {
            var price = jQuery('#price').val();
            var sale_price = jQuery('#sale_price').val();
            if (parseInt(sale_price) >= parseInt(price)) {
                jQuery("#sale_price_span").show();
                jQuery('#sale_price_span').html('<label for="user_name" class="error sale_price_error">Sale price should be not greater than to product price</label>');
            } else {
                jQuery("#sale_price_span").hide();
            }
        });
    });


    jQuery(document).ready(function () {
        var resize = jQuery('#upload-slider-img').croppie({
            enableExif: true,
            enableOrientation: true, viewport: {
                width: 600,
                height: 600,
                type: 'square'
            },
            boundary: {
                width: 800,
                height: 800
            }
        });
        $modalCropImgSlider = jQuery('.crop_img_slider').faModal();

        jQuery('.pro_slider_image').on('change', function () {
            $modalCropImgSlider.faModal('show');
            var reader = new FileReader();
            reader.onload = function (e) {
                resize.croppie('bind', {
                    url: e.target.result
                }).then(function () {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
        });

        jQuery('.upload-slider-image').on('click', function (ev) {
            resize.croppie('result', {
                type: 'canvas',
                size: {
                    width: 600,
                    height: 600,
                },
            }).then(function (img) {
                var product_id = "";
                jQuery.ajax({
                    url: "{{route('admin.crop.product.slider.image')}}",
                    type: "POST",
                    data: {_token: '{{ csrf_token() }}', "image": img},
                    dataType: "json",
                    beforeSend: function () {
                        jQuery(".loading-box").show();
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.status == '200') {
                            jQuery(".slider_img_cls").html(response.data);
                            $modalCropImgSlider.faModal('hide');
                        } else if (response.status == '501') {
                            swal("Error deleting!", response.msg, "error");
                            $modalCropImgSlider.faModal('hide');
                        } else {
                            swal("Error deleting!", "Either something went wrong or invalid access!", "error");
                            $modalCropImgSlider.faModal('hide');
                        }
                        $modalCropImgSlider.faModal('hide');
                        jQuery(".loading-box").hide();
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
                    },
                    error: function (xhr) {
                        swal("Error deleting!", "Either something went wrong or invalid access!", "error");
                        $modalCropImgSlider.faModal('hide');
                        jQuery(".loading-box").hide();
                    },
                    complete: function () {
                        $modalCropImgSlider.faModal('hide');
                        jQuery(".loading-box").hide();
                    }
                });
            });
        });

        jQuery(document).on("click", ".remove-slider-image", function () {
            var imgId = jQuery(this).attr("data-id");
            jQuery.ajax({
                url: "{{route('admin.remove.product.slider.image')}}",
                type: "POST",
                data: {_token: '{{ csrf_token() }}', "imgId": imgId},
                dataType: "json", beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    if (response.status == '200') {
                        jQuery(".slider_img_cls").html(response.data);
                        swal("Done!", response.msg, "success");
                    } else if (response.status == '501') {
                        swal("Error deleting!", response.msg, "error");
                    } else {
                        swal("Error deleting!", "Either something went wrong or invalid access!", "error");
                    }
                    jQuery(".loading-box").hide();
                    setTimeout(function () {
                        swal.close();
                    }, 2000);
                },
                error: function (xhr) {
                    swal("Error deleting!", "Either something went wrong or invalid access!", "error");
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
    var old_product_type = '<?php echo old("product_type"); ?>';
    var old_title = '<?php echo old("title"); ?>';
    if (old_title == "") {
        if (old_product_type == '2') {
            jQuery(".price_section").hide();
            addProductParcially();
        } else {
            jQuery(".variation_section_div").hide();
            jQuery(".appned_variation_sec").html("");
            jQuery(".price_section").show();
        }
    } else {
        if (old_product_type == '2') {
            addProductParcially();
            jQuery(".price_section").hide();
        } else {
            jQuery(".variation_section_div").hide();
            jQuery(".appned_variation_sec").html("");
            jQuery(".price_section").show();
        }
    }

    jQuery(document).ready(function () {
        jQuery("#product_type").on("change", function () {
            var product_type = jQuery("#product_type").val();
            var title = jQuery("#title").val();
            if (title == "") {
                if (product_type == '2') {
                    jQuery(".price_section").hide();
                    addProductParcially();
                } else {
                    jQuery(".variation_section_div").hide();
                    jQuery(".appned_variation_sec").html("");
                    jQuery(".price_section").show();
                }
            } else {
                if (product_type == '2') {
                    addProductParcially();
                    jQuery(".price_section").hide();
                } else {
                    jQuery(".variation_section_div").hide();
                    jQuery(".appned_variation_sec").html("");
                    jQuery(".price_section").show();
                }
            }
        });
    });

    function addProductParcially() {
        var formData = new FormData(jQuery("#add_pro_frm")[0]);
        jQuery.ajax({
            url: '{{ route("admin.store.product.partially") }}',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function () {
                jQuery(".loading-box").show();
            },
            success: function (response) {
                console.log(response);
                jQuery(".fee_error").show();
                jQuery(".fee_error").text("");
                if (response.status == "501") {
                    var counti = 1;
                    jQuery.each(response.errors, function (i, file) {
                        if (counti == 1) {
                            jQuery('html,body').animate({scrollTop: jQuery('#' + i).offset().top - 140}, 1000);
                        }
                        jQuery('#' + i).after('<span class="fee_error">' + file + '</span>');
                        counti++;
                    });
                } else if (response.status == '200') {
                    jQuery(".fee_error").hide();
                    jQuery("#product_id").val(response.session_product_id);
                    jQuery(".appned_variation_sec").html(response.data);
                } else if (response.status == '201') {
                    jQuery(".fee_error").hide();
                    jQuery(".appned_variation_sec").html(response.data);
                } else {
                    swal("Error deleting!", "Either something went wrong or invalid access!", "error");
                }
                jQuery(".loading-box").hide();
                setTimeout(function () {
                    swal.close();
                }, 2000);
            },
            error: function (xhr) {
                swal("Error deleting!", "Please try again", "error");
                jQuery(".loading-box").hide();
            },
            complete: function () {
                jQuery(".loading-box").hide();
            }
        });
    }
</script>
@include('admin::products.combination-script-css')
@endsection

