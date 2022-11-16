@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Edit CMS Page</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.pages.list')}}">CMS Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit CMS Page</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Edit CMS Page</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.edit.page.post') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" value="{{$page->slug}}" name="slug"/>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">Title <span style="color:red;">*</span></label>
                            <input class="form-control" id="title" name="title" type="text" minlength="1" maxlength="255" title="Title" placeholder="Please Enter Page Title" autocomplete="off" value="{{ @$page->title }}">
                            @if ($errors->has('title'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">Description <span style="color:red;">*</span></label>
                            <textarea class="form-control" id="description" name="description">{{ @$page->description }}</textarea>
                            @if ($errors->has('description'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name">Status <span style="color:red;">*</span></label>
                            <div class="form-check form-check-inline form-check-sm mr-2">
                                <input type="radio"  class="form-check-input" id="inline-radio1" name="status" value="1" <?php echo (@$page->status == "1") ? 'checked="checked"' : ""; ?>>
                                <label class="form-check-label" for="inline-radio1">Active</label>
                            </div>
                            <div class="form-check form-check-inline form-check-sm mr-2">
                                <input type="radio" class="form-check-input" id="inline-radio2" name="status" value="0" <?php echo (@$page->status == "0") ? 'checked="checked"' : ""; ?>>
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
<script type="text/javascript">
    //CKEDITOR.replace('content');
    CKEDITOR.replace('description', {
        filebrowserUploadUrl: "{{route('admin.page.ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });

    jQuery(document).ready(function () {
        jQuery(".reset_form").on("click", function () {
            location.reload();
        });
    });
</script>
@endsection



