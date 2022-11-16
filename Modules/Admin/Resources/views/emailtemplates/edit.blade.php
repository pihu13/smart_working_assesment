@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Email Template</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.emails')}}">Email Template</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Template</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-12 col-md-4 mb-4">
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul style="list-style: none;">
                        @foreach ($errors->all() as $error)
                        <li>-{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            <div class="col-lg-12 col-md-4 mb-4">
                <form class="box bg-white" action="{{route('admin.edit.email.post')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="slug" value="{{$email->slug}}" />
                    <div class="box-row flex-wrap">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Title</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" minlength="1" maxlength="255" name="title" placeholder="Title" value="{{$email->title}}">
                                    @if ($errors->has('title'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('title') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Subject</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" minlength="1" maxlength="255" name="subject" placeholder="Subject" value="{{$email->subject}}">
                                    @if ($errors->has('subject'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('subject') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 mb-3">
                            <div class="form-group">
                                <label>Body</label>
                                <div class="input-group">
                                    <textarea class="form-control" id="content" name="content">{{$email->content}}</textarea>
                                    @if ($errors->has('content'))
                                    <div class="invalid-feedback" style="display:block;">{{ $errors->first('content') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>Instruction(Don't Change Below Short Code)</label>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        {!! $email->instruction !!}
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-sm btn-primary" type="submit">Save</button>
                            <button class="btn btn-sm btn-danger reset_form" type="reset"> Reset</button>
                        </div> 
                    </div>
                </form>
            </div>
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
<script type="text/javascript">
    CKEDITOR.replace('content');
    jQuery(document).ready(function () {
        jQuery(".reset_form").on("click", function () {
            window.location.href = '<?php echo route('admin.emails'); ?>';
        });
    });
</script>
@endsection

