@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Send Notification</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{route('admin.dashboard')}}">Home</a>
                        <li class="breadcrumb-item"><a href="{{ route('admin.notification.list') }}">Notification Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Send Notification</li>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Send Notification</strong>
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

        <form action="{{ route("admin.send.notification.post") }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="card-body">
                <div class="row">

                    <div class="col-sm-12 select2cuscls mb-2">
                        <label for="select2">Select Customers <span style="color:red;">*</span></label>
                        <select class="form-control form-control-lg" id="cutomer_id" name="cutomer_id[]" multiple="multiple">
                            @foreach($customers as $customer)
                            <option value="{{$customer->id}}" <?php echo (@in_array($customer->id, old('cutomer_id'))) ? 'selected="selected"' : ""; ?>>
                                {{$customer->name}}
                            </option>
                            @endforeach
                        </select>
                        @if ($errors->has('cutomer_id'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('cutomer_id') }}</div>
                        @endif
                    </div>


                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">Title <span style="color:red;">*</span></label>
                            <input class="form-control" id="title" name="title" minlength="1" maxlength="100" type="text" title="Title" placeholder="Please Enter Title" autocomplete="off" value="{{ old("title") }}">
                            @if ($errors->has('title'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">Message <span style="color:red;">*</span></label>
                            <textarea class="form-control" minlength="1" maxlength="100" id="message" name="message">{{ old("message") }}</textarea>
                            @if ($errors->has('message'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('message') }}</div>
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
        jQuery('#cutomer_id').select2({
            placeholder: "Please Select",
            allowClear: true,
            allowHtml: true
        });
    });
</script>
@endsection

