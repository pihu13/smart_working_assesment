<div class="col-sm-12 mb-4">
    <h2>Edit Product Combination</h2>
</div>
<div class="col-sm-12 mb-4">
    <div class="box bg-white">
        <div class="box-row">
            <div class="box-content">
                <form action="" class="combination_update" id="combination_update" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="com_id" id="com_id" value="{{ base64_encode($combinations['id']) }}" />
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label" for="text-input">Price <span class="red_require">*</span></label>
                            <input class="form-control" minlength="1" maxlength="5" id="info_com_price" name="price" type="text" title="Price" placeholder="Please enter price" autocomplete="off" value="{{ $combinations["price"] }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label" for="text-input">Sale Price</label>
                            <input class="form-control" minlength="1" maxlength="5" id="info_com_sale_price" name="sale_price" type="text" title="Price" placeholder="Please enter sale price" autocomplete="off" value="{{ $combinations["sale_price"] }}">
                        </div>
                    </div>

<!--                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label">Product Tax</label>
                            <input class="form-control product_tax" id="info_com_product_tax" minlength="1" maxlength="10" name="product_tax" type="text" title="Product Tax" placeholder="Please Enter Product Tax" autocomplete="off" value="{{ $combinations["product_tax"] }}">
                        </div>
                    </div>-->

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label" for="text-input">Quantity <span class="red_require">*</span></label>
                            <input class="form-control" id="info_com_quantity" minlength="1" maxlength="5" name="quantity" type="text" title="Quantity" placeholder="Please enter quantity" autocomplete="off" value="{{ $combinations["quantity"] }}">
                        </div>
                    </div>
                    <button class="btn btn-sm btn-primary update_com_btn" type="submit">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>