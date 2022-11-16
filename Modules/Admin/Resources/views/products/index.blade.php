@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Products</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Products</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <form class="box bg-white" method="GET" action="{{ route('admin.products.list') }}" files="true">
                    <div class="box-title pb-0">
                        <h5>Filter</h5>
                    </div>
                    <div class="d-flex flex-wrap align-items-end py-4">
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <label>Start Date</label>
                                <div class="input-group">
                                    <input type="text" id="start-date" name="start_date" value="{{ (@$requestData['start_date'])?$requestData['start_date']:"" }}" class="form-control" placeholder="Start Date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <label>End Date</label>
                                <div class="input-group">
                                    <input type="text" id="end-date" name="end_date" value="{{ (@$requestData['end_date'])?$requestData['end_date']:"" }}" class="form-control" placeholder="End Date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <label>Category</label>
                                <div class="input-group select2cuscls">
                                    <select name="category[]" id="category" class="category form-control" multiple="multiple">
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}" <?php echo (@in_array($category->id, (@$requestData['category']) ? $requestData['category'] : "")) ? 'selected="selected"' : ""; ?>>
                                            {{$category->name}}
                                        </option>
                                        @foreach ($category->children as $child)
                                        <option value="{{$child->childCat->id}}" <?php echo (@in_array($child->childCat->id, (@$requestData['category']) ? $requestData['category'] : "")) ? 'selected="selected"' : ""; ?>>
                                            -{{ $child->childCat->name }}
                                        </option>
                                        @if(count($child->childCat->children))
                                        @include('admin::products.search-recursive-category',['childs' => $child->childCat->children])
                                        @endif
                                        @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <label>Store</label>
                                <div class="input-group select2cuscls">
                                    <select name="store[]" id="store" class="store form-control" multiple="multiple">
                                        <?php
                                        if (@$shops && count($shops) > 0) {
                                            foreach ($shops as $shop) {
                                                ?>
                                                <option value="{{ @$shop->id }}" <?php echo (@in_array(@$shop->id, (@$requestData['store']) ? $requestData['store'] : "")) ? 'selected="selected"' : ""; ?>>
                                                    {{ @$shop->store_name }}
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-right">
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </div>
                        <div class="col-md-2 text-right">
                            <div class="form-group mb-0">
                                <button type="button" class="btn btn-primary w-100 reset-frm">Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success</strong> {{ $message }}
            <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        @endif
        @if ($message = Session::get('errors_catch'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error</strong> {{ $message }}
            <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        @endif
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">Product List
                <a href="{{route('admin.add.product')}}">
                    <span class="badge badge-primary float-right mr-2">Add Product</span>
                </a>
                <a href="{{route('admin.softdelete.products')}}">
                    <span class="badge badge-primary float-right mr-2">Soft Deleted Product List</span>
                </a>
                <a href="javascript:void(0);" id="datatable_reset">
                    <span class="badge badge-primary float-right mr-2">Reset Table Data</span>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <table id="dataTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="display:none;"></th>
                                        <th scope="col" class="sr-no">#</th>
                                        <th scope="col">Thumbnail</th>
                                        <th scope="col">SKU</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Store Name</th>
                                        <th scope="col">Linked Category & Subcategory</th>
                                        <th scope="col">Added On</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php  $i=1; @endphp
                                    @foreach($products as $product)
                                    <tr class="remove_row_<?php echo $product->id; ?>">
                                        <td style="display:none;"></td>
                                        <th scope="row" class="sr-no"> {{$i++}}</th>
                                        <?php
                                        if (!empty($product->product_image)) {
                                            $img = $product->product_image;
                                        } else {
                                            $img = 'uploads/dummy.png';
                                        }
                                        ?>
                                        <td>
                                            <span class="img-icon">
                                                <img src="{{ asset('/storage/'.$img)}}" height="50" width="50" alt="img">
                                            </span>
                                        </td>
                                        <td>{{ (@$product->sku)?$product->sku:"N/A" }}</td>
                                        <td>
                                            {{ $product->title }}
                                        </td>
                                        <td>{{ @$product->shopDetails->store_name }}</td>
                                        <td style="width:20%;">
                                            <?php
                                            $catArr = [];
                                            if (@$product->proCategory && count($product->proCategory) > 0) {
                                                foreach ($product->proCategory as $val) {
                                                    $catArr[] = "<b><a href='" . route("admin.view.category", [@$val->categoryDetails->slug]) . "' target='_blank'>" . @$val->categoryDetails->name . "</a></b>";
                                                }
                                            }
                                            if (@$catArr && count($catArr) > 0) {
                                                echo implode(", ", $catArr);
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>
                                        </td>
                                        <td>{{ date_format(@$product->created_at,"d M Y") }}</td>
                                        <th scope="col">
                                            @if($product->status == 1) 
                                            <a onclick="" href="javascript:void(0);" class="actions status_update" data-status="0" data-slug="{{ $product->slug }}">
                                                <span class="badge badge-success">Active</span>
                                            </a> 
                                            @else 
                                            <a  onclick="" href="javascript:void(0);" class="actions status_update" data-status="1" data-slug="{{ $product->slug }}">
                                                <span class="badge badge-danger">Inactive</span>
                                            </a> 
                                            @endif 
                                        </th>
                                        <td class="action action_cls" style="float:left;">
                                            <a class="icon-btn preview" href="{{ route('admin.view.product',$product->slug) }}">		
                                                <button type="button" itle="View" class="icon-btn preview"><i class="fal fa-eye"></i></button>
                                            </a>
                                            <a class="icon-btn edit" href="{{ route('admin.edit.product',$product->slug) }}">
                                                <button type="button" itle="Edit" class="icon-btn edit"><i class="fal fa-edit"></i></button>
                                            </a>
                                            <a href="javascript:void(0);" onclick="confirmDelete('<?php echo $product->slug; ?>',<?php echo $product->id; ?>);"  class="actions" data-slug="{{ $product->slug }}">
                                                <button title="Delete" type="button" class="icon-btn delete"><i class="fal fa-times"></i></button>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    jQuery(function () {
        var table = jQuery("#dataTable").DataTable({
            'columnDefs': [{
                    'targets': [2, 6, 8, 9],
                    'orderable': false
                }],
            stateSave: true
        });

        jQuery('#category').select2({
            placeholder: "Select",
            allowClear: true
        });
        jQuery('#store').select2({
            placeholder: "Select",
            allowClear: true
        });

        jQuery("#datatable_reset").on("click", function () {
            // $('#dataTable').DataTable().clear().draw(); 
            // $('#dataTable').DataTable().draw(); 
             window.location.href = '<?php echo route('admin.products.list'); ?>';
        });
    });

    function confirmDelete(dataSlug, row_id) {
        var this_id = row_id;
        swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (!isConfirm)
                return;
            jQuery.ajax({
                url: '{{ route("admin.delete.product") }}',
                type: "POST",
                data: {_token: '{{ csrf_token() }}', slug: dataSlug},
                dataType: "json",
                beforeSend: function () {
                    jQuery(".loading-box").show();
                },
                success: function (response) {
                    console.log(response);
                    if (response.status == "200") {
                        jQuery('.remove_row_' + this_id).remove();
                        swal("Done!", "Row deleted successfully!", "success");
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
    }

    jQuery(document).ready(function () {
        /*
         * Update Customer Status 
         */
        jQuery(document).on("click", ".status_update", function () {
            var status = jQuery(this).attr("data-status");
            var slug = jQuery(this).attr("data-slug");
            var this_id = jQuery(this);
            jQuery.ajax({
                url: '{{ route("admin.product.status") }}',
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
                    //alert(xhr.statusText + xhr.responseText);
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
    jQuery(document).ready(function () {
        jQuery(".reset-frm").on("click", function () {
            window.location.href = '<?php echo route('admin.products.list'); ?>';
        });

        jQuery(document).ready(function () {
            jQuery(function () {
                var dateToday = new Date();
                var dates = jQuery("#start-date, #end-date").datepicker({
                    dateFormat: "yy-mm-dd",
                    defaultDate: "+0w",
                    changeMonth: true,
                    numberOfMonths: 1,
                    maxDate: dateToday,
                    onSelect: function (selectedDate) {
                        var option = this.id == "start-date" ? "minDate" : "maxDate",
                                instance = jQuery(this).data("datepicker"),
                                date = jQuery.datepicker.parseDate(instance.settings.dateFormat || jQuery.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                        dates.not(this).datepicker("option", option, date);
                    }
                });
            });
        });
    });
</script>
@endsection

