@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Add New Email Template</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.emails')}}">Email Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Template</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>Add Email Manager</strong></div>
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

            <form action="{{route('admin.add.email.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Title</label>
                        <input class="form-control" id="title" minlength="1" maxlength="255" name="title" type="text" title="Title" placeholder="Please Enter Title" value="{{old('title')}}" autocomplete="title">
                        @if ($errors->has('title'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('title') }}</div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label>Subject</label>
                        <input class="form-control" id="subject" name="subject" minlength="1" maxlength="255" type="text" title="Subject" placeholder="Please Enter Subject" value="{{old('subject')}}" autocomplete="subject">
                        @if ($errors->has('subject'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('subject') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-12 col-form-label" for="text-input">Body</label>
                    <div class="col-md-12">
                        <textarea class="form-control" id="content" name="content" placeholder="Please enter email template"></textarea>
                        @if ($errors->has('content'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('content') }}</div>
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
    jQuery(document).ready(function () {
        jQuery(".reset_form").on("click", function () {
            window.location.href = '<?php echo route('admin.emails'); ?>';
        });
    });
</script>
@endsection

