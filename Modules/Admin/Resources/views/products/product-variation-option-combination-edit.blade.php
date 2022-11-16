<?php
$current = env('DEFAULT_CURRENCY');
?>

<div class="card variation_section_div">
    <div class="card-header">
        <strong>Product Variations</strong>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Product Variant -->
            <div class="col-sm-3 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content table-responsive">
                            <div class="card">
                                <div class="card-header">Product Variations
                                    <a href="javascript:void(0);" class="add_veriable ml-2 float-right" data-id="{{ base64_encode($id) }}">
                                        <span class="badge badge-primary float-right">Add Variation</span>
                                    </a>
                                </div>
                            </div>
                            <table id="dataTable1" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" class="sr-no">#</th>
                                        <th scope="col">Variation Name</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php  $i=1; @endphp
                                    @foreach($variables as $variable)
                                    <tr>
                                        <th scope="row" class="sr-no"> {{$i++}}</th>
                                        <td>{{ $variable->variant_name }}</td>
                                        <th scope="col">
                                            @if($variable->status == 1) 
                                            <a onclick="" href="javascript:void(0);" class="actions status_update" data-status="0" data-slug="{{ $variable->id }}">
                                                <span class="badge badge-success">Active</span>
                                            </a> 
                                            @else 
                                            <a  onclick="" href="javascript:void(0);" class="actions status_update" data-status="1" data-slug="{{ $variable->id }}">
                                                <span class="badge badge-danger">Inactive</span>
                                            </a> 
                                            @endif 
                                        </th>
                                        <td class="action" style="float: left;">
                                            <a href="{{ route('admin.delete.product.variation', [$variable->id,$variable->product_id]) }}" onclick="return confirm('Are you sure you want to delete this variation?');"  class="actions" data-id="{{ $variable->id }}" data-token="{{ csrf_token() }}">
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

            <!-- Variation Option -->
            <div class="col-sm-3 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content table-responsive">
                            <div class="card">
                                <div class="card-header">
                                    <b>Variation Option</b>
                                    <a href="javascript:void(0);" class="add_variable_option" data-id="{{ base64_encode($id) }}">
                                        <span class="badge badge-primary float-right">Add Variation Option</span>
                                    </a>
                                </div>
                            </div>
                            <table id="dataTable2" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Variable Name</th>
                                        <th scope="col">Option Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($variables as $variable) {
                                        $options = Helper::getVeriableOption($variable->id);
                                        foreach ($options as $option) {
                                            ?>
                                            <tr>
                                                <td>{{ $variable->variant_name }}</td>
                                                <td>{{ $option->option_name }}</td>
                                                <td class="action" style="float: left;">
                                                    <a href="{{ route('admin.delete.product.option', [$option->id,$variable->id,$variable->product_id]) }}" onclick="return confirm('Are you sure you want to delete this variation option?');"  class="actions" data-id="{{ $variable->id }}" data-token="{{ csrf_token() }}">
                                                        <button title="Delete" type="button" class="icon-btn delete"><i class="fal fa-times"></i></button>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Combination -->
            <div class="col-sm-6 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content table-responsive">
                            <div class="card">
                                <div class="card-header">
                                    <b>Product Combinations</b>
                                    <a href="javascript:void(0);" class="add_pro_combination" data-id="{{ base64_encode($id) }}">
                                        <span class="badge badge-primary float-right">Add Combination</span>
                                    </a>
                                </div>
                            </div>
                            <table id="dataTable3" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" class="sr-no">#</th>
                                        <th scope="col">Combination</th>
                                        <?php
                                        if (@$product->getVariableCom && count($product->getVariableCom) > 0) {
                                            foreach (@$product->getVariableCom as $gval) {
                                                if (!$gval->getOption->isEmpty()) {
                                                    ?>
                                                    <th scope="col">{{ $gval->variant_name }}</th>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                        <th scope="col">Price</th>
                                        <th scope="col">Sale Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col" class="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php  $i=1; @endphp
                                    @if(@$product->productCombination && count(@$product->productCombination) > 0)
                                    @foreach(@$product->productCombination as $combination)
                                    <tr>
                                        <th scope="row" class="sr-no"> {{$i++}}</th>
                                        <td>
                                            {{ $combination->product_combination }}
                                        </td>
                                        <?php
                                        $countArr = 0;
                                        $arr = json_decode($combination->product_combination_names); //explode("-", $combination->product_combination);
                                        if (@$arr && !empty($arr)) {
                                            $countArr = count($arr);
                                        }
                                        $vriCount = 0;
                                        if (@$product->getVariableCom && count($product->getVariableCom) > 0) {
                                            $vriCount = 0;
                                            foreach (@$product->getVariableCom as $gval) {
                                                if (!$gval->getOption->isEmpty()) {
                                                    $vriCount += 1;
                                                }
                                            }
                                        }
                                        $loop = 0;
                                        if ($countArr != $vriCount) {
                                            $loop = $vriCount - $countArr;
                                        }
                                        if (@$arr) {
                                            foreach (@$arr as $arrEach) {
                                                ?>
                                                <td>{{ ucwords($arrEach) }}</td>
                                                <?php
                                            }
                                        }
                                        if ($loop > 0) {
                                            for ($i = 1; $i <= $loop; $i++) {
                                                echo '<td>N/A</td>';
                                            }
                                        }
                                        ?>
                                        <td>
                                            <?php echo $current . $combination->price; ?>
                                        </td>
                                        <td>
                                            <?php echo ($combination->sale_price) ? $current . $combination->sale_price : $current . "0.00"; ?>
                                        </td>

                                        <td>
                                            {{ $combination->quantity }}
                                        </td>
                                        <td class="action" style="float: left;">
                                            <a class="icon-btn edit edit_combilation_cls" href="javascript:void(0);" data-id="{{ base64_encode($combination->id) }}">
                                                <button type="button" itle="Edit" class="icon-btn edit"><i class="fal fa-edit"></i></button>
                                            </a>
                                            <a href="{{ route('admin.delete-combination', [$combination->id,@$combination->productDetails->id])}}" onclick="return confirm('Are you sure you want to delete this combination?');"  class="actions" data-id="" data-token="{{ csrf_token() }}">
                                                <button title="Delete" type="button" class="icon-btn delete"><i class="fal fa-times"></i></button>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>