<?php
$current = env('DEFAULT_CURRENCY');
?>

<div class="card variation_section_div">
    <div class="card-header">
        <strong>Product Variation</strong>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        Product Variations <b>(Please add at least one variation option before combination add)</b>
                    </div>
                </div>
            </div>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @php  $i=1; @endphp
                                    @foreach($variables as $variable)
                                    <tr>
                                        <th scope="row" class="sr-no"> {{$i++}}</th>
                                        <td>{{ $variable->variant_name }}</td>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @php  $i=1; @endphp
                                    @foreach($combinations as $combination)
                                    <tr>
                                        <th scope="row" class="sr-no"> {{$i++}}</th>
                                        <td>
                                            {{ $combination->product_combination }}
                                        </td>
                                        <?php
                                        $countArr = 0;
                                        $arr = json_decode($combination->product_combination_names);//explode("-", $combination->product_combination);
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