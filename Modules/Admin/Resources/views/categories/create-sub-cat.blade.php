@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Create Sub-category</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.category.list')}}">Category Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Sub-category</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><strong>Add Sub-category</strong></div>
        <div class="card-body">
            @if (count($errors) > 0)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul style="list-style: none;">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            @endif
            <form action="{{ route('admin.add.subcategory.post') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="parent_id" id="parent_id" value="{{ @$id }}" />
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Category Name <span style="color:red;">*</span></label>
                            <input class="form-control" id="name" name="name" value="{{ old("name") }}" type="text" title="Category Name" minlength="1" maxlength="255" placeholder="Enter Category Name" autocomplete="off">
                            @if ($errors->has('name'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="name">Category Image <span style="color:red;">*</span></label>
                            <input type="file" name="cat_image_full" id="cat_image_full" accept=".png, .jpg, .jpeg" value="{{ old("cat_image_full") }}" class="form-control cat_image_full">
                            <span>Minimum image size 225*225</span>
                            @if ($errors->has('cat_image_full_db'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('cat_image_full_db') }}</div>
                            @endif
                        </div>
                    </div>
                    <?php
                    $SessionCatFullImgDb = Session::get('cat_full_img_db');
                    ?>
                    <input type="hidden" name="cat_image_full_db" id="cat_image_full_db" value="{{ $SessionCatFullImgDb }}" class="form-control">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <?php
                            $SessionCatFullImg = Session::get('cat_full_img');
                            if (isset($SessionCatFullImg)) {
                                $img = $SessionCatFullImg;
                            } else {
                                $img = asset('/storage/uploads/dummy.png');
                            }
                            ?>
                            <img src="{{ $img }}" id="cat_image_full_cls" width="100" height="100">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">Description</label>
                            <textarea name="description" class="form-control" minlength="3" maxlength="160" placeholder="Please enter description">{{ old("description") }}</textarea>
                            @if ($errors->has('description'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Status <span style="color:red;">*</span></label>
                            <div class="form-check form-check-inline form-check-sm mr-2">
                                <input type="radio"  class="form-check-input" id="inline-radio1" name="status" value="1" <?php echo (old("status") == '1') ? 'checked="checked"' : ""; ?> >
                                <label class="form-check-label" for="inline-radio1">Active</label>
                            </div>
                            <div class="form-check form-check-inline form-check-sm mr-2">
                                <input type="radio" class="form-check-input" id="inline-radio2" name="status" value="0" <?php echo (old("status") == '0') ? 'checked="checked"' : ""; ?> >
                                <label class="form-check-label" for="inline-radio2">Inactive</label>
                            </div>
                            @if ($errors->has('status'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <button class="btn btn-sm btn-primary" type="submit">Save</button>
                    <button class="btn btn-sm btn-danger reset_form" type="reset"> Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<div class="fa-modal class_crop_popup crop_img_cat" style="width:40%;">
    <div class="modal-wrap">
        <div class="fa-modal__close-btn"></div>
        <div class="panel panel-info">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div id="upload-cat-img"></div>
                    </div>

                    <div class="col-md-6">
                        <input type="file" name="cat_image_full" accept=".png, .jpg, .jpeg" id="cat_image_full" value="" class="form-control cat_image_full">
                        <span>Minimum image size 225*225</span>
                    </div>
                    <div class="col-md-6 text-center">
                        <button class="btn btn-primary btn-block upload-image">Upload Image</button>
                        <div class="alert alert-success" id="upload-success" style="display: none;margin-top:10px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        var resize = jQuery('#upload-cat-img').croppie({
            enableExif: true,
            enableOrientation: true, viewport: {// Default { width: 100, height: 100, type: 'square' } 
                width: 225,
                height: 225,
                type: 'square' //square
            },
            boundary: {
                width: 600,
                height: 600
            }
        });

        $modalCropImgCat = jQuery('.crop_img_cat').faModal();
        jQuery('.reset_form1').click(function () {
            $modalCropImgCat.faModal('show');
        });

        jQuery('.cat_image_full').on('change', function () {
            $modalCropImgCat.faModal('show');
            var reader = new FileReader();
            reader.onload = function (e) {
                resize.croppie('bind', {
                    url: e.target.result
                }).then(function () {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
        });

        jQuery('.upload-image').on('click', function (ev) {
            resize.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (img) {
                jQuery.ajax({
                    url: "{{route('admin.category.crop.image')}}",
                    type: "POST",
                    data: {_token: '{{ csrf_token() }}', "image": img},
                    success: function (data) {
                        jQuery("#cat_image_full_cls").attr('src', data.file_name);
                        jQuery("#cat_image_full_db").val(data.file_name_db);
                        $modalCropImgCat.faModal('hide');
                    }
                });
            });
        });

        jQuery(".reset_form").on("click", function () {
            location.reload();
        });
    });
</script>
@endsection


