@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Add New Option</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.system.settings')}}">System Setting</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Option</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Add Option</strong>
        </div>
        <div class="card-body">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                <p>{{ $message }}</p>
            </div>
            @endif

            <form action="{{route('admin.add.system.setting.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group row">
                    <label class="col-md-12 col-form-label" for="text-input">Name</label>
                    <div class="col-md-12">
                        <input class="form-control" id="option_name" name="option_name" type="text" title="nf-title" placeholder="Please Enter Name" autocomplete="option_name">
                        @if ($errors->has('option_name'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('option_name') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-12 col-form-label" for="text-input">Value</label>
                    <div class="col-md-12">
                        <input class="form-control" id="option_value" name="option_value" type="text" title="nf-title" placeholder="Please Enter Value" autocomplete="option_value">
                        @if ($errors->has('option_name'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('option_value') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-12 col-form-label" for="text-input">Setting Type</label>
                    <div class="col-md-12">
                        <select name="setting_type" id="setting_type" class="form-control">
                            <option value="">Select</option>
                            <option value="smtp">SMTP</option>
                            <option value="stripe">Stripe</option>
                            <option value="footersocialmedia">Footer Social Media</option>
                            <option value="currency">Currency</option>
                            <option value="header_content">Header Content</option>
                            <option value="email_footer_content">Email Footer Content</option>
                        </select>
                        @if ($errors->has('option_name'))
                        <div class="invalid-feedback" style="display:block;">
                            {{ $errors->first('option_value') }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-12 col-form-label" for="text-input">Status</label>
                    <div class="col-md-12">
                        <div class="form-check form-check-inline form-check-sm mr-2">
                            <input type="radio"  class="form-check-input" id="inline-radio1" name="status" value="1"  >
                            <label class="form-check-label" for="inline-radio1">Active</label>
                        </div>
                        <div class="form-check form-check-inline form-check-sm mr-2">
                            <input type="radio" class="form-check-input" id="inline-radio2"" name="status" value="0" >
                            <label class="form-check-label" for="inline-radio2">Inactive</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        @if ($errors->has('status'))
                        <div class="invalid-feedback" style="display:block;">
                            {{ $errors->first('status') }}
                        </div>
                        @endif
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
<style type="text/css">
    .cke_reset{
        width: 100%;
    }
</style>
<script>
    CKEDITOR.replace('content');
    $(document).ready(function () {
        $(".reset_form").on("click", function () {
            window.location.href = '<?php echo route('admin.system.settings'); ?>';
        });
    });
</script>
@endsection

