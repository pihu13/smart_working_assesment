@extends('admin::layouts.master')
@section('admin::content')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/css/intlTelInput.css">
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Edit Profile / Change Password</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-12 col-md-4 mb-4">
                @if (isset($errors) && count($errors) > 0)
                <div class="alert alert-danger">
                    <ul style="list-style: none;">
                        @foreach ($errors->all() as $error)
                        <li>-{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if ($message = Session::get('success'))
                <div class="alert alert-success" role="alert">
                    <p>{{ $message }}</p>
                </div>
                @endif
            </div>

            <div class="col-lg-6 col-md-4 mb-4">
                <form class="box bg-white" action="{{route('admin.update.admin')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="slug" value="{{ $adminData->slug }}" />
                    <div class="box-row flex-wrap">
                        <div class="profile-information mb-3">
                            <div class="user-icon">
                                <img src="{{asset('/storage/'.@$adminData->profile_photo)}}" id="profile_image_cls" alt="img">
                                <div class="img-upload">
                                    <input type="file" class="file" name="photo" id="profile-img">
                                    <label for="profile-img"><i class="fal fa-camera"></i></label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="old_photo" id="old_photo" value="{{ @$adminData->profile_photo }}">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label>User Name <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" minlength="3" maxlength="50" id="username" name="username" placeholder="User Name" value="{{$adminData->username}}">
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
                                    <input type="text" class="form-control" minlength="3" max="45" name="first_name" placeholder="First Name" value="{{$adminData->first_name}}">
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
                                    <input type="text" class="form-control" minlength="3" max="45" placeholder="Last Name" name="last_name" value="{{$adminData->last_name}}">
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
                                    <input type="email" class="form-control" minlength="6" max="100" name="email" placeholder="Email" value="{{$adminData->email}}">
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
                                    <input type="tel" class="form-control" id="phone_number" minlength="4" maxlength="14" autocomplete="off" name="phone_number" placeholder="Phone Number" value="{{ (isset($adminData->phone_number) && !empty($adminData->phone_number))?$adminData->phone_number:"" }}">
                                    <label for="phone_number" style="color: red;display: none;" class="error_code">Phone number is invalid.</label>
                                    <input type="hidden" class="contact_code"  name="contact_code" value="{{ (@$adminData->country_std_code)?@$adminData->country_std_code:"+44" }}">
                                    <input type="hidden" class="country_name"  name="country_name" value="{{ (@$adminData->country_name)?@$adminData->country_name:"United Kingdom" }}">
                                    <input type="hidden" class="country_code" name="country_code" value="{{ (@$adminData->country_code)?@$adminData->country_code:"gb" }}">
                                    @if ($errors->has('phone_number'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('phone_number') }}</div>
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
            <div class="col-lg-6 col-md-4 mb-4">
                <form class="box bg-white" action="{{route('admin.change.password')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="slug" value="{{$adminData->slug}}" />
                    <div class="box-row flex-wrap">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label>Old Password <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="old_password" placeholder="Old Password" value="{{ old('old_password') }}">
                                    @if ($errors->has('old_password'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('old_password') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label>Password <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" minlength="6" maxlength="50" placeholder="Password" name="password" value="{{old('password')}}">
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
                                    <input type="password" class="form-control" minlength="6" maxlength="50" placeholder="Confirm Password" name="confirm_password" value="{{old('confirm_password')}}">
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
    initialCountry: "{{ (@$adminData->country_code)?@$adminData->country_code:'gb' }}",
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
    jQuery(document).ready(function () {
        jQuery('#username').keyup(function () {
            jQuery(this).val(jQuery(this).val().replace(/ +?/g, ''));
        });

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

        jQuery(".reset_form").on("click", function () {
            window.location.href = '<?php echo route('admin.dashboard'); ?>';
        });

        jQuery("#profile-img").change(function () {
            profileImgReadURL(this);
        });
    });

    function profileImgReadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#profile_image_cls').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
