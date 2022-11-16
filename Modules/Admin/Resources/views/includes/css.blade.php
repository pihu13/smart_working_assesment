@yield('links')
<?php
$faviconIcon = Helper::getGeneralSettingLogo("favicon-icon");
if (@$faviconIcon["option_value"] && !empty($faviconIcon["option_value"])) {
    ?>
    <link rel="shortcut icon" href="{{ asset('/storage/'.$faviconIcon["option_value"]) }}" type="image/x-icon">
<?php } else {
    ?>
    <link rel="shortcut icon" href="{{ asset('/admin/images/favicon.ico') }}" type="image/x-icon">
<?php } ?>

<link href="{{ asset('/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('/admin/css/fontawesome.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('/admin/css/toastr.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('/admin/css/select2.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('/admin/css/jquery-ui.css') }}">
<!--    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">-->
<link href="{{ asset('/admin/css/sweetalert.css') }}" rel="stylesheet" type="text/css" media="all">
<link href="{{ asset('/admin/css/loader-min.css') }}" rel="stylesheet" type="text/css" media="all">
<link href="{{ asset('/admin/css/bootstrap-colorpicker.css') }}" rel="stylesheet" type="text/css" media="all">
<link href="{{ asset('/admin/css/jquery.faModal.css') }}" rel="stylesheet" type="text/css" media="all">
<link href="{{ asset('/admin/css/croppie.min.css') }}" rel="stylesheet" type="text/css" media="all">

<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/lightgallery.css') }}" />

<link  rel="stylesheet" href="{{ asset('/admin/css/style.css' ) }}" type="text/css">
<link  rel="stylesheet" href="{{ asset('/admin/css/admin-custom.css' ) }}" type="text/css">
