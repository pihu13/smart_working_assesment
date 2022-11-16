@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Add Banner</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{route('admin.dashboard')}}">Home</a>
                        <li class="breadcrumb-item"><a href="{{ route('admin.banners') }}">Banner Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Banner</li>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Add Banner</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul style="list-style: none;padding-left: 0;">
                            @foreach ($errors->all() as $error)
                            <li><strong>Error!</strong> {{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <form action="{{ route("admin.add.banner.post") }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Title <span style="color:red;">*</span></label>
                            <input class="form-control" id="title" name="title" minlength="1" maxlength="100" type="text" title="Store Name" placeholder="Please Enter Title" autocomplete="off" value="{{ old("title") }}">
                            @if ($errors->has('title'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="name">Banner Image <span style="color:red;">*</span></label>
                            <input class="form-control banner_image" id="banner_image" accept=".png, .jpg, .jpeg, .svg" name="banner_image" type="file" title="Banner" placeholder="Please Choose Banner" autocomplete="off">
                            <span>Minimum image size 1011*406</span>
                            @if ($errors->has('banner_image_db'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('banner_image_db') }}</div>
                            @endif
                        </div>
                    </div>

                    <?php
                    if (@old("banner_image_db")) {
                        $img = old("banner_image_db");
                    } else {
                        $img = "uploads/dummy.png";
                    }
                    ?>
                    <input type="hidden" name="banner_image_db" id="banner_image_db" value="{{ @old("banner_image_db") }}" class="form-control">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="name"></label><br>
                            <img src="{{ asset('/storage/'.$img) }}" id="banner_img_cls" width="100" height="100">
                        </div>
                    </div>


                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">Content <span style="color:red;">*</span></label>
                            <textarea class="form-control" minlength="1" maxlength="100" id="content" name="content">{{ old("content") }}</textarea>
                            @if ($errors->has('content'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('content') }}</div>
                            @endif
                        </div>
                    </div>


                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">Status <span style="color:red;">*</span></label>
                            <div class="form-check form-check-inline form-check-sm mr-2">
                                <input type="radio"  class="form-check-input" id="inline-radio1" name="status" value="1" <?php echo (old("status") == "1") ? 'checked="checked"' : ""; ?>>
                                <label class="form-check-label" for="inline-radio1">Active</label>
                            </div>
                            <div class="form-check form-check-inline form-check-sm mr-2">
                                <input type="radio" class="form-check-input" id="inline-radio2" name="status" value="0" <?php echo (old("status") == "0") ? 'checked="checked"' : ""; ?>>
                                <label class="form-check-label" for="inline-radio2">Inactive</label>
                            </div>
                            @if ($errors->has('status'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
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
@endsection
@section('js')

<div class="fa-modal class_crop_popup crop_img_thamb crop_banner_img_cls" style="width: 80%;">
    <div class="modal-wrap">
        <div class="fa-modal__close-btn"></div>
        <div class="panel panel-info">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div id="upload-banner"></div>
                    </div>
                    <div class="col-md-6">
                        <input type="file" name="banner_image" accept=".png, .jpg, .jpeg" id="banner_image" class="form-control banner_image">
                        <span>Minimum image size 1011*406</span>
                    </div>
                    <div class="col-md-6 text-center">
                        <button class="btn btn-primary btn-block upload-btn-img">Upload Image</button>
                        <div class="alert alert-success" id="upload-success" style="display: none;margin-top:10px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".reset_form").on("click", function () {
            location.reload();
        });
    });

    /*
     * Store Logo
     */
    jQuery(document).ready(function () {
        var resize = jQuery('#upload-banner').croppie({
            enableExif: true,
            enableOrientation: true, viewport: {
                width: 1011,
                height: 406,
                type: 'square'
            },
            boundary: {
                width: 1100,
                height: 500
            }
        });
        $crop_banner_img_cls = jQuery('.crop_banner_img_cls').faModal();

        jQuery('.banner_image').on('change', function () {
            $crop_banner_img_cls.faModal('show');
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

        jQuery('.upload-btn-img').on('click', function (ev) {
            resize.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (img) {
                jQuery.ajax({
                    url: "{{ route('admin.upload.banner.img') }}",
                    type: "POST",
                    data: {_token: '{{ csrf_token() }}', "image": img},
                    success: function (data) {
                        console.log(data);
                        jQuery("#banner_img_cls").attr('src', data.file_name);
                        jQuery("#banner_image_db").val(data.file_name_db);
                        $crop_banner_img_cls.faModal('hide');
                    }
                });
            });
        });
    });
</script>
@endsection

