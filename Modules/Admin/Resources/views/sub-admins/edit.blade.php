@extends('admin::layouts.master')
@section('admin::content')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/css/intlTelInput.css">
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Edit Subadmin</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.sub.admins') }}">Sub-admin Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sub-admin</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-12 col-md-4 mb-4">
                @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul style="list-style: none;">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                @endif

                @if ($message = Session::get('success'))    
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success</strong> {{ $message }}
                    <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                @endif
            </div>
            <div class="col-lg-12 col-md-4 mb-4">
                <form class="box bg-white" id="edit-sub-admin" action="{{route('admin.edit.sub.admin.post')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="slug" value="{{$user->slug}}" />
                    <input type="hidden" name="user_id" value="{{$user->id}}" />
                    <div class="box-row flex-wrap">
                        <div class="profile-information mb-3">
                            <div class="user-icon">
                                <?php
                                if (!empty($user->profile_photo)) {
                                    $img = @$user->profile_photo;
                                } else {
                                    $img = "uploads/users/avatar.png";
                                }
                                ?>
                                <img src="{{ asset("/storage/".$img) }}" class="profile_image_cls" id="profile_image_cls" alt="img">
                                <input type="hidden" name="photo_old" value="{{ @$img }}">

                                <div class="img-upload">
                                    <input type="file" class="file" name="photo" id="profile-img">
                                    <label for="profile-img"><i class="fal fa-camera"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label>User Name <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" minlength="1" maxlength="50" id="username" name="username" placeholder="User Name" value="{{$user->username}}">
                                    @if ($errors->has('username'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('username') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>First Name <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" minlength="1" maxlength="25" id="first_name" name="first_name" placeholder="First Name" value="{{$user->first_name}}">
                                    @if ($errors->has('first_name'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('first_name') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Last Name <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" minlength="1" maxlength="25" id="last_name" placeholder="Last Name" name="last_name" value="{{$user->last_name}}">
                                    @if ($errors->has('last_name'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('last_name') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label>Email Address <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="email" class="form-control" minlength="1" maxlength="100" id="email" name="email" placeholder="Email" value="{{$user->email}}">
                                    @if ($errors->has('email'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3 phone_number_sec">
                            <div class="form-group">
                                <label>Phone Number <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="tel" class="form-control" id="phone_number" minlength="4" maxlength="14" name="phone_number" placeholder="Phone Number" value="{{ (isset($user->phone_number) && !empty($user->phone_number))?$user->phone_number:"" }}">
                                    <label for="phone_number" style="color: red" class="error_code">Phone number is invalid.</label>
                                    <input type="hidden" class="contact_code" id="contact_code" name="contact_code" value="{{ @$user->country_std_code }}">
                                    <input type="hidden" class="country_name" name="country_name" value="{{ @$user->country_name }}">
                                    <input type="hidden" class="country_code" name="country_code" value="{{ @$user->country_code }}">
                                    @if ($errors->has('phone_number'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('phone_number') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <?php
                        $userPrPermitn = [];
                        if (@$userPr && count($userPr) > 0) {
                            foreach ($userPr as $key => $userPrEach) {
                                if ($key != "default") {
                                    $userPrPermitn[] = $key;
                                }
                            }
                        }
                        ?>

                        <div class="col-md-12">
                            <div class="form-group select2cuscls">
                                <label for="text-input">Permission</label>
                                <select name="permission_name[]" id="permission_name" class="permission_name form-control" multiple="multiple">
                                    <?php
                                    if (@$perArr && count($perArr) > 0) {
                                        foreach ($perArr as $key => $val) {
                                            if ($key != "default") {
                                                ?>
                                                <option value="{{ $key }}" <?php echo (in_array($key, $userPrPermitn)) ? 'selected="selected"' : ""; ?>>
                                                    {{ ucwords($key) }}
                                                </option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                @if ($errors->has('permission_name'))
                                <div class="invalid-feedback" style="display:block;">{{ $errors->first('permission_name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label>Status <span style="color:red;">*</span></label>
                                <div class="form-check form-check-inline form-check-sm mr-2">
                                    <input type="radio"  class="form-check-input" id="inline-radio1" name="status" value="1" {{ ($user->status=="1")? "checked" : "" }}  />
                                    <label class="form-check-label" for="inline-radio1">Active</label>
                                </div>
                                <div class="form-check form-check-inline form-check-sm mr-2">
                                    <input type="radio" class="form-check-input" id="inline-radio2"" name="status" value="0" {{ ($user->status=="0")? "checked" : "" }} />
                                    <label class="form-check-label" for="inline-radio2">Inactive</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3 text-center">
                            <button type="button" class="btn btn-light reset_form">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-12 col-md-4 mb-4">
                <form class="box bg-white" id="sub-admin-change-password" action="{{route('admin.sub.admin.change.password')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="slug" value="{{$user->slug}}" />
                    <input type="hidden" name="email" value="{{$user->email}}" />

                    <div class="box-row flex-wrap">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label>Password <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" minlength="6" maxlength="15" placeholder="Password" name="password" value="{{old('password')}}">
                                    @if ($errors->has('password'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label>Confirm Password <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" minlength="6" maxlength="15" placeholder="Confirm Password" name="confirm_password" value="{{old('confirm_password')}}">
                                    @if ($errors->has('confirm_password'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('confirm_password') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3 text-center">
                            <button type="button" class="btn btn-light reset_form">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<style type="text/css">
    .permission-cls {
        width: 100%;  
        padding: 0 15px;
    }
    .permission-cls h6 {
        font-size: 20px;
        font-weight: 600;
    }
    .permission-cls ul {
        padding: 0;
        margin: 0;
        list-style: none;
        width: 100%;
    }
    .permission-cls ul li {
        padding: 15px;
        border: 1px solid #ced4da;
    }
    .label-title {
        display: inline-block;
        font-size: 20px;
        border-bottom: 1px solid #ced4da;
        padding-bottom: 10px;
        font-weight: 600;
    }
    .permission-cls ul li.form-group .form-check-wrap input {
        float: left;
        width: 18px;
        margin-top: 5px;
        margin-right: 5px;
    }
    .permission-cls ul li.form-group .form-check-wrap {
        display: inline-block;
        width: 24%;
        vertical-align: middle;
    }
    .permission-cls ul li.form-group .form-check-wrap .form-check-label {
        font-size: 15px;
        display: inline;
        cursor: pointer;
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
    .form-check-wrap {
        display: inline-block;
        vertical-align: middle;
        padding: 0 20px 13px 0;
    }
    .form-group .form-check-wrap label {
        width: auto;
    }
    .form-check-wrap input[type="checkbox"], .form-check-wrap input[type="radio"] {
        vertical-align: middle;
    }
</style>
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"></script>

<script type="text/javascript">
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
    initialCountry: "{{ @$user->country_code }}",
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
        jQuery(".ckbCheckAll").click(function () {
            var ids = jQuery(this).attr("data-id");
            if (this.checked) {
                jQuery('.' + ids).each(function () {
                    jQuery(this).prop('checked', true);
                });
            } else {
                jQuery('.' + ids).each(function () {
                    jQuery(this).prop('checked', false);
                });
            }
        });


        jQuery("#permission_name").select2({
            placeholder: "Please Module",
            allowClear: true,
            allowHtml: true
        });


        jQuery('#username').keyup(function () {
            jQuery(this).val(jQuery(this).val().replace(/ +?/g, ''));
        });

        jQuery(".error_code").hide();

        jQuery(document).on('keyup', "#phone_number", function () {
            var code = jQuery('.selected-dial-code').text();
            var country_name = jQuery('.selected-flag').attr('title');
            jQuery(".contact_code").val(code);
            jQuery(".country_name").val(country_name);
        });

        jQuery(".country").on("click", function () {
            var countryCode = jQuery(this).attr("data-country-code");
            jQuery(".country_code").val(countryCode);
        });

        jQuery("#edit-sub-admin").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                email: {
                    required: true,
                    email: true
                },
                phone_number: {
                    required: true,
                    phoneno: true,
                    minlength: 4,
                    maxlength: 14
                }
            },
            messages: {
                first_name: "Please enter first name",
                last_name: "Please enter last name",
                email: {
                    required: "Please enter email address",
                    email: "Email address must be in the format of name@domain.com"
                },
                phone_number: {
                    required: "Please enter phone number",
                    phoneno: "Phone number must be in the format."
                }
            }
        });

        jQuery("#sub-admin-change-password").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 12
                },
                confirm_password: {
                    required: true,
                    minlength: 6,
                    maxlength: 12,
                    equalTo: '[name="password"]'
                }
            },
            messages: {
                password: "Please enter password",
                confirm_password: "Please enter confirm password same as password"
            }
        });

        jQuery(".reset_form").on("click", function () {
            window.location.href = '<?php echo route('admin.sub.admins'); ?>';
        });

        jQuery("#profile-img").change(function () {
            profileImgReadURL(this);
        });
    });

    function profileImgReadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                jQuery('#profile_image_cls').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection

