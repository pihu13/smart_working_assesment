@extends('admin::layouts.master')
@section('admin::content')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/css/intlTelInput.css">
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Add Store</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{route('admin.dashboard')}}">Home</a>
                        <li class="breadcrumb-item"><a href="{{ route('admin.shops.list') }}">Store Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Store</li>
                        </li>
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
            <div class="row">
                <div class="col-sm-12">
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
                </div>
            </div>
        </div>

        <form action="{{ route("admin.add.shop.post") }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Name <span style="color:red;">*</span></label>
                            <input class="form-control" id="store_name" name="store_name" type="text" title="Store Name" placeholder="Please Enter Store Name" autocomplete="off" value="{{ old("store_name") }}">
                            @if ($errors->has('store_name'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('store_name') }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Vendor Logo Start -->
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="name">Logo <span style="color:red;">*</span></label>
                            <input class="form-control store_logo" id="store_logo" accept=".png, .jpg, .jpeg, .svg" name="store_logo" type="file" title="Store Logo" placeholder="Please Choose Store Logo" autocomplete="off">
                            <span>Minimum image size 250*250</span>
                            @if ($errors->has('store_logo_db'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('store_logo_db') }}</div>
                            @endif
                        </div>
                    </div>

                    <?php
                    if (@old("store_logo_db")) {
                        $img = old("store_logo_db");
                    } else {
                        $img = "uploads/dummy.png";
                    }
                    ?>
                    <input type="hidden" name="store_logo_db" id="store_logo_db" value="{{ @old("store_logo_db") }}" class="form-control">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="name"></label><br>
                            <img src="{{ asset('/storage/'.$img) }}" id="store_logo_cls" width="100" height="100">
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Owner Name <span style="color:red;">*</span></label>
                            <input class="form-control" id="store_owner_name" name="store_owner_name" type="text" title="Store Name" placeholder="Please Enter Store Owner Name" autocomplete="off" value="{{ old("store_owner_name") }}">
                            @if ($errors->has('store_owner_name'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('store_owner_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Email Address <span style="color:red;">*</span></label>
                            <input class="form-control" id="store_name" name="store_email" type="text" title="Store Email" placeholder="Please Enter Store Email" autocomplete="off" value="{{ old("store_email") }}">
                            @if ($errors->has('store_email'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('store_email') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6 phone_number_sec">
                        <div class="form-group">
                            <label for="name">Contact Number <span style="color:red;">*</span></label>
                            <input class="form-control" id="phone_number" name="store_contact_no" type="text" title="Store Contact Number" placeholder="Please Enter Store Contact Number" autocomplete="off" value="{{ old("store_contact_no") }}">

                            <label for="phone_number" style="color: red;display:none;" class="error_code">Phone number is invalid.</label>
                            <input type="hidden" class="contact_code"  name="contact_code" value="{{ (@old('contact_code' ))?old('contact_code' ):"+1" }}">
                            <input type="hidden" class="country_name"  name="country_name" value="{{ (@old('country_name' ))?old('country_name' ):"United States: +1" }}">
                            <input type="hidden" class="country_code" name="country_code" value="{{ (@old('country_code' ))?old('country_code' ):"us" }}">

                            @if ($errors->has('store_contact_no'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('store_contact_no') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Address <span style="color:red;">*</span></label>
                            <input class="form-control store_address googlemapshown1" id="store_address" name="store_address" type="text" title="Store Address" placeholder="Please Enter Store Address" autocomplete="off" value="{{ old("store_address") }}">
                            @if ($errors->has('store_address'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('store_address') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group pro_offer_sec">

                            <table style="width:100%;">
                                <tr>
                                    <th>
                                        <label class="col-form-label">Shop Opening/Closing Time</label> 
                                        <div class="form-check form-check-inline form-check-sm mr-2">
                                            <input type="checkbox" class="form-check-input" id="same_time1" name="same_time" value="1" <?php echo (old("same_time") == "1") ? 'checked="checked"' : ""; ?>>
                                            <label class="form-check-label" for="same_time1">Thought out week timing</label>
                                        </div>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th class="align-right">
                                        <a href="javascript:void(0);" class="add_tc_row same_time_cls">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </th>
                                </tr>
                            </table>
                            <table style="width:100%;" class="same_time_cls">
                                <tbody class="append_term_row">
                                    <tr>
                                        <th>Week Name <span style="color:red;">*</span></th>
                                        <th>Opening Time <span style="color:red;">*</span></th>
                                        <th>Closing Time <span style="color:red;">*</span></th>
                                        <th></th>
                                    </tr>
                                    <?php
                                    if (@old("day_name") && count(old("day_name")) > 0) {
                                        foreach (old("day_name") as $key => $day_name) {
                                            ?>
                                            <tr>
                                                <td align="top">
                                                    <select name="day_name[]" class="form-control">
                                                        <option value="">Day Name</option>
                                                        <option value="1">Monday</option>
                                                        <option value="2">Tuesday</option>
                                                        <option value="3">Wednesday</option>
                                                        <option value="4">Thursday</option>
                                                        <option value="5">Friday</option>
                                                        <option value="6">Saturday</option>
                                                        <option value="7">Sunday</option>
                                                    </select>
                                                    @if ($errors->has("day_name.{$key}"))
                                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first("day_name.{$key}") }}</div>
                                                    @endif
                                                </td>
                                                <td align="top">
                                                    <input type="text" name="start_time[]" value="" placeholder="Please select start time" class="form-control start_time">
                                                    @if ($errors->has("start_time.{$key}"))
                                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first("start_time.{$key}") }}</div>
                                                    @endif
                                                </td>
                                                <td align="top">
                                                    <input type="text" name="end_time[]" value="" placeholder="Please select end time" class="form-control end_time">
                                                    @if ($errors->has("end_time.{$key}"))
                                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first("end_time.{$key}") }}</div>
                                                    @endif
                                                </td>
                                                <td align="top">
                                                    <a href="javascript:void(0);" class="remove_row_tc" data-id=""><i class="fas fa-minus"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>


                            <table style="width:100%;display:none;" class="same_time_second">
                                <tbody>
                                    <tr>
                                        <th>Opening Time <span style="color:red;">*</span></th>
                                        <th>Closing Time <span style="color:red;">*</span></th>
                                    </tr>
                                    <tr>
                                        <td align="top">
                                            <input type="text" name="same_start_time" value="{{ old("same_start_time") }}" placeholder="Please select start time" class="form-control same_start_time">
                                            @if ($errors->has('same_start_time'))
                                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('same_start_time') }}</div>
                                            @endif
                                        </td>
                                        <td align="top">
                                            <input type="text" name="same_end_time" value="{{ old("same_end_time") }}" placeholder="Please select end time" class="form-control same_end_time">
                                            @if ($errors->has('same_end_time'))
                                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('same_end_time') }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">Description</label>
                            <textarea class="form-control" id="description" name="description">{{ old("description") }}</textarea>
                        </div>
                    </div>


                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">Status <span style="color:red;">*</span></label>
                            <div class="form-check form-check-inline form-check-sm mr-2">
                                <input type="radio"  class="form-check-input" id="inline-radio1" name="status" value="1" <?php echo (old("status") == "1") ? 'checked="checked"' : ""; ?>>
                                <label class="form-check-label" for="inline-radio1">Active</label>
                            </div>
                            <div class="form-check form-check-inline form-check-sm mr-2">
                                <input type="radio" class="form-check-input" id="inline-radio2" name="status" value="0" <?php echo (old("status") == "0") ? 'checked="checked"' : ""; ?>>
                                <label class="form-check-label" for="inline-radio2">Inactive</label>
                            </div>
                            @if ($errors->has('status'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
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
@endsection
@section('js')
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"></script>
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


    .phone_number_sec .intl-tel-input {
        width: 100%;
    }
    .phone_number_sec .form-group .form-control {
        padding-left: 80px;
    }
    .phone_number_sec .selected-dial-code {
        display: inline-block;
        padding-left: 25px;
        vertical-align: middle;
        padding-top: 11px;
    }
    .phone_number_sec .flag-box {
        display: inline-block;
        vertical-align: middle;
    }
    .phone_number_sec .country .country-name, .phone_number_sec .country .country-dial-code {
        overflow: hidden;
        padding-left: 15px;
    }
</style>

<div class="fa-modal class_crop_popup crop_img_thamb crop_vendor_logo_cls" style="width: 30%;">
    <div class="modal-wrap">
        <div class="fa-modal__close-btn"></div>
        <div class="panel panel-info">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div id="upload-shop-logo"></div>
                    </div>
                    <div class="col-md-6">
                        <input type="file" name="store_logo" accept=".png, .jpg, .jpeg" id="store_logo" class="form-control store_logo">
                        <span>Minimum image size 250*250</span>
                    </div>
                    <div class="col-md-6 text-center">
                        <button class="btn btn-primary btn-block upload-shop-image">Upload Image</button>
                        <div class="alert alert-success" id="upload-success" style="display: none;margin-top:10px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function () {
    var same_time = "{{ old('same_time') }}";
    if (same_time == "1") {
        jQuery(".same_time_cls").hide();
        jQuery(".same_time_second").show();
    } else {
        jQuery(".same_time_second").hide();
        jQuery(".same_time_cls").show();
    }

    jQuery('#same_time1').change(function () {
        if (jQuery(this).prop('checked')) {
            jQuery(".same_time_cls").hide();
            jQuery(".same_time_second").show();
        } else {
            jQuery(".same_time_second").hide();
            jQuery(".same_time_cls").show();
        }
    });

    jQuery('.same_start_time').daterangepicker({
        singleDatePicker: true,
        datePicker: false,
        timePicker: true,
        opens: 'right',
        locale: {
            format: 'HH:mm:ss A'
        }
    }).on('show.daterangepicker', function (ev, picker) {
        picker.container.find(".calendar-table").hide();
    });

    jQuery('.same_end_time').daterangepicker({
        singleDatePicker: true,
        datePicker: false,
        timePicker: true,
        opens: 'right',
        locale: {
            format: 'HH:mm:ss A'
        }
    }).on('show.daterangepicker', function (ev, picker) {
        picker.container.find(".calendar-table").hide();
    });
});



var telInput = $("#phone_number"),
        errorMsg = $("#error-msg"),
        validMsg = $("#valid-msg");

telInput.on("countrychange", function () {
    var country_code_t = $("#phone_number").intlTelInput("getSelectedCountryData").iso2;
    jQuery(".country_code").val(country_code_t);

    var code = jQuery('.selected-dial-code').text();
    var country_name = jQuery('.selected-flag').attr('title');
    jQuery(".contact_code").val(code);
    jQuery(".country_name").val(country_name);
});

// initialise plugin
telInput.intlTelInput({
    allowExtensions: true,
    formatOnDisplay: true,
    autoFormat: true,
    autoHideDialCode: true,
    autoPlaceholder: true,
    defaultCountry: "auto",
    ipinfoToken: "yolo",
    nationalMode: false,
    numberType: "MOBILE",
    //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
    preferredCountries: ['sa', 'ae', 'qa', 'om', 'bh', 'kw', 'ma'],
    preventInvalidNumbers: true,
    separateDialCode: true,
    initialCountry: "us",
    geoIpLookup: function (callback) {
        jQuery.get("http://ipinfo.io", function () {}, "jsonp").always(function (resp) {
            var countryCode = (resp && resp.country) ? resp.country : "";
            callback(countryCode);
        });
    },
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
});
var reset = function () {
    telInput.removeClass("error");
    jQuery(".error_code").hide();
    errorMsg.addClass("hide");
    validMsg.addClass("hide");
};
// on blur: validate
telInput.blur(function () {
    reset();
    if (jQuery.trim(telInput.val())) {
        if (telInput.intlTelInput("isValidNumber")) {
            validMsg.removeClass("hide");
            jQuery(".error_code").hide();
        } else {
            telInput.addClass("error");
            jQuery(".error_code").show();
            errorMsg.removeClass("hide");
        }
    }
});
// on keyup / change flag: reset
telInput.on("keyup change", reset);
</script>
<script type="text/javascript">
    jQuery.validator.addMethod("phoneno", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 6 &&
                phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
    }, "<br />Please specify a valid phone number");

    jQuery(document).ready(function () {
        jQuery("#phone_number").on('change', function () {
            var code = jQuery('.selected-dial-code').text();
            var country_name = jQuery('.selected-flag').attr('title');
            jQuery(".contact_code").val(code);
            jQuery(".country_name").val(country_name);
        });

        jQuery(".country").on("click", function () {
            var countryCode = jQuery(this).attr("data-country-code");
            jQuery(".country_code").val(countryCode);
        });
    });
</script>

<script type="text/javascript">
    //CKEDITOR.replace('shop_description');
    CKEDITOR.replace('description', {
        filebrowserUploadUrl: "{{route('admin.store.ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    $(document).ready(function () {
        $('#category_id').select2({
            placeholder: "Please Select",
            allowClear: true,
            allowHtml: true
        });
        $(".reset_form").on("click", function () {
            location.reload();
        });
    });

    /*
     * Store Logo
     */
    jQuery(document).ready(function () {
        var resize = jQuery('#upload-shop-logo').croppie({
            enableExif: true,
            enableOrientation: true, viewport: {
                width: 250,
                height: 250,
                type: 'square'
            },
            boundary: {
                width: 300,
                height: 300
            }
        });
        $crop_vendor_logo_cls = jQuery('.crop_vendor_logo_cls').faModal();

        jQuery('.store_logo').on('change', function () {
            $crop_vendor_logo_cls.faModal('show');
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

        jQuery('.upload-shop-image').on('click', function (ev) {
            resize.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (img) {
                jQuery.ajax({
                    url: "{{ route('admin.store.logo') }}",
                    type: "POST",
                    data: {_token: '{{ csrf_token() }}', "image": img},
                    success: function (data) {
                        jQuery("#store_logo_cls").attr('src', data.file_name);
                        jQuery("#store_logo_db").val(data.file_name_db);
                        $crop_vendor_logo_cls.faModal('hide');
                    }
                });
            });
        });
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        var t = 1;
        jQuery('.add_tc_row').on('click', function () {
            var row = '<tr><td align="top"><select name="day_name[]" class="form-control"><option value="">Day Name</option><option value="1">Monday</option><option value="2">Tuesday</option><option value="3">Wednesday</option><option value="4">Thursday</option><option value="5">Friday</option><option value="6">Saturday</option><option value="7">Sunday</option></select></td><td align="top"><input type="text" name="start_time[]" value="" placeholder="Please select start time" class="form-control start_time"></td><td align="top"><input type="text" name="end_time[]" value="" placeholder="Please select end time" class="form-control end_time"></td><td align="top"><a href="javascript:void(0);" class="remove_row_tc" data-id=""><i class="fas fa-minus"></i></a></td></tr>';
            t++;
            jQuery('.append_term_row').append(row);

            jQuery('.start_time').daterangepicker({
                singleDatePicker: true,
                datePicker: false,
                timePicker: true,
                opens: 'right',
                locale: {
                    format: 'HH:mm:ss A'
                }
            }).on('show.daterangepicker', function (ev, picker) {
                picker.container.find(".calendar-table").hide();
            });

            jQuery('.end_time').daterangepicker({
                singleDatePicker: true,
                datePicker: false,
                timePicker: true,
                opens: 'right',
                locale: {
                    format: 'HH:mm:ss A'
                }
            }).on('show.daterangepicker', function (ev, picker) {
                picker.container.find(".calendar-table").hide();
            });
        });

        jQuery(document).on("click", ".remove_row_tc", function () {
            var tr = jQuery(this).closest('tr');
            tr.remove();
            t--;
        });


        jQuery('.start_time').daterangepicker({
            singleDatePicker: true,
            datePicker: false,
            timePicker: true,
            opens: 'right',
            locale: {
                format: 'HH:mm:ss A'
            }
        }).on('show.daterangepicker', function (ev, picker) {
            picker.container.find(".calendar-table").hide();
        });

        jQuery('.end_time').daterangepicker({
            singleDatePicker: true,
            datePicker: false,
            timePicker: true,
            opens: 'right',
            locale: {
                format: 'HH:mm:ss A'
            }
        }).on('show.daterangepicker', function (ev, picker) {
            picker.container.find(".calendar-table").hide();
        });
    });
</script>

<script type="text/javascript">
    function initAutoComplete() {
        let autoDom = document.getElementById("store_address");
        const autoCompleteObj = new google.maps.places.Autocomplete(autoDom);
        autoCompleteObj.addListener("place_changed", () => {
            console.log('geometry', autoCompleteObj.getPlace().address_components);

        });
    }
</script>
<?php
$googleMapKey = Helper::googleServiceKeys('google-map-key');
?>
<script src = "https://maps.googleapis.com/maps/api/js?libraries=places&key={{ $googleMapKey }}&callback=initAutoComplete" ></script>
@endsection

