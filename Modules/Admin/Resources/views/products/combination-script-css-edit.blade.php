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
                url: '{{ route("admin.edit.product.variables") }}',
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
                url: '{{ route("admin.edit.product.variables.post") }}',
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
                        jQuery(".appned_variation_sec").html(response.data);

                        setTimeout(function () {
                            swal.close();
                        }, 2000);
                    } else if (response.status == "501") {
                        jQuery.each(response.errors, function (i, file) {
                            jQuery('#info_v_' + i).after('<span class="fee_error">' + file + '</span>');
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


        /*
         * Variable Option 
         */
        jQuery(document).on("click", ".add_variable_option", function () {
            var dataID = jQuery(this).attr("data-id");
            jQuery.ajax({
                url: '{{ route("admin.edit.product.variable.option") }}',
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
                url: '{{ route("admin.edit.product.variable.option.post") }}',
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
                        jQuery(".appned_variation_sec").html(response.data);
                    } else if (response.status == "501") {
                        jQuery.each(response.errors, function (i, file) {
                            jQuery('#info_o_' + i).after('<span class="fee_error">' + file + '</span>');
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


        /*
         *  Add Product Combinations
         */

        jQuery(document).on("click", ".add_pro_combination", function () {
            var dataID = jQuery(this).attr("data-id");
            jQuery.ajax({
                url: '{{ route("admin.edit.product.combination") }}',
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
                url: '{{ route("admin.edit.product.combination.post") }}',
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
                        jQuery(".appned_variation_sec").html(response.data);
                    } else if (response.status == "501") {
                        jQuery.each(response.errors, function (i, file) {
                            jQuery('#com_' + i).after('<span class="fee_error">' + file + '</span>');
                        });
                    } else if (response.status == "201") {
                        jQuery(".com_product_combination_already").html('<span class="fee_error">' + response.msg + '</span>');
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


        /*
         * Update Variable Status 
         */
        jQuery(document).on("click", ".status_update", function () {
            var status = jQuery(this).attr("data-status");
            var slug = jQuery(this).attr("data-slug");
            var this_id = jQuery(this);
            jQuery.ajax({
                url: '{{ route("admin.product.variation.status") }}',
                type: "POST",
                data: {_token: '{{ csrf_token() }}', status: status, slug: slug},
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    if (response.status == "200") {
                        jQuery(this_id).attr("data-status", "0");
                        jQuery(this_id).html('<span class="badge badge-success">Active</span>');
                        swal("Done!", "Status update successfully!", "success");
                    } else if (response.status == "201") {
                        jQuery(this_id).attr("data-status", "1");
                        jQuery(this_id).html('<span class="badge badge-danger">Inactive</span>');
                        swal("Done!", "Status update successfully!", "success");
                    } else {
                        swal("Error deleting!", "Please try again", "error");
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
         * Update Combination Data 
         */

        jQuery(document).on("click", ".edit_combilation_cls", function () {
            var dataID = jQuery(this).attr("data-id");
            jQuery.ajax({
                url: '{{ route("admin.update.product.combination") }}',
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
                        jQuery("#info_com_price").focus();
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

        jQuery(".combinationpopup").on("submit", "#combination_update", function (e) {
            e.preventDefault();
            var data = jQuery("#combination_update").serialize();
            jQuery.ajax({
                url: '{{ route("admin.update.product.combination.post") }}',
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

                        jQuery(".appned_variation_sec").html(response.data);

                        setTimeout(function () {
                            swal.close();
                        }, 2000);
                    } else if (response.status == "501") {
                        jQuery.each(response.errors, function (i, file) {
                            jQuery('#info_com_' + i).after('<span class="fee_error">' + file + '</span>');
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

    });

</script>