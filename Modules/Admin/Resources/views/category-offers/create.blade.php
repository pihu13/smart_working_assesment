@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Add Offer</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.category.offers')}}">Offer Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Offer</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Add Offer</strong>
        </div>
        <div class="card-body">
            @if (count($errors) > 0)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul style="list-style: none;">
                    @foreach ($errors->all() as $error)
                    <li>-{{ $error }}</li>
                    @endforeach
                </ul>
                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            @endif
            <form action="{{ route('admin.add.offer.post') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Category <span style="color:red;">*</span></label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">Select</option>
                                <?php
                                if (@$categories && count($categories) > 0) {
                                    foreach ($categories as $category) {
                                        ?>
                                        <option value="{{ @$category->id }}">
                                            {{ @$category->name }}
                                        </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            @if ($errors->has('category_id'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('category_id') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Title <span style="color:red;">*</span></label>
                            <input class="form-control" id="title" value="{{ old("title") }}" name="title" minlength="1" title="Title" maxlength="100" type="text" placeholder="Enter Title" autocomplete="off">
                            @if ($errors->has('title'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Offer Type <span style="color:red;">*</span></label>
                            <select class="form-control" id="offer_type" name="offer_type">
                                <option value="1">Fixed</option>
                                <option value="2">Percentage</option>
                            </select>
                            @if ($errors->has('offer_type'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('offer_type') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Price Discount <span style="color:red;">*</span></label>
                            <input type="text" name="price_discount" value="{{ old("price_discount") }}" id="price_discount" title="Price Discount" minlength="1" maxlength="3" class="form-control" placeholder="Please enter price discount">
                            @if ($errors->has('price_discount'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('price_discount') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Offer Banner Type <span style="color:red;">*</span></label>
                            <select class="form-control" id="banner_type" name="banner_type">
                                <option value="1" <?php echo (@old("banner_type") == "1")?'selected="selected"':""; ?>>Full</option>
                                <option value="2" <?php echo (@old("banner_type") == "2")?'selected="selected"':""; ?>>Small</option>
                            </select>
                            @if ($errors->has('banner_type'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('banner_type') }}</div>
                            @endif
                        </div>
                    </div>
                    
                    <?php 
                    if(@old("banner_type") == "2"){
                        $div_cls1 = "hide_cls";
                        $div_cls2 = "show_cls";
                    }else{
                        $div_cls2 = "hide_cls";
                        $div_cls1 = "show_cls";
                    }
                    ?>


                    <div class="col-sm-3 full_img_div {{ @$div_cls1 }}">
                        <div class="form-group">
                            <label for="name">Banner Image <span style="color:red;">*</span></label>
                            <input class="form-control full_banner_img" id="full_banner_img" accept=".png, .jpg, .jpeg, .svg" name="full_banner_img" type="file" title="Full Banner Image" placeholder="Please Choose Banner Image" autocomplete="off">
                            <span>Minimum image size 600*600</span>
                            @if ($errors->has('full_banner_img_db'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('full_banner_img_db') }}</div>
                            @endif
                        </div>
                    </div>
                    <?php
                    if (@old("full_banner_img_db")) {
                        $img = old("full_banner_img_db");
                    } else {
                        $img = "uploads/dummy.png";
                    }
                    ?>
                    <input type="hidden" name="full_banner_img_db" id="full_banner_img_db" value="{{ @old("full_banner_img_db") }}" class="form-control">
                    <div class="col-sm-3 full_img_div {{ @$div_cls1 }}">
                        <div class="form-group">
                            <label for="name"></label><br>
                            <img src="{{ asset('/storage/'.$img) }}" id="full_banner_img_cls" width="100" height="100">
                        </div>
                    </div>

                    <div class="col-sm-3 small_img_div {{ $div_cls2 }}">
                        <div class="form-group">
                            <label for="name">Banner Image <span style="color:red;">*</span></label>
                            <input class="form-control small_banner_img" id="small_banner_img" accept=".png, .jpg, .jpeg, .svg" name="small_banner_img" type="file" title="Small Banner" placeholder="Please Choose Small Banner" autocomplete="off">
                            <span>Minimum image size 250*250</span>
                            @if ($errors->has('small_banner_img_db'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('small_banner_img_db') }}</div>
                            @endif
                        </div>
                    </div>

                    <?php
                    if (@old("small_banner_img_db")) {
                        $img = old("small_banner_img_db");
                    } else {
                        $img = "uploads/dummy.png";
                    }
                    ?>
                    <input type="hidden" name="small_banner_img_db" id="small_banner_img_db" value="{{ @old("small_banner_img_db") }}" class="form-control">
                    <div class="col-sm-3 small_img_div {{ $div_cls2 }}">
                        <div class="form-group">
                            <label for="name"></label><br>
                            <img src="{{ asset('/storage/'.$img) }}" id="small_banner_img_cls" width="100" height="100">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Valid From Date <span style="color:red;">*</span></label>
                            <input type="text" name="valid_from_date" id="valid_from_date" value="{{ old("valid_from_date") }}" title="Valid From Date" class="form-control" placeholder="Please enter valid from date">
                            @if ($errors->has('valid_from_date'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('valid_from_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Valid to Date <span style="color:red;">*</span></label>
                            <input type="text" name="valid_to_date" id="valid_to_date" value="{{ old("valid_to_date") }}" title="Valid To Date" class="form-control" placeholder="Please enter valid to date">
                            @if ($errors->has('valid_to_date'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('valid_to_date') }}</div>
                            @endif
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Status <span style="color:red;">*</span></label>
                            <div class="form-check form-check-inline form-check-sm mr-2">
                                <input type="radio"  class="form-check-input" id="inline-radio1" name="status" <?php echo (old('status') == "1") ? 'checked="checked"' : ""; ?> value="1"  >
                                <label class="form-check-label" for="inline-radio1">Active</label>
                            </div>
                            <div class="form-check form-check-inline form-check-sm mr-2">
                                <input type="radio" class="form-check-input" id="inline-radio2"" name="status" <?php echo (old('status') == "0") ? 'checked="checked"' : ""; ?> value="0" >
                                <label class="form-check-label" for="inline-radio2">Inactive</label>
                            </div>
                            @if ($errors->has('status'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
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
<style type="text/css">
    .show_cls{
        display: block;
    }
    .hide_cls{
        display: none;
    }
</style>
<div class="fa-modal crop_full_banner_img_cls" style="width: 50%;">
    <div class="modal-wrap">
        <div class="fa-modal__close-btn"></div>
        <div class="panel panel-info">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div id="upload-full-banner"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input class="form-control full_banner_img" id="full_banner_img" accept=".png, .jpg, .jpeg" name="full_banner_img" type="file" title="Full Banner Image" placeholder="Please Choose Banner Image" autocomplete="off">
                            <span>Minimum image size 600*600</span>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <button class="btn btn-primary btn-block upload-full-img-cls">Upload Image</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fa-modal crop_small_banner_img_cls" style="width: 50%;">
    <div class="modal-wrap">
        <div class="fa-modal__close-btn"></div>
        <div class="panel panel-info">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div id="upload-small-banner"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input class="form-control small_banner_img" id="small_banner_img" accept=".png, .jpg, .jpeg, .svg" name="small_banner_img" type="file" title="Small Banner" placeholder="Please Choose Small Banner" autocomplete="off">
                            <span>Minimum image size 250*250</span>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <button class="btn btn-primary btn-block upload-small-img-cls">Upload Image</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".reset_form").on("click", function () {
            location.reload();
        });

        /*
         * Check True and False Statements
         */
        jQuery("#banner_type").on("change", function () {
            var banner_type = jQuery("#banner_type").val();
            if (banner_type == "2") {
                jQuery(".full_img_div").hide();
                jQuery(".small_img_div").show();
            } else {
                jQuery(".small_img_div").hide();
                jQuery(".full_img_div").show();
            }
        });

        /*
         * Start Date and End date 
         */
        jQuery(function () {
            var dateToday = new Date();
            var dates = jQuery("#valid_from_date, #valid_to_date").datepicker({
                dateFormat: "yy-mm-dd",
                defaultDate: "+0w",
                changeMonth: true,
                numberOfMonths: 1,
                minDate: dateToday,
                onSelect: function (selectedDate) {
                    var option = this.id == "valid_from_date" ? "minDate" : "maxDate",
                            instance = jQuery(this).data("datepicker"),
                            date = jQuery.datepicker.parseDate(instance.settings.dateFormat || jQuery.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                    dates.not(this).datepicker("option", option, date);
                }
            });
        });

        fullBannerImg();
        smallBannerImg();
    });

    function ImgReadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                jQuery('#brand_image_cls').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    /*
     * Upload Full Banner Image
     * @param : banner_img
     * @return response
     */
    function fullBannerImg() {
        $modelFullBanner = jQuery('.crop_full_banner_img_cls').faModal();
        var resize = jQuery('#upload-full-banner').croppie({
            enableExif: true,
            enableOrientation: true, viewport: {
                width: 600,
                height: 600,
                type: 'square'
            },
            boundary: {
                width: 650,
                height: 650
            }
        });

        jQuery('.full_banner_img').on('change', function () {
            $modelFullBanner.faModal('show');
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

        jQuery('.upload-full-img-cls').on('click', function (ev) {
            resize.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (img) {
                jQuery.ajax({
                    url: "{{ route('admin.upload.offer.img') }}",
                    type: "POST",
                    data: {_token: '{{ csrf_token() }}', "image": img},
                    success: function (data) {
                        jQuery("#full_banner_img_cls").attr('src', data.file_name);
                        jQuery("#full_banner_img_db").val(data.file_name_db);
                        $modelFullBanner.faModal('hide');
                    }
                });
            });
        });
    }

    /*
     * Upload Small Banner Image
     * @param : banner_img
     * @return response
     */
    function smallBannerImg() {
        $modelSmallBanner = jQuery('.crop_small_banner_img_cls').faModal();
        var resize = jQuery('#upload-small-banner').croppie({
            enableExif: true,
            enableOrientation: true, viewport: {
                width: 600,
                height: 600,
                type: 'square'
            },
            boundary: {
                width: 650,
                height: 650
            }
        });

        jQuery('.small_banner_img').on('change', function () {
            $modelSmallBanner.faModal('show');
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

        jQuery('.upload-small-img-cls').on('click', function (ev) {
            resize.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (img) {
                jQuery.ajax({
                    url: "{{ route('admin.upload.offer.img') }}",
                    type: "POST",
                    data: {_token: '{{ csrf_token() }}', "image": img},
                    success: function (data) {
                        jQuery("#small_banner_img_cls").attr('src', data.file_name);
                        jQuery("#small_banner_img_db").val(data.file_name_db);
                        $modelSmallBanner.faModal('hide');
                    }
                });
            });
        });
    }
</script>
@endsection


