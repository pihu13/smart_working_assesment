<style type="text/css">
    .fee_error{
        font-size: 14px;
        color: red;
        margin-top: 5px;
        display: block;
    }
</style>
<div class="fa-modal my-modal combinationpopup" style="width:50%;">
    <div class="modal-wrap">
        <div class="fa-modal__close-btn"></div>
        <div class="compare-block combination_data_app">

        </div>
    </div>    
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        /*
         * Add Variable
         */
        $modalcombinationpopup = jQuery('.combinationpopup').faModal();
        jQuery(document).on("click", ".add_veriable", function () {
            var dataID = jQuery(this).attr("data-id");
            jQuery.ajax({
                url: '{{ route("admin.add.product.variables") }}',
                type: "POST",
                data: {_token: '{{ csrf_token() }}', id: dataID},
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    $modalcombinationpopup.faModal('show');
                    if (response.status == "200") {
                        jQuery('.combination_data_app').html(response.msg);
                        jQuery("#info_add_variant_name").focus();
                        jQuery("#info_add_variant_name").select2({
                            placeholder: "Please enter variant name and press keyboard enter button",
                            tags: true
                        });
                    } else {
                        swal("Error deleting!", response.msg, "error");
                    }
                    jQuery(".loading-box").hide();
                },
                error: function (xhr) {
                    swal("Error deleting!", "Please try again", "error");
                    jQuery(".loading-box").hide();
                },
                complete: function () {
                    jQuery(".loading-box").hide();
                }
            });
        });

        jQuery(".combination_data_app").on("submit", "#frm_add_vari_cls", function (e) {
            e.preventDefault();
            var data = jQuery("#frm_add_vari_cls").serialize();
            jQuery.ajax({
                url: '{{ route("admin.add.product.variables.post") }}',
                type: "POST",
                data: data,
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    jQuery(".fee_error").hide();

                    if (response.status == "200") {
                        $modalcombinationpopup.faModal('hide');
                        swal("Done!", response.msg, "success");

                        setTimeout(function () {
                            swal.close();
                        }, 2000);
                        addProductParcially();
                    } else if (response.status == "501") {
                        jQuery.each(response.errors, function (i, file) {
                            jQuery('#info_v_' + i).after('<span class="fee_error">' + file + '</span>');
                        });
                    } else if (response.status == "201") {
                        swal("Error deleting!", response.msg, "error");
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
                        addProductParcially();
                    } else {
                        swal("Error deleting!", response.msg, "error");
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
                        addProductParcially();
                    }
                    jQuery(".loading-box").hide();
                },
                error: function (xhr) {
                    swal("Error deleting!", "Please try again", "error");
                    jQuery(".loading-box").hide();
                },
                complete: function () {
                    jQuery(".loading-box").hide();
                }
            });
        });


        /*
         * Variable Option 
         */
        jQuery(document).on("click", ".add_variable_option", function () {
            var dataID = jQuery(this).attr("data-id");
            jQuery.ajax({
                url: '{{ route("admin.add.product.variable.option") }}',
                type: "POST",
                data: {_token: '{{ csrf_token() }}', id: dataID},
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    $modalcombinationpopup.faModal('show');
                    if (response.status == "200") {
                        jQuery('.combination_data_app').html(response.msg);
                        jQuery("#info_op_option_name").focus();
                        jQuery("#info_op_option_name").select2({
                            placeholder: "Please enter variant option and press keyboard enter button",
                            tags: true
                        });
                    } else {
                        swal("Error deleting!", response.msg, "error");
                        setTimeout(function () {
                            swal.close();
                        }, 2000);
                    }
                    jQuery(".loading-box").hide();
                },
                error: function (xhr) {
                    swal("Error deleting!", "Please try again", "error");
                    jQuery(".loading-box").hide();
                },
                complete: function () {
                    jQuery(".loading-box").hide();
                }
            });
        });

        jQuery(".combinationpopup").on("submit", "#frm_variable_option", function (e) {
            e.preventDefault();
            var data = jQuery("#frm_variable_option").serialize();
            jQuery.ajax({
                url: '{{ route("admin.add.product.variable.option.post") }}',
                type: "POST",
                data: data,
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    jQuery(".fee_error").hide();

                    if (response.status == "200") {
                        $modalcombinationpopup.faModal('hide');
                        swal("Done!", response.msg, "success");

                        addProductParcially();
                    } else if (response.status == "501") {
                        jQuery.each(response.errors, function (i, file) {
                            jQuery('#info_o_' + i).after('<span class="fee_error">' + file + '</span>');
                        });
                    } else if (response.status == "201") {
                        swal("Error deleting!", response.msg, "error");
                        addProductParcially();
                    } else {
                        swal("Error deleting!", response.msg, "error");
                        addProductParcially();
                    }
                    jQuery(".loading-box").hide();
                },
                error: function (xhr) {
                    swal("Error deleting!", "Please try again", "error");
                    jQuery(".loading-box").hide();
                },
                complete: function () {
                    jQuery(".loading-box").hide();
                }
            });
        });


        /*
         *  Add Product Combinations
         */

        jQuery(document).on("click", ".add_pro_combination", function () {
            var dataID = jQuery(this).attr("data-id");
            jQuery.ajax({
                url: '{{ route("admin.add.product.combination") }}',
                type: "POST",
                data: {_token: '{{ csrf_token() }}', id: dataID},
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    $modalcombinationpopup.faModal('show');
                    if (response.status == "200") {
                        jQuery('.combination_data_app').html(response.msg);
                        jQuery("#com_price").focus();
                    } else {
                        swal("Error deleting!", response.msg, "error");
                        addProductParcially();
                    }
                    jQuery(".loading-box").hide();
                },
                error: function (xhr) {
                    swal("Error deleting!", "Please try again", "error");
                    jQuery(".loading-box").hide();
                },
                complete: function () {
                    jQuery(".loading-box").hide();
                }
            });
        });

        jQuery(".combinationpopup").on("submit", "#form_add_combination", function (e) {
            e.preventDefault();
            var data = jQuery("#form_add_combination").serialize();
            jQuery.ajax({
                url: '{{ route("admin.add.product.combination.post") }}',
                type: "POST",
                data: data,
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    jQuery(".fee_error").hide();
                    if (response.status == "200") {
                        $modalcombinationpopup.faModal('hide');
                        swal("Done!", response.msg, "success");

                        addProductParcially();
                    } else if (response.status == "501") {
                        jQuery.each(response.errors, function (i, file) {
                            jQuery('#com_' + i).after('<span class="fee_error">' + file + '</span>');
                        });
                    } else if (response.status == "201") {
                        jQuery(".com_product_combination_already").html('<span class="fee_error">' + response.msg + '</span>');
                    } else {
                        swal("Error deleting!", response.msg, "error");
                        addProductParcially();
                    }
                    jQuery(".loading-box").hide();
                },
                error: function (xhr) {
                    swal("Error deleting!", "Please try again", "error");
                    jQuery(".loading-box").hide();
                },
                complete: function () {
                    jQuery(".loading-box").hide();
                }
            });
        });

    });

</script>