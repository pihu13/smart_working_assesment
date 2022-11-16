<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/daterangepicker.css') }}">
<script type="text/javascript" src="{{ asset('/admin/js/jquery.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/admin/js/bootstrap.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/script.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/moment.min.js') }}"></script>

<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/dataTables.bootstrap4.min.css') }}" />

<script type="text/javascript" src="{{ asset('/admin/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/dataTables.bootstrap4.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/admin/js/daterangepicker.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/admin/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('/admin/js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/jquery.faModal.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/croppie.js') }}"></script>

<script type="text/javascript" src="{{ asset('/admin/js/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/bootstrap-colorpicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/jquery.geocomplete.min.js') }}"></script>

<?php
$currentRouteName = \Route::current()->getName();
if (!in_array($currentRouteName, ["admin.add.customer","admin.edit.customer","admin.add.shop","admin.edit.shop"])) {
    $googleMapKey = Helper::googleServiceKeys('google-map-key');
    ?>
    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key={{ $googleMapKey }}"></script>
    <?php
}
?>

<script type="text/javascript" src="{{ asset('/admin/js/sweetalert-dev.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/lightgallery.min.js') }}"></script>