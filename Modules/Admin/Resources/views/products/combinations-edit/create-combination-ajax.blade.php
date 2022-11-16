<div class="col-sm-12 mb-4">
    <h2>Add Product Combination</h2>
</div>
<div class="col-sm-12 mb-4">
    <div class="box bg-white">
        <div class="box-row">
            <div class="box-content">
                <form action="" class="form_add_combination" id="form_add_combination" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="product_id" value="{{ $product->id }}" />

                    <div class="form-group row">
                        <div class="col-md-12 com_product_combination_already">
                        </div>
                    </div>

                    <?php
                    foreach (@$product->getVariableCom as $gval) {
                        if (!$gval->getOption->isEmpty()) {
                            ?>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="col-form-label">{{ $gval->variant_name }} <span class="red_require">*</span></label>
                                    <select class="form-control" id="" name="combination[]">
                                        <option value="">Select</option>
                                        <?php
                                        foreach (@$gval->getOption as $option) {
                                            ?>
                                            <option value="<?php echo $option->option_name ?>">
                                                <?php echo $option->option_name ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <span id="com_product_combination"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label" for="text-input">Price <span class="red_require">*</span></label>
                            <input class="form-control" minlength="1" maxlength="5" id="com_price" name="price" type="text" value="{{old('price')}}" title="Price" placeholder="Please enter price" autocomplete="price">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label" for="text-input">Sale Price</label>
                            <input class="form-control" minlength="1" maxlength="5" id="com_sale_price" name="sale_price" type="text" value="{{old('sale_price')}}" title="Price" placeholder="Please enter sale price" autocomplete="price">
                        </div>
                    </div>

<!--                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label">Product Tax</label>
                            <input class="form-control" id="com_product_tax" minlength="1" maxlength="10" name="product_tax" type="text" title="Product Tax" placeholder="Please Enter Product Tax" autocomplete="off" value="{{ (old('product_tax'))?old('product_tax'):"0" }}">
                        </div>
                    </div>-->

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label" for="text-input">Quantity <span class="red_require">*</span></label>
                            <input class="form-control" id="com_quantity" minlength="1" maxlength="5" name="quantity" type="text" value="{{old('quantity')}}" title="Quantity" placeholder="Please enter quantity" autocomplete="quantity">
                        </div>
                    </div>
                    <button class="btn btn-sm btn-primary add_combination_btn_cls" type="submit">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>