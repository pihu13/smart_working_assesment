<div class="col-sm-12 mb-4">
    <h2>Add Variation Option</h2>
</div>
<div class="col-sm-12 mb-4">
    <div class="box bg-white">
        <div class="box-row">
            <div class="box-content">
                <form action="" id="frm_variable_option" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="status" id="status" value="1" />
                    <input type="hidden" name="product_id" id="product_id" value="{{ @$id }}" />
                    
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label" for="text-input">Product Variant <span style="color:red;">*</span></label>
                            <select name="product_variant_id" id="info_o_product_variant_id" class="form-control">
                                <option value="">Select Variant</option>
                                <?php
                                foreach ($variables as $variable) {
                                    ?>
                                    <option value="{{ $variable->id }}">
                                        {{ $variable->variant_name }}
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                            @if ($errors->has('product_variant_id'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('product_variant_id') }}</div>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <label class="col-form-label" for="text-input">Option Name <span style="color:red;">*</span></label>
                            <select name="option_name[]" id="info_op_option_name" class="info_op_option_name form-control" multiple="multiple">
                            </select>
                            @if ($errors->has('option_name'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('option_name') }}</div>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <div id="info_o_option_name">
                                
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-primary add_variable_option_cls" type="submit">Save</button> 
                </form>
            </div>
        </div>
    </div>
</div>

