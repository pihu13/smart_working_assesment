<div class="col-sm-12 mb-4">
    <h2>Add Variation</h2>
</div>
<div class="col-sm-12 mb-4">
    <div class="box bg-white">
        <div class="box-row">
            <div class="box-content">
                <form action="" class="frm_add_vari_cls" id="frm_add_vari_cls" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="product_id" id="product_id" value="{{ base64_encode(@$product["id"]) }}" />
                    <input type="hidden" name="status" id="status" value="1" />
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label" for="text-input">Variable Name <span style="color:red;">*</span></label>
                            <select name="variant_name[]" id="info_add_variant_name" class="info_add_variant_name form-control" multiple="multiple">
                            </select>

                            @if ($errors->has('variant_name'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('variant_name') }}</div>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <div id="info_v_variant_name">
                                
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-primary add_vari_cls" type="submit">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>