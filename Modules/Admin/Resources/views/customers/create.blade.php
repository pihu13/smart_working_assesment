@extends('admin::layouts.master')
@section('admin::content')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/css/intlTelInput.css">
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Add New Dcotor</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.customers.list')}}">Dcotor Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add New Dcotor</li>
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
                <form class="box bg-white" id="add-customer" action="{{route('admin.add.customer.post')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="box-row flex-wrap">
                        <div class="profile-information mb-3">
                            <div class="user-icon">
                                <img src="{{asset('/storage/uploads/users/avatar.png')}}" id="profile_image_cls" class="profile_image_cls" alt="img">
                                <div class="img-upload">
                                    <input type="file" class="file" name="photo" id="profile-img">
                                    <label for="profile-img"><i class="fal fa-camera"></i></label>
                                </div>
                            </div>
                            @if ($errors->has('photo'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('photo') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Full Name <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" minlength="1" maxlength="25" placeholder="Full Name" name="name" value="{{old('name')}}">
                                    @if ($errors->has('name'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Email Address <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="email" class="form-control" minlength="6" maxlength="100" name="email" placeholder="Email" value="{{old('email')}}">
                                    @if ($errors->has('email'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                       <!-- <div class="col-md-6 mb-3 phone_number_sec">
                            <div class="form-group">
                                <label>Phone Number <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="tel" class="form-control" id="phone_number" minlength="4" maxlength="14" autocomplete="off" name="phone_number" placeholder="Phone Number" value="{{ old('phone_number' )}}">
                                    <label for="phone_number" style="color: red;display:none;" class="error_code">Phone number is invalid.</label>
                                    <input type="hidden" class="contact_code"  name="contact_code" value="{{ (@old('contact_code' ))?old('contact_code' ):"+1" }}">
                                    <input type="hidden" class="country_name"  name="country_name" value="{{ (@old('country_name' ))?old('country_name' ):"United States: +1" }}">
                                    <input type="hidden" class="country_code" name="country_code" value="{{ (@old('country_code' ))?old('country_code' ):"us" }}">
                                    @if ($errors->has('phone_number'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('phone_number') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Street Address</label>
                                <div class="input-group">
                                    <input type="text" class="form-control googlemapshown1" id="street_address" maxlength="255" name="street_address" placeholder="Street Address" value="{{old('street_address')}}">
                                    @if ($errors->has('street_address'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('street_address') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Apartment/Unit Number</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="apartment_unit" maxlength="50" name="apartment_unit" placeholder="Apartment Unit" value="{{old('apartment_unit')}}">
                                    @if ($errors->has('apartment_unit'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('apartment_unit') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>City</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="city" maxlength="50" name="city" placeholder="City" value="{{old('city')}}">
                                    @if ($errors->has('city'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('city') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>State</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="state" maxlength="50" name="state" placeholder="State" value="{{old('state')}}">
                                    @if ($errors->has('state'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('state') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Country</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="country" maxlength="50" name="country" placeholder="Country" value="{{old('country')}}">
                                    @if ($errors->has('country'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('country') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Zip Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="zip_code" maxlength="50" name="zip_code" placeholder="Zip Code" value="{{old('zip_code')}}">
                                    @if ($errors->has('zip_code'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('zip_code') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Password <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="Password" class="form-control" id="password" minlength="6" maxlength="12" name="password" placeholder="Password" value="{{old('password')}}">
                                    @if ($errors->has('password'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Confirm Password <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="Password" class="form-control" minlength="6" maxlength="12" name="confirm_password" placeholder="Confirm Password" value="{{old('confirm_password')}}">
                                    @if ($errors->has('confirm_password'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('confirm_password') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>-->


                      
                        <?php /*
                          <div class="col-md-6 mb-3">
                          <div class="form-group">
                          <label>Gender <span style="color:red;">*</span></label>
                          <div class="form-check form-check-inline form-check-sm mr-2">
                          <input type="radio"  class="form-check-input" id="inline-radio1" name="gender" value="1" <?php echo (old("gender") == "1") ? 'checked="checked"' : ""; ?>>
                          <label class="form-check-label" for="inline-radio1">Male</label>
                          </div>
                          <div class="form-check form-check-inline form-check-sm mr-2">
                          <input type="radio" class="form-check-input" id="inline-radio2"" name="gender" value="2" <?php echo (old("gender") == "2") ? 'checked="checked"' : ""; ?>>
                          <label class="form-check-label" for="inline-radio2">Female</label>
                          </div>
                          <div class="form-check form-check-inline form-check-sm mr-2">
                          <input type="radio" class="form-check-input" id="inline-radio3"" name="gender" value="3" <?php echo (old("gender") == "3") ? 'checked="checked"' : ""; ?>>
                          <label class="form-check-label" for="inline-radio3">Other</label>
                          </div>
                          @if ($errors->has('gender'))
                          <div class="invalid-feedback" style="display:block;">{{ $errors->first('gender') }}</div>
                          @endif
                          </div>
                          </div>
                         */ ?>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Status <span style="color:red;">*</span></label>
                                <div class="form-check form-check-inline form-check-sm mr-2">
                                    <input type="radio"  class="form-check-input" id="inline-radio4" name="status" value="1" <?php echo (old("status") == "1") ? 'checked="checked"' : ""; ?>>
                                    <label class="form-check-label" for="inline-radio4">Active</label>
                                </div>
                                <div class="form-check form-check-inline form-check-sm mr-2">
                                    <input type="radio" class="form-check-input" id="inline-radio5" name="status" value="0" <?php echo (old("status") == "0") ? 'checked="checked"' : ""; ?>>
                                    <label class="form-check-label" for="inline-radio5">Inactive</label>
                                </div>
                                @if ($errors->has('status'))
                                <div class="invalid-feedback" style="display:block;">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 mb-3 text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-light reset_form">Cancel</button>
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

    $(document).ready(function () {
        $('#username').keyup(function () {
            $(this).val($(this).val().replace(/ +?/g, ''));
        });

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

        jQuery("#add-customer").validate({
            rules: {
                name: "required",
                email: {
                    required: true,
                    email: true
                },
                phone_number: {
                    required: true,
                    phoneno: true,
                    minlength: 6,
                    maxlength: 12
                },
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
                name: "Please enter full name",
                email: {
                    required: "Please enter email address",
                    email: "Email address must be in the format of name@domain.com"
                },
                phone_number: {
                    required: "Please enter phone number",
                    phoneno: "Phone number must be in the format."
                },
                password: "Please enter password",
                confirm_password: "Please enter confirm password same as password",
            }
        });

        $(".reset_form").on("click", function () {
            window.location.href = '<?php echo route('admin.customers.list'); ?>';
        });

        $("#profile-img").change(function () {
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

<script type="text/javascript">
    let autocomplete;
    let address1Field;
    function initAutocomplete() {
        address1Field = document.querySelector("#street_address");
        autocomplete = new google.maps.places.Autocomplete(address1Field, {
            componentRestrictions: {country: []},
            fields: ["address_components"],
        });
        autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
        const place = autocomplete.getPlace();
        let address1 = "";
        for (const component of place.address_components) {
            const componentType = component.types[0];
            switch (componentType) {
                case "street_number":
                {
                    address1 = `${component.long_name} ${address1}`;
                    break;
                }

                case "route":
                {
                    address1 += component.short_name;
                    break;
                }

                case "postal_code":
                {
                    document.querySelector("#zip_code").value = component.long_name;
                    break;
                }

                case "postal_code_suffix":
                {

                    break;
                }
                case "locality":
                    document.querySelector("#city").value = component.long_name;
                    break;

                case "administrative_area_level_1":
                {
                    document.querySelector("#state").value = component.short_name;
                    break;
                }
                case "country":
                    document.querySelector("#country").value = component.long_name;
                    break;
            }
        }
    }
</script>
<?php
$googleMapKey = Helper::googleServiceKeys('google-map-key');
?>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key={{ $googleMapKey }}&callback=initAutocomplete"></script>
@endsection

